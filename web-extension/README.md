# PurpleSlurple Reader 0.1.2

This is the desktop-first Safari MVP: press the toolbar button, then **Open Reader View**.
The current page is cleaned, restyled, and numbered with stable `#purpN` fragments. Clicking
`(26)`, for example, changes the article URL to end in `#purp26`.

Everything runs locally in the browser. The extension does not send pages, reading history,
or preferences anywhere.

## Install on a Mac

1. Install Apple's full Xcode application from the Mac App Store if it is not already installed.
   The smaller standalone **Command Line Tools** package does not include Safari's converter.
2. Open Xcode once and allow it to install any additional requested components.
3. Double-click `install-on-mac.command`. Its disposable Xcode project is generated in
   macOS's temporary directory, never inside this source folder.
4. In the Xcode window, select **My Mac** as the destination and press **Run**.
5. Open Safari > Settings > Extensions and enable **PurpleSlurple Reader**.
6. Put the extension in Safari's toolbar if Safari does not do so automatically.

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
