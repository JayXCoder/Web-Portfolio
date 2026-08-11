const MIN_ZOOM = 0.25;
const MAX_ZOOM = 4;
const ZOOM_STEP = 0.25;

const ICON = {
    minus: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="M5 12h14"/></svg>',
    plus: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="M12 5v14M5 12h14"/></svg>',
    reset: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12a9 9 0 1 0 3-6.7M3 4v5h5"/></svg>',
    expand: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/></svg>',
    close: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="M6 6l12 12M18 6 6 18"/></svg>',
};

function clampZoom(value) {
    const stepped = Math.round(value / ZOOM_STEP) * ZOOM_STEP;
    return Math.min(MAX_ZOOM, Math.max(MIN_ZOOM, Number(stepped.toFixed(2))));
}

function formatZoom(zoom) {
    return `${Math.round(zoom * 100)}%`;
}

function btn(action, label, icon) {
    return `<button type="button" class="mermaid-viewer-btn" data-mermaid-action="${action}" aria-label="${label}" title="${label}">${icon}<span class="mermaid-viewer-btn-label">${label}</span></button>`;
}

function toolbarHtml({ expand = true, close = false } = {}) {
    return `
        <div class="mermaid-viewer-toolbar" role="toolbar" aria-label="Diagram controls">
            ${btn('zoom-out', 'Zoom out', ICON.minus)}
            <span class="mermaid-viewer-zoom" data-mermaid-zoom-label aria-live="polite">100%</span>
            ${btn('zoom-in', 'Zoom in', ICON.plus)}
            ${btn('reset', 'Reset zoom', ICON.reset)}
            ${expand ? btn('expand', 'Expand diagram', ICON.expand) : ''}
            ${close ? btn('close', 'Close', ICON.close) : ''}
        </div>
    `;
}

function measureSvg(svg) {
    const viewBox = svg.viewBox?.baseVal;
    let width = viewBox?.width || Number.parseFloat(svg.getAttribute('width')) || svg.clientWidth || 800;
    let height = viewBox?.height || Number.parseFloat(svg.getAttribute('height')) || svg.clientHeight || 400;

    if (!Number.isFinite(width) || width <= 0) width = 800;
    if (!Number.isFinite(height) || height <= 0) height = 400;

    return { width, height };
}

function createController(viewport, svg, labelEl, { onExpand, onClose } = {}) {
    const natural = measureSvg(svg);
    let zoom = 1;

    svg.removeAttribute('width');
    svg.removeAttribute('height');
    svg.style.maxWidth = 'none';
    svg.setAttribute('role', 'img');

    const apply = () => {
        svg.style.width = `${natural.width * zoom}px`;
        svg.style.height = `${natural.height * zoom}px`;
        if (labelEl) labelEl.textContent = formatZoom(zoom);
    };

    const setZoom = (next, { center = true } = {}) => {
        const prev = zoom;
        zoom = clampZoom(next);
        if (zoom === prev) {
            apply();
            return;
        }

        const cx = viewport.clientWidth / 2;
        const cy = viewport.clientHeight / 2;
        const contentX = (viewport.scrollLeft + cx) / prev;
        const contentY = (viewport.scrollTop + cy) / prev;

        apply();

        viewport.scrollLeft = contentX * zoom - cx;
        viewport.scrollTop = contentY * zoom - cy;
    };

    apply();

    return {
        zoomIn: () => setZoom(zoom + ZOOM_STEP),
        zoomOut: () => setZoom(zoom - ZOOM_STEP),
        reset: () => {
            zoom = 1;
            apply();
            viewport.scrollLeft = 0;
            viewport.scrollTop = 0;
        },
        setZoom,
        getZoom: () => zoom,
        expand: () => onExpand?.(),
        close: () => onClose?.(),
    };
}

function bindToolbar(root, getController) {
    root.addEventListener('click', (event) => {
        const el = event.target.closest('[data-mermaid-action]');
        if (!el || !root.contains(el)) return;

        const controller = typeof getController === 'function' ? getController() : getController;
        if (!controller) return;

        const action = el.dataset.mermaidAction;
        if (action === 'zoom-in') controller.zoomIn();
        if (action === 'zoom-out') controller.zoomOut();
        if (action === 'reset') controller.reset();
        if (action === 'expand') controller.expand();
        if (action === 'close') controller.close();
    });
}

function bindWheelZoom(viewport, getController) {
    viewport.addEventListener(
        'wheel',
        (event) => {
            if (!(event.ctrlKey || event.metaKey)) return;
            event.preventDefault();
            const controller = typeof getController === 'function' ? getController() : getController;
            if (!controller) return;
            const delta = event.deltaY < 0 ? ZOOM_STEP : -ZOOM_STEP;
            controller.setZoom(controller.getZoom() + delta);
        },
        { passive: false },
    );
}

function bindDragPan(viewport) {
    let dragging = false;
    let startX = 0;
    let startY = 0;
    let scrollLeft = 0;
    let scrollTop = 0;

    viewport.addEventListener('pointerdown', (event) => {
        if (event.button !== 0) return;
        if (event.target.closest('button, a, input')) return;
        dragging = true;
        viewport.classList.add('is-panning');
        startX = event.clientX;
        startY = event.clientY;
        scrollLeft = viewport.scrollLeft;
        scrollTop = viewport.scrollTop;
        viewport.setPointerCapture(event.pointerId);
    });

    viewport.addEventListener('pointermove', (event) => {
        if (!dragging) return;
        viewport.scrollLeft = scrollLeft - (event.clientX - startX);
        viewport.scrollTop = scrollTop - (event.clientY - startY);
    });

    const endDrag = (event) => {
        if (!dragging) return;
        dragging = false;
        viewport.classList.remove('is-panning');
        try {
            viewport.releasePointerCapture(event.pointerId);
        } catch {
            // ignore
        }
    };

    viewport.addEventListener('pointerup', endDrag);
    viewport.addEventListener('pointercancel', endDrag);
}

let sharedDialog = null;

function ensureDialog() {
    if (sharedDialog) return sharedDialog;

    const dialog = document.createElement('dialog');
    dialog.className = 'mermaid-viewer-dialog';
    dialog.innerHTML = `
        <div class="mermaid-viewer-dialog-shell">
            ${toolbarHtml({ expand: false, close: true })}
            <div class="mermaid-viewer-viewport mermaid-viewer-dialog-viewport" data-mermaid-dialog-viewport tabindex="0" aria-label="Expanded diagram canvas">
            </div>
            <p class="mermaid-viewer-hint">Drag to pan · Ctrl/⌘ + scroll to zoom · Esc to close · + / − / 0</p>
        </div>
    `;
    document.body.appendChild(dialog);

    let controller = null;
    let previousOverflow = '';

    const close = () => {
        if (dialog.open) dialog.close();
    };

    const getController = () => controller;

    bindToolbar(dialog, getController);
    const viewport = dialog.querySelector('[data-mermaid-dialog-viewport]');
    bindWheelZoom(viewport, getController);
    bindDragPan(viewport);

    dialog.addEventListener('close', () => {
        document.body.style.overflow = previousOverflow;
        viewport.replaceChildren();
        controller = null;
    });

    dialog.addEventListener('click', (event) => {
        if (event.target === dialog) close();
    });

    dialog.addEventListener('keydown', (event) => {
        if (!controller) return;
        if (event.key === '+' || event.key === '=') {
            event.preventDefault();
            controller.zoomIn();
        } else if (event.key === '-' || event.key === '_') {
            event.preventDefault();
            controller.zoomOut();
        } else if (event.key === '0') {
            event.preventDefault();
            controller.reset();
        }
    });

    sharedDialog = {
        open(sourceSvg) {
            const clone = sourceSvg.cloneNode(true);
            viewport.replaceChildren(clone);

            const label = dialog.querySelector('[data-mermaid-zoom-label]');
            controller = createController(viewport, clone, label, { onClose: close });

            previousOverflow = document.body.style.overflow;
            document.body.style.overflow = 'hidden';
            dialog.showModal();
            dialog.querySelector('[data-mermaid-action="close"]')?.focus();
        },
    };

    return sharedDialog;
}

function wrapMermaidNode(pre) {
    if (pre.dataset.mermaidViewer === 'true' || pre.closest('.mermaid-viewer')) return;

    const svg = pre.querySelector('svg');
    if (!svg) return;

    const figure = document.createElement('figure');
    figure.className = 'mermaid-viewer';
    figure.innerHTML = `
        ${toolbarHtml({ expand: true, close: false })}
        <div class="mermaid-viewer-viewport" data-mermaid-viewport tabindex="0" aria-label="Diagram canvas"></div>
        <figcaption class="mermaid-viewer-caption">Scroll or drag · Ctrl/⌘ + scroll to zoom</figcaption>
    `;

    const viewport = figure.querySelector('[data-mermaid-viewport]');
    const label = figure.querySelector('[data-mermaid-zoom-label]');
    if (!viewport || !label) return;

    pre.replaceWith(figure);
    viewport.appendChild(svg);

    const controller = createController(viewport, svg, label, {
        onExpand: () => ensureDialog().open(svg),
    });

    bindToolbar(figure, controller);
    bindWheelZoom(viewport, controller);
    bindDragPan(viewport);
}

export function enhanceMermaidViewers(root) {
    if (!root) return;
    root.querySelectorAll('pre.mermaid').forEach(wrapMermaidNode);
}
