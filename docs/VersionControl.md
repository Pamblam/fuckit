<p align="center">
	<img src="images/milton.png" />
</p>

# Backups and Version Control

Since Milton CMS uses SQLite and is fully self-contained, you can use Git to backup and version control your Milton CMS website, as long as your database file doesn't exceed your Git server's max file size.

To version control your website with Git:

 - Remove the `.git` directory: `rm -rf .git`
 - Remove the following lines from the `.gitignore` file:
   - database/milton.db
   - public/assets/images/*
   - !public/assets/images/.gitkeep
 - Create your repo and add/commit/push.