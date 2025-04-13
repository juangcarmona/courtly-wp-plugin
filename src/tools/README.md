# 🈂️ Courtly – PO File Translator

This developer tool helps automatically translate `.po` language files using Google Translate.

## 🛠 Requirements

You must have Python 3 and virtual environment support:

```bash
python3 -m venv venv
source venv/bin/activate
pip install polib requests
```

## 📂 Usage

```bash
python translate-po.py /absolute/path/to/your.po --lang xx --key YOUR_GOOGLE_API_KEY
```

- `--lang`: target language (e.g. `it`, `fr`, `pt`)
- `--key`: your Google Translate API key

✅ Only `msgid` entries with **empty `msgstr`** will be translated.  
✅ The original `.po` file will be overwritten in place.

## 📦 Example

```bash
python translate-po.py ~/courtly-wp-plugin/src/languages/fr_FR.po --lang fr --key AIzaSyD...
```

---

## 📝 Notes

- Uses [Google Cloud Translate API](https://cloud.google.com/translate) – make sure you have billing enabled and your API key is configured to allow requests from your terminal (no referrer restrictions).
- Run `msgfmt` if you want to generate `.mo` files after translating.

```bash
msgfmt fr_FR.po -o fr_FR.mo
```

or, for all `.po` files in the directory:

```bash
for po in *.po; do
  mo="${po%.po}.mo"
  echo "🔁 Generando $mo..."
  msgfmt -o "$mo" "$po"
done
```
- Make sure to check the translated `.po` files for any errors or inconsistencies.