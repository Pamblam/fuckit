# Fuckit

A modern, portable, lightweight CMS built on PHP, SQLite, and React. 

 - Built as an SPA.
 - Built in SEO/OpenGraph tags.
 - Write posts in Markdown.
 - Utilizes CLI scripts for admin tasks.
 - Built for Apache and Unix/Linux.
 - No PHP sessions, which means no cookies. At all.

## Philosphy

Fuckit is the Vim of content management systems. Nothing is WYSWYG. If you need a drag-and-drop form builder, Wordpress is great. If you enjoy coding and are comfortable with the command line, you stand to benefit from the simplicity and speed of something more modern.

It's 2024 for fuck sake. A huge drawback of most CMSs is they they're not SPA. Fuckit literally only has a single `index.php` file to serve the browser. If you navigate directly to a post, PHP will throw some open graph tags into the header before serving it, but React's router takes care of everything else.

Mama said cookies are the devil, so Fuckit avoids those, too. Authentication is done via token that is saved ina  database and is saved in LocalStorage. It is passed along with every request that requires authentication via an Authentication header. The token is matched against your user-agent and IP address and is updated with every request for, frankly, a much more secure interface than any CMS needs.

## Installing

 - Clone the app to your server: `git clone git@github.com:Pamblam/fuckit.git`
 - Point Apache to serve straight from the `public` directory of the app.

## Managing Content

From the browser, navigate to the `/admin` directory of your app and log in with the user and password you created in the setup script. You can create, preview, edit, delete, publish and unpublish all your posts here.

#### Anatomy of a Post

Here' what you need to know when creating a new post

 - **Title**: The title is converted to a unique slug that is used as a permalink for this post, for SEO purposes. If you create a post call "Hello, world!" it will be accessible from `yourwebsite.com/hello-world`. The title is also added to the OpenGraph tags of the page. 
 - **Summary**: The summary may be used by the theme, or not. It is important and included for SEO purposes though as it is appended to the the page's OpenGraph description tag.
 - **Post Body**: Type your post body in the `compose` section and preview it in the `preview` tab. Posts are formatted with Markdown. There are no cute little buttons to make your text bold.
 - **Images**: There is a button to upload images, which drops the markdown to display the image into the `compose` textarea. Images are limited by whatever size restriction you indicated in the setup script. The first image in the post is also used as the OpenGraph image for the post.
 - **Tags**: Tags are used to categorize content. Can be useful for themeing.
 - **Publish**: You can write and save posts without publishing them. Any post that is not published will not be available to the public, and can only be previewd by a logged-in user.

## Site Admin Scripts

Aside from content management, Fuckit uses CLI scripts for all administration tasks. All of which are set as `npm` scripts.

 - `npm run build`: Changes the import aliases to point to files in your theme directoy (if any) and runs Webpack to build the Javascript and transpile the React.
 - `npm run create_user`: If you want to have multiple users creating content, you can add another one with this script.
 - `npm run edit_user <username or id>`: Conveiniencce script to edit a user based on their ID or username.
 - `npm run set_theme <theme directory>`: This sets the theme in the config file after running some checks. After running this successfully, you'll still need to rebuild the app with `npm run build`.
 - `npm run update <path to new version>`: Update the existing Fuckit instance to a new version whie retaining all content and themes. See the *Updating Fuckit* section below.

## Themeing and customizing

There are two folders in the `src` directory. The `core` folder, which should never be altered, and the `themes` folder, which can contain subfolders for themes, eg, a theme called `mytheme` would live in `src/themes/mytheme`. The theme folder should have the same directory structure as the `src/core` directory. Files in the theme directory superscede files in the core directory. This means you theme can change as little or as much of the core theme as you want. If you only want to change the navbar component, your theme file only needs to contain a `components/Navbar.jsx` file.

#### Adding custom pages

Copy the `main.jsx` file from the `core` directory to the root of your theme. Create a new page in your theme's `views` directory and add a route for it in the `main.jsx` page.

## Deploying

 - Upload the files and run the setup script. The script will tell you if you need to make changes to file permissions.
 - Configure Apache to allow .htaccess changes in the web root, including mod_rewrite.

## Updating Fuckit

These steps will update your Fuckit install while maintaining all content and themes.
 
  1. Clone the new software alongside your existing install, eg, if you're in the parent directory that contains your existing Fuckit instance Run: `git clone git@github.com:Pamblam/fuckit.git ./fuckit_update` - Now you should have a `fuckit` and a `fuckit_new` directory in your working directory.
  2. It's a good idea to make a backup of your existing copy: `cp -R fuckit fuckit_backup`
  3. Move into the existing copy that you want to update and run the install script, passing it the path to the updated copy as the first argument, eg: `cd fuckit && npm run update ../fuckit_update`
  4. If all went as planned, you can now run the build script: `npm run build`
  5. Check it out in the browser to make sure it all looks good. If so, go ahead and delete your updated copy: `rm -rf ../fuckit_update`

## Version Controlling

Since Fuckit uses SQLite and is fully self-contained, you can use Git to backup and version control your Fuckit website, as long as your database file doesn't exceed your Git server's max file size.

To version control your website with Git:

 - Remove the `.git` directory: `rm -rf .git`
 - Remove the following lines from the `.gitignore` file:
   - database/fuckit.db
   - public/assets/images/*
   - !public/assets/images/.gitkeep
 - Create your repo and add/commit/push.

## License

The current version of Fuckit includes a WTF Public License. The *Do What The Fuck You Want To* Public License ([WTFPL](http://www.wtfpl.net/about/)) is a free software license.