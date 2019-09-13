## About Ren'Py Editor

This app is designed to automatize the coding process.
You can add characters, backgrounds, musics... then combinate them to make scenes.
When you have a lot of scenes, you can chain them with actions.
When you have finished your story, export the script to Ren'Py and enjoy your visual novel.

## Install
- composer update
- npm install && npm run dev
- php artisan migrate:refresh --seed
- login with admin@admin.com / admin

## Settings
- folder public/stories must be writable
- rename .env.example into .env then update with your config
- for sqlite, rename database/database.sqlite.empty into database/database.sqlite

## Errors
- in few linux configurations, you will have a problem with the library vendor\sunra\php-simple-html-dom-parser\Src\Sunra\PhpSimple\simplehtmldom_1_5\simple_html_dom.php
- For the moment, fix it like this:
- Line 696
- $pattern = "/([\w:\*]*)(?:\#([\w]+)|\.([\w]+))?(?:\[@?(!?[\w:]+)(?:([!*^$]?=)[\"']?(.*?)[\"']?)?\])?([\/, ]+)/is";
- Line 1378
- if (!preg_match("/^[\w:]+$/", $tag)) {
		
## Licence free for non commercial use only
