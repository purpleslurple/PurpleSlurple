const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const { JSDOM } = require('jsdom');

const engine = fs.readFileSync(path.join(__dirname, '..', 'reader-engine.js'), 'utf8');
const prose = 'This is substantial article prose, with enough words and punctuation to resemble a real news paragraph. '.repeat(8);
const dom = new JSDOM(`<!doctype html><html><head><title>Fallback title</title></head><body>
  <nav>Navigation that should disappear</nav>
  <article class="story-content">
    <h1>Test article</h1>
    <p>${prose}</p>
    <p><a href="/next">A relative link</a> continues the article.</p>
    <img src="/photo.jpg" alt="Test photograph">
  </article>
  <aside>Related stories</aside>
</body></html>`, {
  url: 'https://example.com/news/story',
  runScripts: 'dangerously'
});

dom.window.eval(engine);
assert.equal(dom.window.PurpleSlurpleReader.version, '0.1.2');
dom.window.PurpleSlurpleReader.activate();

const { document } = dom.window;
assert.equal(document.documentElement.dataset.purpleSlurpleReader, 'true');
assert.equal(document.querySelector('#purp0')?.textContent, '0');
assert.equal(document.querySelector('#purp1')?.textContent, '1');
assert.ok(document.querySelectorAll('.purpleslurple').length >= 3);
assert.equal(document.querySelector('a[href="https://example.com/next"]')?.textContent, 'A relative link');
assert.equal(document.querySelector('img')?.src, 'https://example.com/photo.jpg');
assert.equal(document.querySelector('nav'), null);
assert.equal(document.querySelector('aside'), null);

console.log('reader-engine functional test OK');
