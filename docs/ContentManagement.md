<p align="center">
	<img src="images/milton.png" />
</p>

# Content Management

From the browser, navigate to the `/admin` directory of your app and log in with the user and password you created during setup. You can create, preview, edit, delete, publish and unpublish all your posts here.

## Anatomy of a Post

Here' what you need to know when creating a new post

 - **Title**: The title is converted to a unique slug that is used as a permalink for this post, for SEO purposes. If you create a post call "Hello, world!" it will be accessible from `yourwebsite.com/hello-world`. The title is also added to the OpenGraph tags of the page. 
 - **Summary**: The summary may be used by the theme, or not. It is important and included for SEO purposes though as it is appended to the the page's OpenGraph description tag.
 - **Post Body**: Type your post body in the `compose` section and preview it in the `preview` tab. Posts are formatted with Markdown. There are no cute little buttons to make your text bold.
 - **Images**: There is a button to upload images, which drops the markdown to display the image into the `compose` textarea. Images are limited by whatever size restriction you indicated in the setup script. The first image in the post is also used as the OpenGraph image for the post.
 - **Tags**: Tags are used to categorize content. Can be useful for themeing.
 - **Publish**: You can write and save posts without publishing them. Any post that is not published will not be available to the public, and can only be previewd by a logged-in user.