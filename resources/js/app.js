import './bootstrap';
import { initHomeHero } from './home-hero';

window.__skillIconFallback = function (img) {
    const fallback = img.dataset.fallback;
    if (fallback && img.src !== fallback) {
        img.src = fallback;
        img.classList.add('skill-icon-img--mono');
        return;
    }
    const wrap = document.createElement('span');
    wrap.className = 'skill-icon-wrap';
    wrap.innerHTML =
        '<svg class="skill-icon-fallback" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5"/></svg>';
    img.replaceWith(wrap);
};

document.addEventListener('DOMContentLoaded', () => {
    initMobileNav();
    initHomeHero();
    initScrollReveal();
    initVisitorStats();
    initPortfolioFilters();
    initAdminSidebar();
    initPortfolioAi();
});

function initMobileNav() {
    const toggle = document.getElementById('nav-toggle');
    const menu = document.getElementById('nav-menu');
    if (!toggle || !menu) return;

    toggle.addEventListener('click', () => {
        const open = menu.classList.toggle('hidden');
        toggle.setAttribute('aria-expanded', open ? 'false' : 'true');
    });

    menu.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 1024) {
                menu.classList.add('hidden');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    });
}

function initScrollReveal() {
    const targets = document.querySelectorAll('.fade-in-view');
    if (!targets.length) return;

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.08, rootMargin: '0px 0px -40px 0px' }
    );

    targets.forEach((el) => observer.observe(el));
}

function initVisitorStats() {
    const ids = ['totalVisitors', 'totalPageViews', 'todayVisitors'];
    if (!ids.some((id) => document.getElementById(id))) return;

    fetch('/api/visitor-stats')
        .then((r) => r.json())
        .then((data) => {
            setText('totalVisitors', data.total_visitors ?? 0);
            setText('totalPageViews', data.total_page_views ?? 0);
            setText('todayVisitors', data.today_visitors ?? 0);
        })
        .catch(() => ids.forEach((id) => setText(id, '-')));
}

function setText(id, value) {
    const el = document.getElementById(id);
    if (el) el.textContent = String(value);
}

function initPortfolioFilters() {
    const grid = document.getElementById('portfolio-grid');
    const buttons = document.querySelectorAll('[data-filter]');
    if (!grid || !buttons.length) return;

    buttons.forEach((btn) => {
        btn.addEventListener('click', () => {
            const filter = btn.dataset.filter;
            buttons.forEach((b) => {
                b.classList.toggle('filter-chip-active', b === btn);
            });

            grid.querySelectorAll('[data-category]').forEach((item) => {
                const show = filter === 'all' || item.dataset.category === filter;
                item.classList.toggle('hidden', !show);
            });
        });
    });
}

function initAdminSidebar() {
    const sidebar = document.getElementById('admin-sidebar');
    const toggle = document.getElementById('admin-sidebar-toggle');
    const overlay = document.getElementById('admin-sidebar-overlay');
    if (!sidebar || !toggle) return;

    const open = () => {
        sidebar.classList.remove('-translate-x-full');
        overlay?.classList.remove('hidden');
    };
    const close = () => {
        sidebar.classList.add('-translate-x-full');
        overlay?.classList.add('hidden');
    };

    toggle.addEventListener('click', () => {
        sidebar.classList.contains('-translate-x-full') ? open() : close();
    });
    overlay?.addEventListener('click', close);
}

function initPortfolioAi() {
    const form = document.getElementById('portfolio-ai-form');
    if (!form) return;

    const generateBtn = document.getElementById('ai-generate-btn');
    const preview = document.getElementById('ai-preview');
    const statusEl = document.getElementById('ai-status');
    const saveBtn = document.getElementById('ai-save-btn');

    let lastDraft = null;

    generateBtn?.addEventListener('click', async () => {
        const files = form.querySelector('#markdown_files')?.files;
        const paste = form.querySelector('#markdown_paste')?.value?.trim();

        if ((!files || !files.length) && !paste) {
            showStatus(statusEl, 'Upload at least one .md file or paste markdown.', 'error');
            return;
        }

        generateBtn.disabled = true;
        showStatus(statusEl, 'Starting generation…', 'info');

        const body = new FormData(form);
        body.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');

        try {
            const res = await fetch(form.dataset.generateUrl, {
                method: 'POST',
                body,
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            const start = await parseJsonResponse(res);
            if (!res.ok || !start.success) {
                throw new Error(start.message || start.error || formatValidationErrors(start) || 'Generation failed');
            }

            let data = start;

            if (start.job_id) {
                data = await pollPortfolioAiJob(form, start.job_id, (message) => {
                    showStatus(statusEl, message || 'Generating with Ollama…', 'info');
                });
            }

            if (!data.portfolio) {
                throw new Error(data.message || 'Generation finished without a portfolio draft.');
            }

            lastDraft = data.portfolio;
            renderPreview(preview, data.portfolio);
            preview?.classList.remove('hidden');
            saveBtn?.classList.remove('hidden');
            showStatus(statusEl, data.message || 'Draft ready. Review and save.', 'success');
        } catch (e) {
            showStatus(statusEl, e.message, 'error');
            preview?.classList.add('hidden');
            saveBtn?.classList.add('hidden');
        } finally {
            generateBtn.disabled = false;
        }
    });

    saveBtn?.addEventListener('click', async () => {
        if (!lastDraft) return;
        saveBtn.disabled = true;
        showStatus(statusEl, 'Saving portfolio…', 'info');

        try {
            const res = await fetch(form.dataset.saveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ portfolio: lastDraft }),
            });
            const data = await parseJsonResponse(res);
            if (!res.ok || !data.success) {
                throw new Error(data.message || formatValidationErrors(data) || 'Save failed');
            }
            window.location.href = data.redirect || form.dataset.indexUrl;
        } catch (e) {
            showStatus(statusEl, e.message, 'error');
            saveBtn.disabled = false;
        }
    });
}

function sleep(ms) {
    return new Promise((resolve) => setTimeout(resolve, ms));
}

async function pollPortfolioAiJob(form, jobId, onProgress) {
    const baseUrl = form.dataset.jobUrl;
    const interval = Number(form.dataset.pollInterval || 2000);
    const maxAttempts = Number(form.dataset.pollMax || 150);

    if (!baseUrl) {
        throw new Error('Job status URL is not configured.');
    }

    for (let attempt = 0; attempt < maxAttempts; attempt++) {
        if (attempt > 0) {
            await sleep(interval);
        }

        const res = await fetch(`${baseUrl}/${jobId}`, {
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        });
        const data = await parseJsonResponse(res);

        if (!res.ok) {
            throw new Error(data.message || 'Could not check generation status.');
        }

        if (data.status === 'processing') {
            onProgress?.(data.message);
            continue;
        }

        if (data.status === 'completed' && data.success && data.portfolio) {
            return data;
        }

        throw new Error(data.message || 'Generation failed.');
    }

    throw new Error(
        'Generation is taking longer than expected. If Ollama is still running, wait and try again, or use less markdown.',
    );
}

async function parseJsonResponse(res) {
    const text = await res.text();
    const contentType = res.headers.get('content-type') || '';

    if (!contentType.includes('application/json')) {
        const snippet = text.replace(/\s+/g, ' ').trim().slice(0, 160);
        throw new Error(
            snippet
                ? `Server returned an error page instead of JSON: ${snippet}`
                : `Server returned ${res.status} without JSON (check PHP/nginx logs).`,
        );
    }

    try {
        return JSON.parse(text);
    } catch {
        throw new Error('Server returned invalid JSON. Try again or check server logs.');
    }
}

function formatValidationErrors(data) {
    if (!data?.errors || typeof data.errors !== 'object') {
        return '';
    }

    return Object.values(data.errors).flat().join(' ');
}

function showStatus(el, message, type) {
    if (!el) return;
    el.textContent = message;
    el.className =
        'mt-4 rounded-xl px-4 py-3 text-sm ' +
        (type === 'error'
            ? 'bg-danger/10 text-red-300 border border-danger/30'
            : type === 'success'
              ? 'bg-success/10 text-green-300 border border-success/30'
              : 'bg-uv/10 text-uv-bright border border-uv/30');
    el.classList.remove('hidden');
}

function renderPreview(container, portfolio) {
    if (!container) return;
    const tech = (portfolio.technologies || []).join(', ');
    const features = (portfolio.features || []).map((f) => `<li>${escapeHtml(f)}</li>`).join('');
    container.innerHTML = `
        <h3 class="font-display text-xl font-bold text-text">${escapeHtml(portfolio.title)}</h3>
        <p class="mt-2 text-sm text-text-muted">${escapeHtml(portfolio.short_description || '')}</p>
        <p class="mt-3 text-sm text-text-dim line-clamp-4">${escapeHtml(portfolio.description || '')}</p>
        <div class="mt-4 flex flex-wrap gap-2">${(portfolio.technologies || []).map((t) => `<span class="badge-uv">${escapeHtml(t)}</span>`).join('')}</div>
        <p class="mt-3 text-xs text-text-dim">Category: ${escapeHtml(portfolio.category || '-')} · Slug: ${escapeHtml(portfolio.slug || '-')}</p>
        ${features ? `<ul class="mt-3 list-disc pl-5 text-sm text-text-muted space-y-1">${features}</ul>` : ''}
    `;
}

function escapeHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}
