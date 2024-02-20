# fuckit

Wanted to set up a simple blog, Wordpress is too heavy, Pico is too light. Fuckit. Rolling my own.

Fuckit is a simple CMS designed for programmers. It's built for Apache, uses PHP and SQLite on the back side and the included front-end is a ReactJS and Bootstrap 5 SPA.

## Installing
- Clone the app to your server: `git clone git@github.com:Pamblam/fuckit.git`
- Direct apache to serve from the `public` directory of the app.
- Run the installer script: `npm run setup`

## Creating additional users
There isn't an out-of-the-box UI for creating users, but there's an npm command.
- run: `npm run create_user`