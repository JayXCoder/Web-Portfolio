/**
 * Home hero: cycling neon words, sparkles, and multi-language terminal typing.
 */

import { HERO_CODE_SNIPPETS } from './hero-snippets';
import { highlightMonokai } from './hero-monokai';

export function initHomeHero() {
    initHeroWordCycle();
    initHeroTerminal();
}

function initHeroWordCycle() {
    const animatedText = document.getElementById('animatedText');
    const animatedFavicon = document.getElementById('animatedFavicon');
    const shapeCircle = document.getElementById('shapeCircle');
    const shapeTriangle = document.getElementById('shapeTriangle');
    const shapeSquare = document.getElementById('shapeSquare');
    const container = document.querySelector('.animated-text-container');

    if (!animatedText || !container) return;

    const words = [
        'Code', 'Design', 'Innovate', 'Create', 'Build', 'Develop',
        'Engineer', 'Solve', 'Optimize', 'Automate', 'Secure', 'Deploy',
    ];

    const animations = [
        { enter: 'zoom-in', exit: 'zoom-out' },
        { enter: 'dash-left-in', exit: 'dash-right-out' },
        { enter: 'spin-in', exit: 'flip-out' },
        { enter: 'bounce-in', exit: 'slide-up-out' },
    ];

    let currentIndex = 0;
    let animationIndex = 0;

    const shapes = [shapeCircle, shapeTriangle, shapeSquare].filter(Boolean);
    const favicon = animatedFavicon;

    function animateText() {
        const currentAnimation = animations[animationIndex];
        animatedText.className = 'animated-text';
        shapes.forEach((s) => s?.classList.remove('show'));
        favicon?.classList.remove('show');
        animatedText.classList.add(currentAnimation.exit);

        setTimeout(() => {
            animatedText.textContent = words[currentIndex];
            animatedText.classList.remove(currentAnimation.exit);
            animatedText.classList.add(currentAnimation.enter);

            setTimeout(() => favicon?.classList.add('show'), 100);
            shapes.forEach((s, i) => {
                setTimeout(() => s?.classList.add('show'), 200 + i * 100);
            });

            currentIndex = (currentIndex + 1) % words.length;
            animationIndex = (animationIndex + 1) % animations.length;
        }, 300);
    }

    function createSparkle() {
        const sparkle = document.createElement('div');
        sparkle.className = 'hero-sparkle';
        sparkle.style.left = `${Math.random() * 100}%`;
        sparkle.style.top = `${Math.random() * 100}%`;
        container.appendChild(sparkle);
        setTimeout(() => sparkle.remove(), 1500);
    }

    animatedText.classList.add('zoom-in');
    setTimeout(() => favicon?.classList.add('show'), 200);
    shapes.forEach((s, i) => setTimeout(() => s?.classList.add('show'), 300 + i * 100));

    setInterval(animateText, 1200);
    setInterval(createSparkle, 2500);
}

function initHeroTerminal() {
    const typedCodeElement = document.getElementById('typedCode');
    const languageIndicator = document.getElementById('languageIndicator');

    if (!typedCodeElement || !languageIndicator) return;

    const codeSnippets = HERO_CODE_SNIPPETS;

    let currentIndex = 0;
    let isTyping = false;

    function renderCode(plain, language) {
        typedCodeElement.innerHTML = highlightMonokai(plain, language);
    }

    function typeCode(snippet, callback) {
        if (isTyping) return;
        isTyping = true;
        languageIndicator.textContent = snippet.language;
        languageIndicator.style.borderColor = '#66d9ef';

        let index = 0;
        const fullText = snippet.text;
        renderCode('', snippet.language);

        function typeChar() {
            if (index < fullText.length) {
                const slice = fullText.slice(0, index + 1);
                renderCode(slice, snippet.language);
                index += 1;
                setTimeout(typeChar, Math.random() * 16 + 10);
            } else {
                isTyping = false;
                setTimeout(callback, 2600);
            }
        }
        typeChar();
    }

    function cycleSnippets() {
        typeCode(codeSnippets[currentIndex], () => {
            currentIndex = (currentIndex + 1) % codeSnippets.length;
            cycleSnippets();
        });
    }

    setTimeout(cycleSnippets, 600);
}
