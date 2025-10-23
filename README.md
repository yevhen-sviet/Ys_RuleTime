# Ys_RuleTime — Exact Time Window for Cart Price Rules

**Author:** Yevhen Sviet  
**Magento Version:** 2.4.8-p2+  
**Namespace:** `Ys_RuleTime`  
**License:** MIT

---


Overview:

**Ys_RuleTime** extends Magento’s native **Cart Price Rules** (Sales Rules) by adding support for **exact time windows** within the standard date range.

This lets store administrators define *hour-specific* activation periods — for example, *“Apply this discount only between 09:00 and 17:00 every day during the promotion period.”*

Magento natively supports only **from/to dates**; this module adds **from/to times** that are validated automatically during rule collection and checkout.


Features:

- Adds **“From Time”** and **“To Time”** fields to the Cart Price Rule form in Admin.
- Saves time windows to a dedicated table:  
  `ys_salesrule_time (rule_id, from_time, to_time)`
- Enforces rule activation only when the current store time is inside the specified time window.
- Fully integrated into the existing rule validation flow (`addWebsiteGroupDateFilter`).
- Automatically cleans up on rule deletion (via FK with `ON DELETE CASCADE`).
- Respects website timezone settings.


Installation:

1) Copy or install the module into your Magento installation:

app/code/Ys/RuleTime

2) Enable and run setup:

bin/magento module:enable Ys_RuleTime
bin/magento setup:upgrade
bin/magento cache:flush


3) Re-deploy static content if you’re on production mode:

bin/magento setup:static-content:deploy -f