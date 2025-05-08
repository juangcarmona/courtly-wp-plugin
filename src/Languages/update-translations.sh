#!/bin/bash
set -e

POT_FILE="courtly.pot"
LANGUAGES="da_DK de_DE en_GB es_ES fi_FI fr_FR it_IT nl_NL pt_PT sv_SE"

echo "🔄 Updating translations from $POT_FILE"

for lang in $LANGUAGES; do
  PO="courtly-${lang}.po"
  MO="courtly-${lang}.mo"

  if [ -f "$PO" ]; then
    echo "🧹 Cleaning duplicates in $PO..."
    msguniq --use-first -o "$PO" "$PO"

    echo "📝 Merging $PO with $POT_FILE..."
    msgmerge --update "$PO" "$POT_FILE"

    echo "🔧 Compiling $MO..."
    msgfmt "$PO" -o "$MO"
  else
    echo "⚠️  $PO not found, skipping."
  fi
done

echo "✅ Done. All translations cleaned, updated, and compiled."
