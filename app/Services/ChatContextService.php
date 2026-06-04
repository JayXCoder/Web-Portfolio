<?php

namespace App\Services;

use App\Models\Portfolio;
use App\Models\WorkExperience;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ChatContextService
{
    /**
     * Build knowledge text injected into the chat system prompt.
     */
    public function buildKnowledgeBase(): string
    {
        $sections = [
            $this->profileSection(),
            $this->skillsSection(),
            $this->portfoliosSection(),
            $this->experienceSection(),
        ];

        return implode("\n\n", array_filter($sections));
    }

    /**
     * @return list<string> Skill labels from config/skills.php
     */
    public function allSkillLabels(): array
    {
        $labels = [];

        foreach (config('skills.ai_tools', []) as $skill) {
            if (! empty($skill['label'])) {
                $labels[] = $skill['label'];
            }
        }

        $tree = config('skills.tree', []);
        foreach (['apex', 'stacks', 'foundation'] as $key) {
            foreach ($tree[$key]['skills'] ?? [] as $skill) {
                if (! empty($skill['label'])) {
                    $labels[] = $skill['label'];
                }
            }
        }

        foreach ($tree['branches'] ?? [] as $branch) {
            foreach ($branch['skills'] ?? [] as $skill) {
                if (! empty($skill['label'])) {
                    $labels[] = $skill['label'];
                }
            }
        }

        return array_values(array_unique($labels));
    }

    /**
     * Skills mentioned in the user message that Jay lists on his site.
     *
     * @return list<string>
     */
    public function findMatchingSkills(string $message): array
    {
        $haystack = strtolower($message);
        $matched = [];

        foreach ($this->allSkillLabels() as $label) {
            if ($this->messageMentionsSkill($haystack, $label)) {
                $matched[] = $label;
            }
        }

        foreach (config('chat.skill_aliases', []) as $canonical => $aliases) {
            foreach ($aliases as $alias) {
                if (! str_contains($haystack, strtolower($alias))) {
                    continue;
                }

                $resolved = null;
                $canonicalLower = strtolower($canonical);
                foreach ($this->allSkillLabels() as $label) {
                    $labelLower = strtolower($label);
                    if (str_contains($labelLower, $canonicalLower) || str_contains($canonicalLower, $labelLower)) {
                        $resolved = $label;
                        break;
                    }
                }

                $matched[] = $resolved ?? ucwords($canonical);
                break;
            }
        }

        return array_values(array_unique($matched));
    }

    /**
     * Published portfolios related to the user message (tech, title, description).
     *
     * @return Collection<int, Portfolio>
     */
    public function findRelatedPortfolios(string $message): Collection
    {
        $terms = $this->searchTermsFromMessage($message);
        $skillMatches = $this->findMatchingSkills($message);

        foreach ($skillMatches as $skill) {
            $terms[] = strtolower($skill);
            $terms[] = str_replace(' ', '', strtolower($skill));
        }

        $terms = array_values(array_unique(array_filter($terms, fn ($t) => strlen($t) >= 2)));

        if ($terms === []) {
            return collect();
        }

        return Portfolio::published()->ordered()->get()->filter(function (Portfolio $p) use ($terms) {
            $blob = strtolower(implode(' ', array_filter([
                $p->title,
                $p->short_description,
                $p->description,
                $p->category,
                $p->technologies_string,
                is_array($p->features) ? implode(' ', $p->features) : '',
            ])));

            foreach ($terms as $term) {
                if (str_contains($blob, $term)) {
                    return true;
                }
            }

            return false;
        })->values()->take(4);
    }

    /**
     * @return list<array{title: string, url: string, slug: string}>
     */
    public function formatProjectsForChat(Collection $portfolios): array
    {
        return $portfolios->map(fn (Portfolio $p) => [
            'title' => $p->title,
            'slug' => $p->slug,
            'url' => route('portfolio.item', $p),
        ])->all();
    }

    /**
     * Hint appended to the system prompt for this turn when skills/projects match.
     */
    public function buildTurnHint(string $message): string
    {
        $skills = $this->findMatchingSkills($message);
        $projects = $this->findRelatedPortfolios($message);

        if ($skills === [] && $projects->isEmpty()) {
            return '';
        }

        $lines = ['## Hint for this user question'];

        if ($skills !== []) {
            $lines[] = 'Technologies matched on Jay\'s Skills page: '.implode(', ', $skills).'.';
            $lines[] = 'The user is asking about experience with these. Answer YES for each (they are listed on his site). Do not deny experience.';
        }

        if ($projects->isNotEmpty()) {
            $lines[] = 'Related portfolio projects (mention by name; UI will show View buttons):';
            foreach ($projects as $p) {
                $lines[] = "- {$p->title} (slug: {$p->slug})";
            }
        }

        return implode("\n", $lines);
    }

    private function profileSection(): string
    {
        return <<<'TEXT'
## Profile
- Name: Jawahar Ganesh (@ Jay / JayXCoder)
- Role: Full-Stack Developer & Computer Engineer (UniMAP, Malaysia)
- Focus: Laravel, React, Python, AI/ML (including Ollama, vLLM, PyTorch, Hugging Face), cybersecurity, embedded systems / IoT
- Skills page: lists all technologies he works with (authoritative for "do you know X?" questions)
- Site sections: Home, About, Skills, Projects, Portfolio, Experience, Contact, AI Chat
TEXT;
    }

    private function skillsSection(): string
    {
        $grouped = [];

        foreach (config('skills.ai_tools', []) as $skill) {
            $grouped['Productive AI tools'][] = $skill['label'];
        }

        $tree = config('skills.tree', []);
        foreach (['apex' => 'Full Stack', 'stacks' => 'Stacks & UI', 'foundation' => 'Languages'] as $key => $title) {
            foreach ($tree[$key]['skills'] ?? [] as $skill) {
                $grouped[$title][] = $skill['label'];
            }
        }

        foreach ($tree['branches'] ?? [] as $branch) {
            $title = $branch['title'] ?? 'Other';
            foreach ($branch['skills'] ?? [] as $skill) {
                $grouped[$title][] = $skill['label'];
            }
        }

        $lines = ['## Skills & technologies (Jay has hands-on experience with these)'];
        foreach ($grouped as $title => $items) {
            $lines[] = '- **'.$title.'**: '.implode(', ', $items);
        }

        return implode("\n", $lines);
    }

    private function portfoliosSection(): string
    {
        $items = Portfolio::published()->ordered()->get();

        if ($items->isEmpty()) {
            return "## Published portfolio projects\n(No published projects in database yet.)";
        }

        $lines = ['## Published portfolio projects'];

        foreach ($items as $p) {
            $tech = $p->technologies_string ?: 'n/a';
            $lines[] = sprintf(
                '- **%s** (%s) | slug: %s | Tech: %s | Summary: %s',
                $p->title,
                $p->category,
                $p->slug,
                $tech,
                Str::limit($p->short_description ?: $p->description, 200)
            );
        }

        return implode("\n", $lines);
    }

    private function experienceSection(): string
    {
        $items = WorkExperience::published()->ordered()->get();

        if ($items->isEmpty()) {
            return "## Work experience\n(No published experience entries yet.)";
        }

        $lines = ['## Work experience'];

        foreach ($items as $w) {
            $period = $w->start_date?->format('Y-m').' to '.($w->is_current ? 'Present' : $w->end_date?->format('Y-m'));
            $tech = $w->technologies_string ?: '';
            $lines[] = sprintf(
                '- **%s** at %s (%s, %s)%s',
                $w->position,
                $w->company,
                $w->employment_type,
                $period,
                $tech !== '' ? ' | Tech: '.$tech : ''
            );
            if ($w->description) {
                $lines[] = '  '.Str::limit($w->description, 180);
            }
        }

        return implode("\n", $lines);
    }

    private function messageMentionsSkill(string $haystack, string $label): bool
    {
        $label = strtolower($label);
        if (str_contains($haystack, $label)) {
            return true;
        }

        $compact = str_replace([' ', '.', '/'], '', $label);

        return $compact !== '' && str_contains(str_replace([' ', '.', '/'], '', $haystack), $compact);
    }

    /**
     * @return list<string>
     */
    private function searchTermsFromMessage(string $message): array
    {
        $terms = [];
        preg_match_all('/[a-z0-9][a-z0-9.+#_-]*/i', strtolower($message), $matches);

        foreach ($matches[0] ?? [] as $word) {
            if (strlen($word) >= 2) {
                $terms[] = $word;
            }
        }

        return $terms;
    }
}
