/**
 * Monokai-inspired syntax highlighting for the hero terminal.
 */

const KEYWORDS = new Set([
    'async', 'await', 'def', 'return', 'for', 'in', 'if', 'else', 'elif', 'import', 'from', 'const', 'let', 'var',
    'function', 'new', 'class', 'extends', 'type', 'interface', 'enum', 'public', 'private', 'static', 'void',
    'int', 'float', 'double', 'bool', 'true', 'false', 'null', 'nil', 'fn', 'mut', 'let', 'struct', 'impl',
    'package', 'func', 'suspend', 'fun', 'val', 'record', 'case', 'switch', 'break', 'continue', 'using',
    'namespace', 'include', 'define', 'global', 'section', 'mov', 'push', 'pop', 'call', 'ret', 'SELECT',
    'FROM', 'WHERE', 'GROUP', 'BY', 'ORDER', 'LIMIT', 'AS', 'INSERT', 'INTO', 'VALUES', 'set', 'echo',
]);

function escapeHtml(text) {
    return text
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function span(type, text) {
    return `<span class="mk-${type}">${escapeHtml(text)}</span>`;
}

/**
 * @param {string} code
 * @param {string} language
 */
export function highlightMonokai(code, language) {
    const lines = code.split('\n');
    const lang = language.toLowerCase();

    return lines
        .map((line) => highlightLine(line, lang))
        .join('\n');
}

function highlightLine(line, lang) {
    if (line.trim() === '') {
        return '';
    }

    if (/^\s*(\/\/|#|;)/.test(line) || /^\s*\/\*/.test(line)) {
        return span('comment', line);
    }

    if (lang === 'html') {
        return highlightHtml(line);
    }

    if (lang === 'sql') {
        return highlightSql(line);
    }

    if (lang === 'assembly') {
        return highlightAssembly(line);
    }

    return highlightGeneric(line);
}

function highlightHtml(line) {
    let out = '';
    const tagRe = /<\/?[\w-]+|[^<>]+|>/g;
    let m;
    while ((m = tagRe.exec(line)) !== null) {
        const t = m[0];
        if (t.startsWith('</') || (t.startsWith('<') && !t.startsWith('<!--'))) {
            out += span('tag', t);
        } else if (t.includes('=')) {
            out += highlightGeneric(t);
        } else {
            out += span('plain', t);
        }
    }
    return out || span('plain', line);
}

function highlightSql(line) {
    return line
        .split(/(\s+|[(),;*]|'[^']*')/g)
        .filter((p) => p !== '')
        .map((part) => {
            if (/^'[^']*'$/.test(part)) {
                return span('string', part);
            }
            if (/^[A-Z_]+$/.test(part)) {
                return span('keyword', part);
            }
            if (/^\d+$/.test(part)) {
                return span('number', part);
            }
            return span('plain', part);
        })
        .join('');
}

function highlightAssembly(line) {
    if (/^\s*;/.test(line)) {
        return span('comment', line);
    }
    return line
        .split(/(\s+|,)/g)
        .filter((p) => p !== '')
        .map((part) => {
            if (/^(mov|int|push|pop|call|ret|section|global|_start)$/i.test(part)) {
                return span('keyword', part);
            }
            if (/^(rax|rdi|0x[0-9a-f]+|\d+)$/i.test(part)) {
                return span('number', part);
            }
            return span('plain', part);
        })
        .join('');
}

function highlightGeneric(line) {
    const tokens = [];
    const re =
        /(\/\/.*$|#.*$|"(?:\\.|[^"\\])*"|'(?:\\.|[^'\\])*'|`(?:\\.|[^`\\])*`|\b\d+\.?\d*\b|[a-zA-Z_$][\w$]*|::|->|=>|[^\s\w]+|\s+)/g;
    let match;

    while ((match = re.exec(line)) !== null) {
        const t = match[0];
        if (/^(\/\/|#)/.test(t)) {
            tokens.push(span('comment', t));
            break;
        }
        if (/^["'`]/.test(t)) {
            tokens.push(span('string', t));
        } else if (/^\d/.test(t)) {
            tokens.push(span('number', t));
        } else if (KEYWORDS.has(t) || KEYWORDS.has(t.toUpperCase())) {
            tokens.push(span('keyword', t));
        } else if (/^[A-Z][\w]*$/.test(t)) {
            tokens.push(span('type', t));
        } else if (/^[a-z_$][\w$]*(?=\s*\()/.test(t)) {
            tokens.push(span('function', t));
        } else {
            tokens.push(span('plain', t));
        }
    }

    return tokens.join('') || span('plain', line);
}
