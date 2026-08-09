/**
 * Immersive home scroll: HUD particles, counters, chapters, rail, telemetry.
 */

export function initHomeScroll() {
    const root = document.querySelector('[data-home-scroll]');
    if (!root) return;

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    initHeroCanvas(root, reduceMotion);
    initParallax(root, reduceMotion);
    initCounters(root, reduceMotion);
    initSignals(root);
    initChapters(root, reduceMotion);
    initMissionRail(root);
    initDomains(root);
    initTelemetry(root, reduceMotion);
}

function initHeroCanvas(root, reduceMotion) {
    const canvas = root.querySelector('[data-hero-canvas]');
    if (!canvas || reduceMotion) return;

    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    const particles = [];
    const resize = () => {
        const rect = canvas.parentElement?.getBoundingClientRect();
        const dpr = Math.min(window.devicePixelRatio || 1, 2);
        const w = Math.max(1, Math.floor(rect?.width || window.innerWidth));
        const h = Math.max(1, Math.floor(rect?.height || window.innerHeight));
        canvas.width = w * dpr;
        canvas.height = h * dpr;
        canvas.style.width = `${w}px`;
        canvas.style.height = `${h}px`;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        if (particles.length === 0) {
            const count = Math.min(70, Math.floor((w * h) / 18000));
            for (let i = 0; i < count; i++) {
                particles.push({
                    x: Math.random() * w,
                    y: Math.random() * h,
                    r: Math.random() * 1.6 + 0.4,
                    vx: (Math.random() - 0.5) * 0.25,
                    vy: (Math.random() - 0.5) * 0.25,
                    a: Math.random() * 0.45 + 0.15,
                });
            }
        }
    };

    let frame = 0;
    const draw = () => {
        const w = canvas.clientWidth;
        const h = canvas.clientHeight;
        ctx.clearRect(0, 0, w, h);
        for (const p of particles) {
            p.x += p.vx;
            p.y += p.vy;
            if (p.x < 0) p.x = w;
            if (p.x > w) p.x = 0;
            if (p.y < 0) p.y = h;
            if (p.y > h) p.y = 0;
            ctx.beginPath();
            ctx.fillStyle = `rgba(192,132,252,${p.a})`;
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fill();
        }
        // constellation links
        for (let i = 0; i < particles.length; i++) {
            for (let j = i + 1; j < particles.length; j++) {
                const a = particles[i];
                const b = particles[j];
                const dx = a.x - b.x;
                const dy = a.y - b.y;
                const d = Math.hypot(dx, dy);
                if (d < 110) {
                    ctx.strokeStyle = `rgba(168,85,247,${(1 - d / 110) * 0.18})`;
                    ctx.beginPath();
                    ctx.moveTo(a.x, a.y);
                    ctx.lineTo(b.x, b.y);
                    ctx.stroke();
                }
            }
        }
        frame = requestAnimationFrame(draw);
    };

    resize();
    draw();
    window.addEventListener('resize', resize, { passive: true });
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) cancelAnimationFrame(frame);
        else frame = requestAnimationFrame(draw);
    });
}

function initParallax(root, reduceMotion) {
    if (reduceMotion) return;
    const hero = root.querySelector('[data-home-hero]');
    const nodes = root.querySelectorAll('[data-parallax]');
    if (!hero || !nodes.length) return;

    hero.addEventListener('pointermove', (event) => {
        const rect = hero.getBoundingClientRect();
        const x = (event.clientX - rect.left) / rect.width - 0.5;
        const y = (event.clientY - rect.top) / rect.height - 0.5;
        nodes.forEach((node) => {
            const depth = Number(node.dataset.parallax || 0.08);
            node.style.transform = `translate3d(${x * depth * -40}px, ${y * depth * -30}px, 0)`;
        });
    }, { passive: true });
}

function initCounters(root, reduceMotion) {
    const block = root.querySelector('[data-counters]');
    if (!block) return;
    const nums = block.querySelectorAll('[data-count]');
    let started = false;

    const run = () => {
        if (started) return;
        started = true;
        nums.forEach((el) => {
            const target = Number(el.dataset.count || 0);
            if (reduceMotion) {
                el.textContent = String(target);
                return;
            }
            const duration = 900;
            const start = performance.now();
            const tick = (now) => {
                const t = Math.min(1, (now - start) / duration);
                const eased = 1 - Math.pow(1 - t, 3);
                el.textContent = String(Math.round(target * eased));
                if (t < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
        });
    };

    const observer = new IntersectionObserver((entries) => {
        if (entries.some((e) => e.isIntersecting)) {
            run();
            observer.disconnect();
        }
    }, { threshold: 0.4 });
    observer.observe(block);
}

function initSignals(root) {
    const signals = root.querySelectorAll('[data-signal]');
    if (!signals.length) return;

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) entry.target.classList.add('is-lit');
                else if (entry.boundingClientRect.top > 0) entry.target.classList.remove('is-lit');
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
    const stage = root.querySelector('[data-chapter-stage]');
    const stageLabel = root.querySelector('[data-stage-label]');
    const stageTags = root.querySelector('[data-stage-tags]');
    if (!section || !spacer || !panels.length) return;

    const labels = ['01 / BUILD', '02 / SECURE', '03 / DEPLOY'];
    const tagSets = [
        ['Laravel', 'React', 'Ollama', 'Queues'],
        ['CSRF', 'HTTPS', 'RBAC', 'Pentest'],
        ['Docker', 'IoT', 'FPGA', 'Portainer'],
    ];
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
        if (stage) stage.dataset.stage = String(active);
        if (stageLabel) stageLabel.textContent = labels[active] ?? labels[0];
        if (stageTags) {
            stageTags.innerHTML = (tagSets[active] || []).map((t) => `<li>${t}</li>`).join('');
        }
        if (progress) progress.style.width = `${((active + 1) / panels.length) * 100}%`;

        if (syncScroll && !reduceMotion) {
            const rect = section.getBoundingClientRect();
            const start = window.scrollY + rect.top;
            const travel = Math.max(section.offsetHeight - window.innerHeight, 1);
            const target = start + (active / Math.max(panels.length - 1, 1)) * travel;
            window.scrollTo({ top: target, behavior: 'smooth' });
        }
    };

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => setChapter(Number(tab.dataset.chapterTab), { syncScroll: true }));
    });

    if (reduceMotion) {
        setChapter(0);
        return;
    }

    setChapter(0);
    const onScroll = () => {
        const rect = section.getBoundingClientRect();
        const total = Math.max(section.offsetHeight - window.innerHeight, 1);
        const scrolled = Math.min(Math.max(-rect.top, 0), total);
        const ratio = scrolled / total;
        if (progress) progress.style.width = `${Math.min(100, ratio * 100)}%`;
        const index = Math.min(panels.length - 1, Math.floor(ratio * panels.length + 0.001));
        if (index !== active) setChapter(index);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
}

function initMissionRail(root) {
    const rail = root.querySelector('[data-mission-rail]');
    if (!rail) return;

    let isDown = false;
    let startX = 0;
    let scrollLeft = 0;
    let moved = false;

    rail.addEventListener('pointerdown', (event) => {
        isDown = true;
        moved = false;
        rail.classList.add('is-dragging');
        startX = event.clientX;
        scrollLeft = rail.scrollLeft;
        rail.setPointerCapture(event.pointerId);
    });

    rail.addEventListener('pointermove', (event) => {
        if (!isDown) return;
        const walk = event.clientX - startX;
        if (Math.abs(walk) > 6) moved = true;
        rail.scrollLeft = scrollLeft - walk * 1.15;
    });

    const endDrag = (event) => {
        if (!isDown) return;
        isDown = false;
        rail.classList.remove('is-dragging');
        try { rail.releasePointerCapture(event.pointerId); } catch { /* ignore */ }
    };

    rail.addEventListener('pointerup', endDrag);
    rail.addEventListener('pointercancel', endDrag);
    rail.addEventListener('click', (event) => {
        if (moved && event.target.closest('a')) {
            event.preventDefault();
        }
    }, true);
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
        const instant = (Math.abs(y - lastY) / window.innerHeight) / dt;
        velocity = velocity * 0.85 + instant * 0.15;
        lastY = y;
        lastT = now;
        distanceEl.textContent = (y / Math.max(window.innerHeight, 1)).toFixed(1);
        speedEl.textContent = velocity.toFixed(2);
        if (noteEl) noteEl.textContent = (notes.find((n) => velocity <= n.max) || notes.at(-1)).text;
        requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
}
