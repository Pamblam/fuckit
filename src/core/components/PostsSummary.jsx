/**
 * PostsSummary.jsx
 * Shows a paginated list of post summaries.
 */

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faAnglesRight, faAnglesLeft } from '@fortawesome/free-solid-svg-icons';
import {useState, useEffect} from 'react';

import {useNavHelper} from '#hooks/useNavHelper';
import { APIRequest } from '#modules/APIRequest';

export function PostsSummary({searchQuery='', noResultsText='', tags=[]}){

	const navigate = useNavHelper();
	let [results, setResults] = useState([]);
	let [page, setPage] = useState(1);
	let [totalPages, setTotalPages] = useState(0);

	const getRows = async ()=>{
		let res;
		if(tags.length){
			res = await new APIRequest('Pagination').get({query:'all_posts_of_tag', params: JSON.stringify({tags}), page, order_by_col:'create_ts', order_dir:'desc', page_size:15, search_term:searchQuery});
		}else{
			res = await new APIRequest('Pagination').get({query:'all_posts_summary', page, order_by_col:'create_ts', order_dir:'desc', page_size:15, search_term:searchQuery});
		}
		if(!res.has_error){
			setTotalPages(res.data.total_pages);
			setResults(res.data.results);
		}
	};

	useEffect(()=>{
		getRows();
	}, [page, searchQuery]);

	let pagination = '';
	if(totalPages > 1){
		let p_btns = [];
		for(let i=0; i<totalPages; i++){
			let curr_page = i+1;
			// Draw the first page, if the current page isn't the first page
			if(curr_page == 1 && page !== 1){
				p_btns.push(<li key={`page-btn-${curr_page}`} className="page-item"><a className="page-link" href="#" onClick={e=>{e.preventDefault(); setPage(1);}}><FontAwesomeIcon icon={faAnglesLeft} /></a></li>);
			}
			// draw the previous button if the current page is the one precending the page, but obnly if the last page isn't the first page
			if(curr_page == page - 1 && page > 2){
				p_btns.push(<li key={`page-btn-${curr_page}`} className="page-item"><a className="page-link" href="#" onClick={e=>{e.preventDefault(); setPage(curr_page);}}>{curr_page}</a></li>);
			}
			// draw the current page
			if(curr_page == page){
				p_btns.push(<li key={`page-btn-${curr_page}`} className="page-item"><a className="page-link disabled" href="#" onClick={e=>{e.preventDefault();}}>{curr_page}</a></li>);
			}
			// draw the next page, but only if it's not the last page
			if(curr_page == page + 1 && curr_page < totalPages){
				p_btns.push(<li key={`page-btn-${curr_page}`} className="page-item"><a className="page-link" href="#" onClick={e=>{e.preventDefault(); setPage(curr_page);}}>{curr_page}</a></li>);
			}
			// Draw the last page, if the current page is not the last page
			if(curr_page == totalPages && page !== totalPages){
				p_btns.push(<li key={`page-btn-${curr_page}`} className="page-item"><a className="page-link" href="#" onClick={e=>{e.preventDefault(); setPage(curr_page);}}><FontAwesomeIcon icon={faAnglesRight} /></a></li>);
			}
		}

		pagination = (<div className='clearfix'>
			<nav className='float-end'>
				<ul className="pagination">
					{p_btns}
				</ul>
			</nav>
		</div>);
	}

	return (<div className='text-center'>
		{results.map(post=>{
			return (<div key={post.id} style={{cursor:'pointer', backgroundImage: `url(${encodeURI(post.graph_img)})`, backgroundSize: 'cover', backgroundPosition: 'center'}} className="list-group-item m-3 text-center" onClick={e=>navigate(e, `/post/${post.slug}`)}>
				<div style={{backgroundColor: 'rgba(255,255,255,.5)', padding: '1em'}}>
					<h4 className='mb-0 text-center'>{post.title}</h4>
					{!!post.summary && (<p className='m-0'>{post.summary}</p>)}
					<small style={{fontSize:'0.8em'}}>by {post.author_name} on {new Date(post.create_ts * 1000).toLocaleDateString()}</small>
				</div>
			</div>);
		})}
		{results.length === 0 && !!noResultsText && (<p>{noResultsText}</p>)}
		{pagination}
		<br/>
	</div>);
}