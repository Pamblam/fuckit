/**
 * AllPosts.jsx
 * The admin page that displays all the posts.
 */

import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCheck, faXmark, faLink, faPenToSquare } from '@fortawesome/free-solid-svg-icons';
import { Link } from 'react-router-dom';

import { ServerTable } from '#components/ServerTable';
import { AdminPage } from '#components/AdminPage';

export function AllPosts(){
	let crumbs = [{title:"Home", path:"/"},{title:"All Posts",path:'/admin'}];

	let columns = [
		{col: 'create_ts', display: 'Created', sortable:true, render(v){ 
			return new Date(v*1000).toLocaleDateString() 
		}},
		{col: 'title', display: 'Post Title', sortable:true, render(v){ 
			return (<b>{v}</b>);
		}},
		{col: 'author_name', display: 'Author', sortable:true},
		{col: 'published', display: 'Published', render(v){ 
			return v == '1' ? (<span style={{color:"green"}}><FontAwesomeIcon icon={faCheck} /></span>) : (<span style={{color:"red"}}><FontAwesomeIcon icon={faXmark} /></span>);
		}},
		{col: 'action', display: 'Action', render(v, r){ 
			return <>
				<Link to={`/post/${r.slug || r.id}`}><FontAwesomeIcon icon={faLink} /></Link>
				<span style={{margin:'.25em'}}></span>
				<Link to={`/edit_post/${r.slug || r.id}`}><FontAwesomeIcon icon={faPenToSquare} /></Link>
			</>
		}}
	];

	return (<AdminPage crumbs={crumbs}>
		<ServerTable columns={columns} query='all_posts' />
	</AdminPage>);
} 