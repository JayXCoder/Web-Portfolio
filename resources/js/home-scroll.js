/**
 * Seasats-style continuous scroll interactions for the home page.
 */

export function initHomeScroll() {
    const root = document.querySelector('[data-home-scroll]');
    if (!root) return;

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    initSignals(root);
    initChapters(root, reduceMotion);
    initMissionRail(root);
    initDomains(root);
    initTelemetry(root, reduceMotion);
}

function initSignals(root) {
    const signals = root.querySelectorAll('[data-signal]');
    if (!signals.length) return;

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-lit');
                } else if (entry.boundingClientRect.top > 0) {
                    entry.target.classList.remove('is-lit');
                }
            });
        },
        { threshold: 0.55, rootMargin: '-20% 0px -35% 0px' }
    );

    signals.forEach((el) => observer.observe(el));
}

function initChapters(root, reduceMotion) {
    const section = root.querySelector('[data-chapters]');
    const spacer = root.querySelector('[data-chapter-spacer]');
    const progress = root.querySelector('[data-chapter-progress] span');
    const panels = root.querySelectorAll('[data-chapter]');
    const tabs = root.querySelectorAll('[data-chapter-tab]');
    const stageLabel = root.querySelector('[data-stage-label]');
    if (!section || !spacer || !panels.length) return;

    const labels = ['01 / BUILD', '02 / SECURE', '03 / DEPLOY'];
    let active = 0;

    const setChapter = (index, { syncScroll = false } = {}) => {
        active = Math.max(0, Math.min(panels.length - 1, index));
        panels.forEach((panel, i) => {
            const on = i === active;
            panel.classList.toggle('is-active', on);
            panel.hidden = !on;
        });
        tabs.forEach((tab, i) => {
            const on = i === active;
            tab.classList.toggle('is-active', on);
            tab.setAttribute('aria-selected', on ? 'true' : 'false');
        });
        if (stageLabel) stageLabel.textContent = labels[active] ?? labels[0];
        if (progress) progress.style.width = `${((active + 1) / panels.length) * 100}%`;

        if (syncScroll && !reduceMotion) {
            const rect = section.getBoundingClientRect();
            const start = window.scrollY + rect.top;
            const travel = Math.max(spacer.offsetHeight - window.innerHeight, 1);
            const target = start + (active / Math.max(panels.length - 1, 1)) * travel;
            window.scrollTo({ top: target, behavior: 'smooth' });
        }
    };

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            setChapter(Number(tab.dataset.chapterTab), { syncScroll: true });
        });
    });

    const onScroll = () => {
        const rect = section.getBoundingClientRect();
        const total = Math.max(section.offsetHeight - window.innerHeight, 1);
        const scrolled = Math.min(Math.max(-rect.top, 0), total);
        const ratio = scrolled / total;
        if (progress) progress.style.width = `${Math.min(100, ratio * 100)}%`;
        const index = Math.min(panels.length - 1, Math.floor(ratio * panels.length + 0.001));
        if (index !== active) setChapter(index);
    };

    if (reduceMotion) {
        setChapter(0);
        return;
    }

    setChapter(0);
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
}

function initMissionRail(root) {
    const rail = root.querySelector('[data-mission-rail]');
    const track = root.querySelector('[data-mission-track]');
    if (!rail || !track) return;

    let isDown = false;
    let startX = 0;
    let scrollLeft = 0;

    rail.addEventListener('pointerdown', (event) => {
        if (event.target.closest('a') && event.pointerType === 'mouse') {
            // allow click; still enable drag from padding areas
        }
        isDown = true;
        rail.classList.add('is-dragging');
        startX = event.clientX;
        scrollLeft = rail.scrollLeft;
        rail.setPointerCapture(event.pointerId);
    });

    rail.addEventListener('pointermove', (event) => {
        if (!isDown) return;
        const walk = (event.clientX - startX) * 1.15;
        rail.scrollLeft = scrollLeft - walk;
    });

    const endDrag = (event) => {
        if (!isDown) return;
        isDown = false;
        rail.classList.remove('is-dragging');
        try {
            rail.releasePointerCapture(event.pointerId);
        } catch {
            // ignore
        }
    };

    rail.addEventListener('pointerup', endDrag);
    rail.addEventListener('pointercancel', endDrag);
}

function initDomains(root) {
    const tabs = root.querySelectorAll('[data-domain-tab]');
    const panels = root.querySelectorAll('[data-domain-panel]');
    if (!tabs.length || !panels.length) return;

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            const id = tab.dataset.domainTab;
            tabs.forEach((t) => {
                const on = t === tab;
                t.classList.toggle('is-active', on);
                t.setAttribute('aria-selected', on ? 'true' : 'false');
            });
            panels.forEach((panel) => {
                const on = panel.dataset.domainPanel === id;
                panel.classList.toggle('is-active', on);
                panel.hidden = !on;
            });
        });
    });
}

function initTelemetry(root, reduceMotion) {
    const distanceEl = root.querySelector('[data-scroll-distance]');
    const speedEl = root.querySelector('[data-scroll-speed]');
    const noteEl = root.querySelector('[data-scroll-note]');
    if (!distanceEl || !speedEl) return;

    if (reduceMotion) {
        distanceEl.textContent = '0.0';
        speedEl.textContent = '0.00';
        if (noteEl) noteEl.textContent = 'Motion reduced — telemetry paused';
        return;
    }

    let lastY = window.scrollY;
    let lastT = performance.now();
    let velocity = 0;

    const notes = [
        { max: 0.08, text: 'Idle — systems standing by' },
        { max: 0.35, text: 'Cruising the knowledge surface' },
        { max: 0.8, text: 'Fast traverse — agent tracking' },
        { max: Infinity, text: 'High velocity — hold course' },
    ];

    const tick = (now) => {
        const y = window.scrollY;
        const dt = Math.max((now - lastT) / 1000, 0.016);
        const dy = Math.abs(y - lastY);
        const instant = (dy / window.innerHeight) / dt;
        velocity = velocity * 0.85 + instant * 0.15;
        lastY = y;
        lastT = now;

        const screens = y / Math.max(window.innerHeight, 1);
        distanceEl.textContent = screens.toFixed(1);
        speedEl.textContent = velocity.toFixed(2);
        if (noteEl) {
            noteEl.textContent = (notes.find((n) => velocity <= n.max) || notes.at(-1)).text;
        }
        requestAnimationFrame(tick);
    };

    requestAnimationFrame(tick);
}
