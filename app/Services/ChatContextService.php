<?php

namespace App\Services;

use App\Models\Portfolio;
use App\Models\WorkExperience;
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
            $this->portfoliosSection(),
            $this->experienceSection(),
        ];

        return implode("\n\n", array_filter($sections));
    }

    private function profileSection(): string
    {
        return <<<'TEXT'
## Profile
- Name: Jawahar Ganesh (@ Jay / JayXCoder)
- Role: Full-Stack Developer & Computer Engineer (UniMAP, Malaysia)
- Focus: Laravel, React, Python, AI/ML, cybersecurity, embedded systems / IoT
- Site sections: Home, About, Skills, Projects, Portfolio, Experience, Contact, AI Chat
TEXT;
    }

    private function portfoliosSection(): string
    {
        $items = Portfolio::published()->ordered()->get();

        if ($items->isEmpty()) {
            return "## Published portfolio projects\n(No published projects in database yet.)";
        }

        $lines = ["## Published portfolio projects"];

        foreach ($items as $p) {
            $tech = $p->technologies_string ?: 'n/a';
            $features = $p->features ? implode('; ', array_slice($p->features, 0, 6)) : 'n/a';
            $lines[] = sprintf(
                "- **%s** (%s): %s | Tech: %s | Features: %s",
                $p->title,
                $p->category,
                Str::limit($p->short_description ?: $p->description, 200),
                $tech,
                Str::limit($features, 300)
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
            $period = $w->start_date?->format('Y-m').' – '.($w->is_current ? 'Present' : $w->end_date?->format('Y-m'));
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
}
