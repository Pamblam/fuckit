/**
 * SidebarPosts.jsx
 * A card component that shows a paginated list of posts.
 */

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faAnglesRight, faClockRotateLeft } from '@fortawesome/free-solid-svg-icons';
import React from 'react';
import { useNavigate } from "react-router-dom";
import { APIRequest } from '../modules/APIRequest.js';

export function SidebarPosts(){

	const navigate = useNavigate();
	let [results, setResults] = React.useState([]);
	let [page, setPage] = React.useState(1);
	let [totalPages, setTotalPages] = React.useState(0);

	const getRows = async ()=>{
		let res = await new APIRequest('Pagination').get({query:'all_posts_summary', page, order_by_col:'create_ts', order_dir:'desc', page_size:5});
		if(!res.has_error){
			setTotalPages(res.data.total_pages);
			setResults(res.data.results);
		}
	};

	React.useEffect(()=>{
		getRows();
	}, [page]);

	let pagination = '';
	if(totalPages > 1){
		pagination = (<div className="card-footer"><a href='#' onClick={e=>{e.preventDefault(); setPage(page === totalPages ? 1 : page+1); }} style={{display:'block', textAlign:'right'}}><small>Load More <FontAwesomeIcon icon={faAnglesRight} /></small></a></div>);
	}

	return (<div className="card">
		<div className="card-header">
			<FontAwesomeIcon icon={faClockRotateLeft} /> Recent Posts
		</div>
		<ul className="list-group list-group-flush">
			{results.map(post=>{
				return (<li key={post.id} style={{cursor:'pointer'}} className="list-group-item" onClick={e=>{e.preventDefault(); navigate(`/post/${post.slug}`); }}>
					<div><b className='mb-0'>{post.title}</b></div>
					<small>{post.summary}</small>
				</li>);
			})}
		</ul>
		{pagination}
	</div>);
}