#!/usr/bin/env python3
"""
Google Translate-based .po translator for developers.
Overwrites untranslated entries in a given .po file using Google Translate API.
"""

import polib
import requests
import time

def translate_text(text, target_lang, api_key):
    url = "https://translation.googleapis.com/language/translate/v2"
    params = {
        "q": text,
        "target": target_lang,
        "format": "text",
        "key": api_key
    }
    response = requests.post(url, data=params)
    if response.status_code != 200:
        print("❌ Error:", response.text)
        return ""
    return response.json()["data"]["translations"][0]["translatedText"]

def translate_po_file(input_path, target_lang, api_key):
    po = polib.pofile(input_path)
    translated_count = 0

    for entry in po:
        if entry.msgid.strip() and not entry.msgstr.strip():
            print(f"🌍 Translating: {entry.msgid}")
            translated = translate_text(entry.msgid, target_lang, api_key)
            if translated:
                entry.msgstr = translated
                translated_count += 1
            time.sleep(0.1)  # Avoid rate limiting

    po.save(input_path)
    print(f"\n✅ {translated_count} entries translated and saved to: {input_path}")

if __name__ == "__main__":
    import argparse

    parser = argparse.ArgumentParser(description="Translate .po file using Google Translate API and API KEY")
    parser.add_argument("input", help="Input .po file (will be overwritten)")
    parser.add_argument("--lang", required=True, help="Target language code (e.g. da, fr, it)")
    parser.add_argument("--key", required=True, help="Your Google Translate API key")

    args = parser.parse_args()
    translate_po_file(args.input, args.lang, args.key)
