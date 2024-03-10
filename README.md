# Fuckit
Fuckit is a **simple** content management system designed for programmers. It's a Single Page App built for Apache, with PHP and SQLite on the back end, and React and Bootstrap on the client side. Simple means that it's light-weight, but more importantly, it means that Fuckit has a real short learning curve. You only need to look at the codebase for a few minutes and you'll be ready to start implementing and theming it (as long as you know React).

## Why?
I wanted to set up a simple blog. I didn't want anything as heavy as WordPress and systems branded as "Lite" CMS's like Pico weren't really doin' it for me either. I liked the idea of something portable like Pico, that didn't rely on a MySQL database, but using flat files to store everything felt hacky and kinda gross. Besides, why aren't there any SPA CMS's out there? Probably because a single page app would be really hard to theme for non-programmers. So I decided to make one for programmers. Targeting programmers only means I can omit UI stuff that isn't directly related to managing content, like user management functions. There's an NPM script to create users, the web interface is strictly for creating and managing content.

## Why call it that, though?
Because that's what I said to myself after looking for another CMS that fit my requirements. Wordpress didn't fit, Pico didn't fit. Fuckit, rolling my own.

## Installing
- Clone the app to your server: `git clone git@github.com:Pamblam/fuckit.git`
- Direct apache to serve from the `public` directory of the app.
- Install NPM modules: `npm install`
- Set up config: `npm run config`
- Build the UI: `npm run build`

## Creating additional users
There isn't an out-of-the-box UI for creating users, but there's an npm command.
- run: `npm run create_user`

## Theming
Theming is done by changing the `.jsx` files in `/src/components` and `/src/views`. That's why it's for programmers only.

## User Sessions and Security
Sessions are stored in the SQLite database and in localStorage. There are no cookies or PHP sessions involved. The `APIRequest` class which handles all AJAX requests automatically attaches an auth token as a request header to every request that requires it. For security the token is checked against the user's IP address and user agent, and it's refreshed every 6 hours.

## License
Version 1.0.0 includes a WTF Public License. The *Do What The Fuck You Want To* Public License ([WTFPL](http://www.wtfpl.net/about/)) is a free software license.