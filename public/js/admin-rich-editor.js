(() => {
    'use strict';

    const PRODUCT_ALLOWED = new Set(['P','BR','H2','H3','H4','STRONG','B','EM','I','U','UL','OL','LI','BLOCKQUOTE','SPAN']);
    const FULL_ALLOWED = new Set(['P','BR','H1','H2','H3','H4','STRONG','B','EM','I','U','UL','OL','LI','BLOCKQUOTE','PRE','CODE','A','IMG','SPAN','DIV']);
    const emojiPattern = /[\u{1F000}-\u{1FAFF}\u{1FC00}-\u{1FFFF}\u{2600}-\u{27BF}\u{FE0F}\u{1F3FB}-\u{1F3FF}]/gu;

    const toFa = (n) => String(n).replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[d]);

    function plainText(html) {
        const box = document.createElement('div');
        box.innerHTML = html || '';
        return box.textContent || box.innerText || '';
    }

    function sanitize(root, mode) {
        const allowed = mode === 'product' ? PRODUCT_ALLOWED : FULL_ALLOWED;
        const forbidden = mode === 'product'
            ? ['SCRIPT','STYLE','IFRAME','VIDEO','AUDIO','SOURCE','OBJECT','EMBED','SVG','CANVAS','FORM','INPUT','BUTTON','A','IMG','PRE','CODE']
            : ['SCRIPT','STYLE','IFRAME','OBJECT','EMBED','FORM','INPUT','BUTTON'];

        [...root.querySelectorAll('*')].reverse().forEach(el => {
            if (forbidden.includes(el.tagName) || !allowed.has(el.tagName)) {
                const text = document.createTextNode(el.textContent || '');
                el.replaceWith(text);
                return;
            }
            [...el.attributes].forEach(attr => {
                const name = attr.name.toLowerCase();
                const value = attr.value || '';
                if (name.startsWith('on') || name === 'style' || name === 'class' || name === 'id' || name === 'contenteditable') {
                    el.removeAttribute(attr.name);
                }
                if ((name === 'href' || name === 'src') && /^(javascript:|data:|vbscript:)/i.test(value)) {
                    el.removeAttribute(attr.name);
                }
            });
            if (el.tagName === 'A') {
                el.setAttribute('target', '_blank');
                el.setAttribute('rel', 'noopener noreferrer');
            }
            if (el.tagName === 'IMG') {
                el.removeAttribute('width');
                el.removeAttribute('height');
                el.removeAttribute('srcset');
                el.setAttribute('loading', 'lazy');
            }
        });

        if (mode === 'product') {
            root.innerHTML = root.innerHTML.replace(emojiPattern, '');
            root.querySelectorAll('[style]').forEach(el => el.removeAttribute('style'));
        }
        return root;
    }

    function exec(editor, command, value) {
        editor.focus();
        if (command === 'format') {
            document.execCommand('formatBlock', false, value);
        } else if (command === 'foreColor' || command === 'backColor') {
            document.execCommand(command, false, value);
        } else if (command === 'blockquote') {
            document.execCommand('formatBlock', false, 'blockquote');
        } else if (command === 'undo' || command === 'redo' || command === 'bold' || command === 'italic' || command === 'underline' || command === 'insertUnorderedList' || command === 'insertOrderedList') {
            document.execCommand(command, false, null);
        }
    }

    function openPrompt(kind) {
        if (kind === 'link') {
            const url = window.prompt('نشانی پیوند را وارد کنید:');
            if (!url) return null;
            return { url: /^https?:\/\//i.test(url) ? url : `https://${url}` };
        }
        if (kind === 'image') {
            const url = window.prompt('نشانی تصویر را وارد کنید:');
            if (!url) return null;
            return { url };
        }
        if (kind === 'code') {
            const code = window.prompt('کد را وارد کنید:');
            if (code === null) return null;
            return { code };
        }
        return null;
    }

    function setup(container) {
        const mode = container.dataset.mode || 'product';
        const field = container.querySelector('[data-ds-editor-value]');
        const editor = container.querySelector('[data-ds-editor-content]');
        const saveState = container.querySelector('[data-ds-save-state]');
        const words = container.querySelector('[data-ds-words]');
        const chars = container.querySelector('[data-ds-chars]');
        const key = `digitalshop.rich-editor.${location.pathname}.${container.dataset.name}`;
        let timer = null;
        let savedTimer = null;

        const setState = (text, cls = '') => {
            saveState.textContent = text;
            saveState.className = `ds-save-state ${cls}`;
        };

        const sync = (autosave = true) => {
            sanitize(editor, mode);
            field.value = editor.innerHTML;
            const text = plainText(editor.innerHTML).replace(/\s+/g, ' ').trim();
            words.textContent = toFa(text ? text.split(' ').length : 0);
            chars.textContent = toFa(text.length);
            if (autosave) {
                setState('در حال ذخیره…', 'saving');
                clearTimeout(timer);
                timer = setTimeout(() => {
                    try {
                        localStorage.setItem(key, JSON.stringify({ html: field.value, time: Date.now() }));
                        setState('ذخیره خودکار شد', 'saved');
                    } catch (_) {
                        setState('ذخیره محلی در دسترس نیست', 'error');
                    }
                }, 650);
            }
        };

        const initial = field.value || '';
        editor.innerHTML = initial;
        sanitize(editor, mode);

        try {
            const cached = JSON.parse(localStorage.getItem(key) || 'null');
            if (cached && cached.html && cached.html !== field.value && Date.now() - Number(cached.time || 0) < 1000 * 60 * 60 * 72) {
                editor.innerHTML = cached.html;
                sanitize(editor, mode);
                setState('پیش‌نویس بازیابی شد', 'saved');
            } else {
                setState('ذخیره خودکار فعال است');
            }
        } catch (_) {
            setState('ذخیره خودکار فعال است');
        }
        sync(false);

        container.querySelectorAll('[data-command]').forEach(button => {
            button.addEventListener('mousedown', e => e.preventDefault());
            button.addEventListener('click', () => {
                const command = button.dataset.command;
                const value = button.dataset.value;
                if (command === 'fullscreen') {
                    container.classList.toggle('is-fullscreen');
                    document.body.style.overflow = container.classList.contains('is-fullscreen') ? 'hidden' : '';
                    editor.focus();
                    return;
                }
                if (command === 'link' && mode === 'full') {
                    const data = openPrompt('link');
                    if (data) { editor.focus(); document.execCommand('createLink', false, data.url); sync(); }
                    return;
                }
                if (command === 'image' && mode === 'full') {
                    const data = openPrompt('image');
                    if (data) { editor.focus(); document.execCommand('insertHTML', false, `<img src="${data.url.replace(/"/g, '&quot;')}" alt="">`); sync(); }
                    return;
                }
                if (command === 'code' && mode === 'full') {
                    const data = openPrompt('code');
                    if (data) { editor.focus(); document.execCommand('insertHTML', false, `<pre><code>${data.code.replace(/[&<>]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;'}[c]))}</code></pre>`); sync(); }
                    return;
                }
                if (command === 'removeFormat') {
                    exec(editor, 'removeFormat');
                    sync();
                    return;
                }
                exec(editor, command, value);
                sync();
            });
        });

        container.querySelectorAll('[data-color-menu]').forEach(button => {
            button.addEventListener('mousedown', e => e.preventDefault());
            button.addEventListener('click', () => {
                const menu = container.querySelector(`[data-menu="${button.dataset.colorMenu}"]`);
                container.querySelectorAll('.ds-color-menu').forEach(m => { if (m !== menu) m.hidden = true; });
                menu.hidden = !menu.hidden;
            });
        });

        container.querySelectorAll('.ds-tool-select').forEach(button => {
            button.addEventListener('click', () => {
                const menu = button.parentElement.querySelector('.ds-format-menu');
                menu.hidden = !menu.hidden;
            });
        });

        document.addEventListener('click', e => {
            if (!container.contains(e.target)) return;
            container.querySelectorAll('.ds-format-menu,.ds-color-menu').forEach(menu => {
                if (!menu.parentElement.contains(e.target)) menu.hidden = true;
            });
        });

        editor.addEventListener('input', () => sync(true));
        editor.addEventListener('blur', () => sync(true));

        editor.addEventListener('paste', e => {
            e.preventDefault();
            const html = e.clipboardData.getData('text/html');
            const text = e.clipboardData.getData('text/plain');
            const box = document.createElement('div');
            box.innerHTML = html || text;
            sanitize(box, mode);
            if (mode === 'product') {
                box.innerHTML = box.innerHTML.replace(emojiPattern, '');
            }
            document.execCommand('insertHTML', false, box.innerHTML || text);
            sync(true);
        });

        editor.addEventListener('drop', e => {
            if (mode === 'product' && e.dataTransfer && [...e.dataTransfer.types].some(t => t === 'Files' || t === 'text/uri-list')) {
                e.preventDefault();
                setState('افزودن فایل، تصویر یا لینک در توضیحات محصول مجاز نیست', 'error');
            }
        });

        editor.addEventListener('keydown', e => {
            const mod = e.ctrlKey || e.metaKey;
            if (mod && e.key.toLowerCase() === 'k' && mode === 'product') { e.preventDefault(); setState('لینک در توضیحات محصول مجاز نیست', 'error'); }
            if (mod && e.key.toLowerCase() === 'u') { e.preventDefault(); exec(editor, 'underline'); sync(); }
            if (mod && e.key.toLowerCase() === 'b') { e.preventDefault(); exec(editor, 'bold'); sync(); }
            if (mod && e.key.toLowerCase() === 'i') { e.preventDefault(); exec(editor, 'italic'); sync(); }
            if (mod && e.key.toLowerCase() === 'z') { e.preventDefault(); exec(editor, e.shiftKey ? 'redo' : 'undo'); setTimeout(() => sync(false), 0); }
            if (mod && e.key.toLowerCase() === 'y') { e.preventDefault(); exec(editor, 'redo'); setTimeout(() => sync(false), 0); }
            if (e.key === 'Escape' && container.classList.contains('is-fullscreen')) {
                container.classList.remove('is-fullscreen');
                document.body.style.overflow = '';
            }
        });

        const form = container.closest('form');
        if (form) {
            form.addEventListener('submit', () => {
                sync(false);
                try { localStorage.removeItem(key); } catch (_) {}
                setState('در حال ثبت…', 'saving');
            });
        }
    }

    const boot = () => document.querySelectorAll('[data-ds-editor]').forEach(setup);
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
    else boot();
})();
