# Aura
A tool with limited AI capabilities to manage computers in academic labs. It is composed of two parts: the web part (PHP/MySQL) and the `aura-client` (PHP, running as a command line app on every computer to be managed).

![aura_screenshot](https://cloud.githubusercontent.com/assets/512405/9367585/a7168a50-4694-11e5-8bc8-05a91db5c9c7.png)

## Installation

Clone the repo to your web document root (e.g. `/var/www/`). Create a MySQL database and populate it with the content of [inc/resources/sql/aura.sql](https://github.com/Dovyski/Aura/blob/master/inc/resources/sql/aura.sql). Finally change the file `/config.php` to fit your needs, like the database name/user/password. You're good to go!

## Contributors

If you liked the project and want to help, you are welcome! Submit pull requests or [open a new issue](https://github.com/Dovyski/Aura/issues) describing your idea.

## License

Aura is licensed under the MIT license.
