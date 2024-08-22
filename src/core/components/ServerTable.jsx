/**
 * ServerTable.jsx
 * A paginated, AJAX sourced data table component.
 */

import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCaretUp, faCaretDown, faAnglesLeft, faAnglesRight } from '@fortawesome/free-solid-svg-icons';

import { APIRequest } from '#modules/APIRequest';

export function ServerTable({columns, order_col, order_dir, query}){

	let [rows, setRows] = React.useState([]);
	let [orderByCol, setOrderByCol] = React.useState(order_col ? order_col : columns[0].col);
	let [orderDir, setOrderDir] = React.useState(order_dir ? order_dir : 'asc');
	let [page, setPage] = React.useState(1);
	let [totalRecords, setTotalRecords] = React.useState(0);
	let [totalPages, setTotalPages] = React.useState(0);
	let [searchTerm, setSearchTerm] = React.useState('');

	const getRows = async ()=>{
		let res = await new APIRequest('Pagination').get({query, page, order_by_col:orderByCol, order_dir:orderDir, page_size:25, search_term:searchTerm});
		if(!res.has_error){
			setTotalRecords(res.data.total_records);
			setTotalPages(res.data.total_pages);
			setRows(res.data.results);
		}
	};

	let tout = null;
	const onSearchKey = e =>{
		if(tout) clearTimeout(tout);
		tout = setTimeout(()=>{
			setSearchTerm(e.target.value.trim());
			tout = null;
		}, 500);
	};

	const onHeaderClick = col => {
		if(orderByCol == col){
			setOrderDir(orderDir == 'asc' ? 'desc' : 'asc');
		}else{
			setOrderDir('asc');
			setOrderByCol(col);
		}
	};

	React.useEffect(()=>{
		getRows();
	}, [page, orderByCol, orderDir, searchTerm]);

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

	let caption = `Page ${page} of ${totalPages}`;

	return (<>
		<input type="text" className="form-control float-end" style={{maxWidth: '15em'}} placeholder='Search Table' onInput={onSearchKey}></input>
		<table className="table table-hover ">
			<caption>
				{caption}
				{pagination}
			</caption>
			<thead>
				<tr>
					{columns.map(col=>{
						if(col.sortable){
							let caret = '';
							if(orderByCol === col.col){
								caret = <FontAwesomeIcon className='float-end' icon={orderDir === 'asc' ? faCaretUp : faCaretDown} />
							}
							return <th key={`hdr-${col.col}`} scope="col" style={{cursor:"pointer"}} onClick={()=>onHeaderClick(col.col)}>{col.display}{caret}</th>;
						}else{
							return <th key={`hdr-${col.col}`} scope="col">{col.display}</th>;
						}
					})}
				</tr>
			</thead>
			<tbody>
				{rows.map(row=>{
					return (<tr key={`row-${row.id}`}>
						{columns.map((col, col_idx)=>{
							let value = row[col.col];
							if(col.render) value = col.render(value, row);
							return (<td key={`row-${row.id}-${col_idx}`}>{value}</td>);
						})}
					</tr>);
				})}
			</tbody>
		</table>
		
	</>);

}