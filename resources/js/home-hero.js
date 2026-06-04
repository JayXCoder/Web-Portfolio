/**
 * Home hero: cycling neon words, sparkles, and multi-language terminal typing.
 */

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

    const codeSnippets = [
        {
            language: 'Python',
            text: 'print("Hello! Welcome to my portfolio")\nprint("Full-Stack · AI/ML · IoT")\n\ndef explore():\n    return "View my projects →"',
            color: '#3776ab',
        },
        {
            language: 'PHP',
            text: '<?php\n\necho "JayXCoder — Laravel portfolio";\necho "\\nBuilt with Ollama + Docker";',
            color: '#777bb4',
        },
        {
            language: 'JavaScript',
            text: 'const stack = ["Laravel", "React", "Python"];\nconsole.log(`Building with ${stack.join(", ")}`);',
            color: '#f7df1e',
        },
        {
            language: 'C++',
            text: '#include <iostream>\nint main() {\n  std::cout << "Engineer · Builder\\n";\n  return 0;\n}',
            color: '#00599c',
        },
    ];

    let currentIndex = 0;
    let isTyping = false;

    function typeCode(snippet, callback) {
        if (isTyping) return;
        isTyping = true;
        languageIndicator.textContent = snippet.language;
        languageIndicator.style.borderColor = snippet.color;
        typedCodeElement.textContent = '';

        let index = 0;
        const fullText = snippet.text;

        function typeChar() {
            if (index < fullText.length) {
                typedCodeElement.textContent += fullText[index];
                index += 1;
                setTimeout(typeChar, Math.random() * 28 + 18);
            } else {
                isTyping = false;
                setTimeout(callback, 2200);
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

    setTimeout(cycleSnippets, 800);
}
