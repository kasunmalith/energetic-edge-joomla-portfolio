# Local configuration

This repository is a sanitized portfolio copy of a Joomla website. It has no production database dump, uploaded client galleries or production `configuration.php`.

To create a local Joomla configuration:

1. Copy `configuration.example.php` to `configuration.php`.
2. Replace the database host, username, password, database name and table prefix with isolated local values.
3. Set local writable paths for `log_path` and `tmp_path`.
4. Generate a new Joomla secret for the local installation.
5. Import a separate, synthetic development database through your own Joomla/database workflow.

Do not place production credentials, database exports, client photographs, logs or backup archives in this repository. The ignore rules protect these paths from normal Git additions.

The live site uses Joomla content and database records for pages, menus and albums. Those records are intentionally not included in this portfolio source.

## Google Drive album integration

`plugins/content/fskmalbum/generate.php` reads its Google Drive developer key and each server parent-folder ID from the PHP environment. The required variable names are listed in `.env.example`. Configure those values in the hosting environment; do not add them to the source tree.
