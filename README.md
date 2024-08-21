# Fuckit
Fuckit is a deadass fire content management system, no cap—it’s built for programmers who don’t have time for the BS. We’re talking a Single Page App (SPA) that vibes with Apache, running PHP and SQLite on the back end, and flexing React and Bootstrap on the front end. When we say simple, we mean this thing is light as hell, but the real tea? It’s got a hella short learning curve. You just need to peep the codebase for a hot sec, and you’ll be ready to start customizing and theming it—assuming you’re already tight with React, of course. Easy peasy, lemon squeezy.

## Why?
I wanted to whip up a blog, but I was not about to mess with that WordPress vibe—it’s just too extra. And those so-called "Lite" systems like Pico? Yeah, they were big yikes. I was into the whole portable thing Pico had going on, no MySQL database and whatnot, but using flat files for everything? That’s straight-up cringe and low-key sus. Plus, where the heck are all the SPA CMS’s? Probably 'cause making a single-page app that’s non-dev-friendly is a whole-ass struggle bus. So I was like, bet—I’ll make one just for the code gang. Aiming it at programmers means I can dodge all the unnecessary UI drip that doesn’t have anything to do with content, like user management—who even needs that? We got an NPM script for making users, and the web interface? Strictly for content creation and management. No cap, no fluff.

## Why call it that, though?
'Cause that’s literally what I said to myself after hunting for a CMS that checked all the boxes. WordPress? Nah, didn’t vibe. Pico? Nah, didn’t hit either. So I was like, fuck it, I’m rolling my own. No cap.

## Installing
Here’s the install game plan, no cap:
 - First off, clone the app to your server: `git clone git@github.com:Pamblam/fuckit.git`
 - Point Apache to serve straight from the `public` directory of the app—no detours.
 - Next up, install those NPM modules: `npm install`
 - Get your config locked in: `npm run config`
 - Finally, build out that UI: `npm run build`

## Creating additional users
There isn't an out-of-the-box UI for creating users, but there's an npm command.
- run: `npm run create_user`

## Theming
Theming is done by changing the `.jsx` files in `/src/components` and `/src/views`. That's why it's for programmers only.

## User Sessions and Security
Sessions are kept locked down in the SQLite database and localStorage—no cookies, no PHP sessions, none of that outdated stuff. The APIRequest class handles all the AJAX vibes and automatically slaps an auth token onto every request that needs it. For security, we check the token against the user’s IP address and user agent, and we refresh it every 6 hours to keep things tight.

## License
Version 1.0.0 includes a WTF Public License. The *Do What The Fuck You Want To* Public License ([WTFPL](http://www.wtfpl.net/about/)) is a free software license.