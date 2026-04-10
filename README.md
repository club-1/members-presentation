Webpage presenting members of UNIX server
=========================================

Usage
-----

Unix users are supposed to have a `PRESENTATION.md` file listed in their home directory.
This script will convert to markdown the content of those files and agregate it using this [template](template.php).
Users can choose their display name using *Front Matter* config like this :

```md
---
name: lumpy space princess
color: pink
---

# Anything about me:

bla bla *bla*
```

### Webring

In the *Front Matter*, users may also define a list of websites using the `website` key.

```yaml
websites:
    - https://cool.land
    - https://my-site.club1.fr
```

All thoses websites are aggregated in a JSON file called `webring.json`, under the `urls` key.
Thus file is rendered at the same time of main render (HTML presentation page).

#### Random website

The path `hasard.php` redirect to a random website from this list.


Iframing
--------

Sample Iframe configuration:

```html
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            iframe, body, html {
                box-sizing: border-box;
                width: 100%;
                height: 100%;
                padding: 0;
                margin: 0;
                border: none;
                overflow: hidden;
            }
        </style>
    </head>
    <body>
        <iframe id="iframe" src="<members-presentation-url>" frameborder="0"></iframe>
        <script>
            let iframe = document.getElementById('iframe');
            iframe.addEventListener('load', (e) => {
                if (e.target instanceof HTMLIFrameElement) {
                    let data = {target: window.location.hash.slice(1)};
                    e.target.contentWindow.postMessage(data, '*');
                }
            });
        </script>
    </body>
</html>

```

Develop
-------

clone the git repo

    git clone https://github.com/club-1/members-presentation

Install dependencies

    make

