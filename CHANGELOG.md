# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [0.0.1] - 2025-04-14

### Added
- Multilingual support using `.po` and `.mo` files, including build-time compilation via GitHub Actions
- Admin calendar views: availability, reservations (list, new, detail, history), courts, users, user types
- Public calendar views: reservation booking, reservation detail
- Shared calendar module supporting FullCalendar (`calendar-general.js`, `calendar-editable.js`)
- Modular plugin structure following MVC architecture
- Domain services and repositories for courts, reservations, blocks, opening hours
- GitHub Actions CI pipeline for:
  - Compiling translations
  - Generating ZIP artifact on every push
- GitHub Actions CD pipeline for:
  - Creating GitHub release from version in `courtly.php`
  - Attaching ZIP artifact

### Changed
- `.mo` files removed from the repository and `.gitignore` updated

### Security
- `main` branch is now protected via GitHub ruleset
- Only pull requests with successful checks and review can be merged

---

## [0.0.0] - 2025-04-14
