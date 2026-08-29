#!/usr/bin/env bash
# Package the current HEAD as a theme zip for manual upload.
set -e
SP="/c/Users/sayid/AppData/Local/Temp/claude/C--GitHub-moghadam-wp-theme/405e6b05-338a-405f-a65e-f8e406073918/scratchpad"
rm -rf "$SP/build"; mkdir -p "$SP/build"
git -C /c/GitHub/moghadam-wp-theme archive --format=tar --prefix=moghadam/ HEAD | tar -x -C "$SP/build"
rm -f "$SP/build/moghadam/.editorconfig" "$SP/build/moghadam/.gitattributes" "$SP/build/moghadam/.gitignore"
cd "$SP/build" && python -c "import shutil,os; shutil.make_archive('moghadam','zip','.','moghadam'); print('zip', os.path.getsize('moghadam.zip'), 'bytes')"
