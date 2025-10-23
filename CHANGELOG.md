# Changelog
All notable changes to **Ys_RuleTime** will be documented in this file.

Follows [Keep a Changelog](https://keepachangelog.com/en/1.0.0/) and [Semantic Versioning](https://semver.org/).

---

## [1.0.0] - 2025-10-23

### Added
- Initial stable release of **Ys_RuleTime** module.
- Added *From Time* and *To Time* fields to **Cart Price Rules** in Admin.
- Created table `ys_salesrule_time` linked 1:1 with `salesrule` via `rule_id`.
- Implemented repository, resource model, and helper for CRUD and configuration.
- Added plugin to enforce rule activation only within defined time window.
- Supports website timezone and safe save via after-commit logic.

---

### Author
Maintained by **Yevhen Sviet**  
📧 [ysviet@gmail.com](mailto:ysviet@gmail.com)  
Licensed under the **MIT License** © 2025
