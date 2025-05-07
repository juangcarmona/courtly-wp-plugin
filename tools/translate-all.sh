#!/bin/bash
set -e

API_KEY="$1"
if [ -z "$API_KEY" ]; then
  echo "❌ Please provide your Google Translate API key."
  echo "Usage: ./translate-all.sh YOUR_API_KEY"
  exit 1
fi

PO_DIR="../src/Languages/"  # or change if running from outside Languages/
LANGS=("da_DK" "de_DE" "en_GB" "es_ES" "fi_FI" "fr_FR" "it_IT" "nl_NL" "pt_PT" "sv_SE")

for LANG in "${LANGS[@]}"; do
  PO_FILE="${PO_DIR}courtly-${LANG}.po"
  LOCALE="${LANG%%_*}"  # Get "es" from "es_ES"

  if [ -f "$PO_FILE" ]; then
    echo "🌍 Translating $PO_FILE to $LOCALE..."
    python translate-po.py "$PO_FILE" --lang "$LOCALE" --key "$API_KEY"
  else
    echo "⚠️  $PO_FILE not found, skipping."
  fi
done

echo "✅ All translations processed."
