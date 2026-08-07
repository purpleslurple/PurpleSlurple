# PurpleSlurple Reader 0.1.2

This is the desktop-first Safari MVP: press the toolbar button, then **Open Reader View**.
The current page is cleaned, restyled, and numbered with stable `#purpN` fragments. Clicking
`(26)`, for example, changes the article URL to end in `#purp26`.

Everything runs locally in the browser. The extension does not send pages, reading history,
or preferences anywhere.

## Try it in Safari on a Mac

1. In Safari > Settings > Advanced, enable **Show features for web developers**.
2. In Safari > Settings > Developer, click **Add Temporary Extension…**.
3. Select this `web-extension` folder.
4. Enable **PurpleSlurple Reader** in Safari's Extensions settings.

Safari removes a temporary extension after 24 hours or when Safari quits. This route is
intended for development and testing.

## Build the containing Mac app

For a packaged extension, install Apple's full Xcode application, open it once, and then
double-click `install-on-mac.command`. The smaller standalone **Command Line Tools** package
does not include Safari's converter. The disposable Xcode project is generated in macOS's
temporary directory, never inside this source folder.

During early local development, Safari may ask you to enable its **Develop** menu and allow
unsigned extensions. Xcode and Safari will show the relevant prompt when needed.

## Current scope

- Manual conversion from the toolbar
- Reader extraction based on the July 2026 bookmarklet
- Purple numbers for headings, paragraphs, and list items
- Warm light theme and automatic dark theme
- Preserved links, tables, code blocks, and lazy-loaded images
- Original URL retained in Safari, so anchored links remain meaningful

To return to the original page, reload it. Automatic Reader mode, per-site preferences,
one-click paragraph copying, and the reading/thought trail belong to the next passes.

## Source layout

- `reader-engine.js` — browser-neutral extraction, cleanup, rendering, and numbering
- `activate-reader.js` — tiny activation/toggle entry point
- `popup.*` — Safari toolbar interface
- `manifest.json` — standard WebExtension manifest

The same source can later be packaged for Firefox and for iPhone/iPad Safari.

## Test

```sh
npm install
npm test
```
