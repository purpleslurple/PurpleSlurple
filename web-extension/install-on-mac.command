#!/bin/zsh
set -e

SOURCE_DIR="${0:A:h}"
PROJECT_DIR="$SOURCE_DIR/Safari Project"
XCODE_DEVELOPER_DIR="/Applications/Xcode.app/Contents/Developer"

if [[ -d "$XCODE_DEVELOPER_DIR" ]]; then
  export DEVELOPER_DIR="$XCODE_DEVELOPER_DIR"
fi

if ! command -v xcrun >/dev/null 2>&1 || ! xcrun --find safari-web-extension-converter >/dev/null 2>&1; then
  echo "The full Xcode installation (not only Command Line Tools) is required."
  echo
  echo "1. Install or finish installing Xcode from the Mac App Store."
  echo "2. Open Xcode once and allow it to install any requested components."
  echo "3. Run this installer again."
  echo
  echo "Active developer directory:"
  xcode-select -p 2>/dev/null || echo "Not configured"
  read -k 1 "?Press any key to close."
  exit 1
fi

mkdir -p "$PROJECT_DIR"
xcrun safari-web-extension-converter "$SOURCE_DIR" \
  --project-location "$PROJECT_DIR" \
  --app-name "PurpleSlurple Reader" \
  --bundle-identifier "com.purpleslurple.reader" \
  --swift \
  --copy-resources \
  --force

echo
echo "The Safari project is ready in: $PROJECT_DIR"
echo "In Xcode, choose My Mac and press Run. Then enable PurpleSlurple Reader in Safari Settings > Extensions."
read -k 1 "?Press any key to close."
