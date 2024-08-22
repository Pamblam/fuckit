/**
 * NewPost.jsx
 * The page to create a new post.
 */

import { AdminPage } from '../components/AdminPage.jsx';
import { PostForm } from '../components/PostForm.jsx';

export function NewPost(){
	let crumbs = [{title:"Home", path:"/"},{title:"Admin",path:'/admin'},{title:"New Post",path:'/new_post'}];

	return (<AdminPage crumbs={crumbs}>
		<PostForm />
	</AdminPage>);
} 