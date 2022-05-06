Webpage presenting members of UNIX server
=========================================

Usage
-----

Unix users are supposed to have a `PRESENTATION.md` file listed in their home directory.
This script will convert to markdown the content of those files and agregate it using this [template](template.php).
Users can choose their display name using *Front Matter* config like this :
```md
---
name: A very sophiscticated Name !
---

```

Config
------

Edit `config.sample.php` with list of members and save it as `config.php`.

If `USERS` is empty, this script will look for any users having a home in `/home`