/**
 * EditPost.jsx
 * The admin page for editing posts. 
 */

import { useParams } from "react-router-dom";

import { AdminPage } from '#components/AdminPage';
import { PostForm } from '#components/PostForm';

export function EditPost(){
	const { slugOrId } = useParams();

	let crumbs = [{title:"Home", path:"/"},{title:"Admin",path:'/admin'},{title:"Edit Post",path:`/edit_post/${slugOrId}`}];

	return (<AdminPage crumbs={crumbs}>
		<PostForm slugOrId={slugOrId} />
	</AdminPage>);
} 