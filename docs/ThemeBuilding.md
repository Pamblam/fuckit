<p align="center">
	<img src="images/milton.png" />
</p>

# Themeing and customizing

There are two folders in the `src` directory. The `core` folder, which should never be altered, and the `themes` folder, which can contain subfolders for themes, eg, a theme called `mytheme` would live in `src/themes/mytheme`. The theme folder should have the same directory structure as the `src/core` directory. Files in the theme directory superscede files in the core directory. This means you theme can change as little or as much of the core theme as you want. If you only want to change the navbar component, your theme file only needs to contain a `components/Navbar.jsx` file.

## Adding custom pages

Copy the `main.jsx` file from the `core` directory to the root of your theme. Create a new page in your theme's `views` directory and add a route for it in the `main.jsx` page.