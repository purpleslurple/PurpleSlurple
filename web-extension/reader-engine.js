(() => {
  if (window.PurpleSlurpleReader) return;

  const DROP = [
    'script', 'style', 'noscript', 'iframe', 'svg', 'canvas', 'form', 'button',
    'input', 'select', 'textarea', 'nav', 'aside', 'footer', 'header', '.ad',
    '.ads', '.advertisement', '.promo', '.newsletter', '.subscribe', '.share',
    '.social', '.comments', '.comment', '[class*="cookie"]', '[id*="cookie"]',
    '[class*="modal"]', '[role="banner"]', '[role="navigation"]',
    '[role="complementary"]'
  ].join(',');

  const KEEP = [
    'a', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'blockquote', 'pre', 'code',
    'ul', 'ol', 'li', 'figure', 'figcaption', 'img', 'table', 'thead', 'tbody',
    'tfoot', 'tr', 'th', 'td', 'article', 'section', 'main', 'div', 'strong', 'b',
    'em', 'i', 'small', 'sub', 'sup', 'br', 'hr'
  ].join(',');

  const MARKABLE = 'p,h1,h2,h3,h4,h5,h6,li';

  const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, character => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
  }[character]));

  function absoluteUrl(value, base) {
    try { return new URL(value, base).href; } catch { return ''; }
  }

  function scoreNode(node) {
    const text = node.innerText || node.textContent || '';
    const length = text.trim().length;
    if (length < 240) return 0;
    const paragraphs = node.querySelectorAll('p').length;
    const headings = node.querySelectorAll('h1,h2,h3').length;
    const commas = (text.match(/,/g) || []).length;
    const linkedText = [...node.querySelectorAll('a')]
      .reduce((total, link) => total + (link.innerText || '').length, 0);
    const linkDensity = linkedText / Math.max(length, 1);
    const identity = `${node.className || ''} ${node.id || ''} ${node.tagName || ''}`;
    const bad = /comment|meta|footer|footnote|sidebar|related|share|social|nav|menu|promo|ad-|advert/i.test(identity) ? 0.45 : 1;
    const good = /article|story|post|entry|content|main|body/i.test(identity) ? 1.35 : 1;
    return (Math.sqrt(length) + paragraphs * 18 + headings * 8 + commas * 1.5)
      * (1 - Math.min(linkDensity, 0.85)) * bad * good;
  }

  function selectArticle(source) {
    const candidates = [...source.querySelectorAll('article,main,[role="main"],section,div')];
    return candidates
      .map(node => [scoreNode(node), node])
      .sort((left, right) => right[0] - left[0])[0]?.[1] || source.body;
  }

  function clean(root, sourceUrl) {
    root.querySelectorAll(DROP).forEach(node => node.remove());

    root.querySelectorAll('*').forEach(node => {
      [...node.attributes].forEach(attribute => {
        const allowed = ['href', 'src', 'alt', 'title', 'width', 'height', 'colspan', 'rowspan'];
        if (!allowed.includes(attribute.name.toLowerCase())) node.removeAttribute(attribute.name);
      });
      if (!node.matches(KEEP)) node.replaceWith(...node.childNodes);
    });

    root.querySelectorAll('div,section').forEach(node => {
      const text = (node.textContent || '').trim();
      const isEmptyWrapper = text.length < 40 && !node.querySelector('img,table,pre,blockquote');
      const isBoilerplate = /^(share|advertisement|related|more from|recommended)$/i.test(text);
      if (isEmptyWrapper || isBoilerplate) node.remove();
    });

    root.querySelectorAll('img').forEach(image => {
      const source = image.currentSrc || image.src || image.getAttribute('data-src')
        || image.getAttribute('data-original') || '';
      const resolved = absoluteUrl(source, sourceUrl);
      if (!resolved) { image.remove(); return; }
      image.src = resolved;
      image.removeAttribute('srcset');
      image.removeAttribute('sizes');
      image.loading = 'lazy';
    });

    root.querySelectorAll('a[href]').forEach(link => {
      const resolved = absoluteUrl(link.getAttribute('href'), sourceUrl);
      if (resolved) link.href = resolved;
      else link.removeAttribute('href');
    });
    return root;
  }

  function addPurpleNumbers(root, source) {
    let index = 1;
    root.querySelectorAll(MARKABLE).forEach(element => {
      const fragmentId = `purp${index}`;
      const link = source.createElement('a');
      link.href = `#${fragmentId}`;
      link.id = fragmentId;
      link.textContent = String(index);
      link.setAttribute('aria-label', `Paragraph ${index}`);
      const marker = source.createElement('span');
      marker.className = 'purpleslurple';
      marker.append('(', link, ') ');
      element.insertBefore(marker, element.firstChild);
      index += 1;
    });
  }

  const READER_STYLE = `
    color-scheme: light dark;
    html { scroll-behavior: smooth; }
    body { margin: 0; background: #f7f4ed; color: #202124; font: 20px/1.7 Georgia, serif; }
    main { max-width: 760px; margin: 0 auto; padding: 44px 22px 100vh; }
    h1,h2,h3,h4,h5,h6 { font-family: system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif; line-height: 1.15; letter-spacing: 0; }
    h1 { font-size: clamp(2.1rem,6vw,4rem); margin: .2em 0 .15em; }
    h2 { font-size: 1.65rem; margin: 1.7em 0 .4em; }
    h3 { font-size: 1.3rem; margin: 1.4em 0 .35em; }
    .meta { color: #6b665e; font: 15px/1.45 system-ui,sans-serif; margin-bottom: 2.5rem; overflow-wrap: anywhere; }
    .meta a { color: inherit; }
    p,li { max-width: 70ch; }
    a { color: #1d5f8a; }
    .purpleslurple { font: 700 .72em/1 system-ui,sans-serif; color: #7c3aed; white-space: nowrap; }
    .purpleslurple a { color: #7c3aed; text-decoration: none; }
    .purpleslurple a:hover { text-decoration: underline; }
    :target { scroll-margin-top: 1.5rem; }
    :target::after { content: ""; }
    img { display: block; max-width: 100%; height: auto; margin: 1.4rem auto; }
    figure { margin: 2rem 0; }
    figcaption { color: #6b665e; font: 14px/1.4 system-ui,sans-serif; text-align: center; }
    blockquote { border-left: 4px solid #9a8f7c; margin: 1.5rem 0; padding-left: 1rem; color: #37332d; }
    pre { overflow: auto; padding: 1rem; border-radius: 6px; background: #24292f; color: #f6f8fa; font: 14px/1.5 ui-monospace,monospace; }
    code { font-family: ui-monospace,monospace; }
    table { border-collapse: collapse; width: 100%; font-size: 16px; }
    td,th { border: 1px solid #bbb; padding: .45rem; text-align: left; }
    @media (prefers-color-scheme: dark) {
      body { background: #17191b; color: #ece7dc; }
      .meta,figcaption { color: #aaa398; }
      a { color: #8ec7ff; }
      .purpleslurple,.purpleslurple a { color: #c4a5fd; }
      blockquote { border-color: #777; color: #ddd; }
    }
  `;

  function activate() {
    const source = document;
    const sourceUrl = location.href.split('#purp')[0];
    const pageTitle = source.title || '';
    const articleTitle = source.querySelector('article h1,main h1,h1')?.textContent?.trim() || pageTitle;
    const byline = source.querySelector('[rel="author"],.byline,.author,[class*="author"],[itemprop="author"]')?.textContent?.trim() || '';
    const article = clean(selectArticle(source).cloneNode(true), sourceUrl);
    addPurpleNumbers(article, source);

    const html = `<!doctype html><html data-purple-slurple-reader="true"><head>
      <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
      <title>${escapeHtml(articleTitle)}</title><style>${READER_STYLE}</style></head><body><main>
      <h1><span class="purpleslurple">(<a id="purp0" href="#purp0" aria-label="Title">0</a>) </span>${escapeHtml(articleTitle)}</h1>
      <div class="meta">${byline ? `${escapeHtml(byline)}<br>` : ''}<a href="${escapeHtml(sourceUrl)}">${escapeHtml(sourceUrl)}</a></div>
      ${article.innerHTML}</main></body></html>`;

    source.open();
    source.write(html);
    source.close();
    if (location.hash.startsWith('#purp')) location.hash = location.hash;
  }

  window.PurpleSlurpleReader = { activate, scoreNode, version: '0.1.1' };
})();
