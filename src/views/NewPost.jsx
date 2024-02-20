import React from 'react';
import ReactDOM from 'react-dom/client';
import { AdminPostsTable } from '../components/AdminPostsTable.jsx';
import { AdminPage } from '../components/AdminPage.jsx';

export function NewPost(){
	let crumbs = [{title:"Home", path:"/"},{title:"Admin",path:'/admin'},{title:"New Post",path:'/new_post'}];
	const onSumbit = e=>{
		e.preventDefult();
		console.log('caught form submission');
	};
	return (<AdminPage crumbs={crumbs}>
		<form onSubmit={onSumbit}>

			<div className="mb-3">
				<label htmlFor="new_post_title" className="form-label">Post Title</label>
				<input type="email" className="form-control" id="new_post_title" aria-describedby="new_post_title_help" />
				<div id="new_post_title_help" className="form-text">Give the post a title.</div>
			</div>

			<ul className="nav nav-tabs">
				<li className="nav-item">
					<a className="nav-link active" data-bs-toggle="tab" href="#new_post_compose">Compose</a>
				</li>
				<li className="nav-item">
					<a className="nav-link" data-bs-toggle="tab" href="#new_post_preview">Preview</a>
				</li>
			</ul>
			<div className="tab-content">
				<div className="tab-pane container active" id="new_post_compose">
					<p>compose</p>
				</div>
				<div className="tab-pane container fade" id="new_post_preview">
					<p>preview</p>
				</div>
			</div>

		</form>
	</AdminPage>);
} 