import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCaretUp, faCaretDown } from '@fortawesome/free-solid-svg-icons';
import { APIRequest } from '../modules/APIRequest';

export function ServerTable({caption, columns, order_col, order_dir, query}){

	let [rows, setRows] = React.useState([]);
	let [orderByCol, setOrderByCol] = React.useState(order_col ? order_col : columns[0].col);
	let [orderDir, setOrderDir] = React.useState(order_dir ? order_dir : 'asc');
	let [page, setPage] = React.useState(1);
	let [totalRecords, setTotalRecords] = React.useState(0);
	let [totalPages, setTotalPages] = React.useState(0);

	const getRows = async ()=>{
		let res = await new APIRequest('ServerTable').get({query, page, order_by_col:orderByCol, order_dir:orderDir, page_size:10});
		if(!res.has_error){
			setTotalRecords(res.data.total_records);
			setTotalPages(res.data.total_pages);
			setRows(res.data.results);
		}
	};

	React.useEffect(()=>{
		getRows();
	}, []);

	let header = (<thead>
		<tr>
			{columns.map(col=>{
				let caret = '';
				if(orderByCol === col.col){
					caret = <FontAwesomeIcon className='float-end' icon={orderDir === 'asc' ? faCaretUp : faCaretDown} />
				}
				return <th scope="col">{col.display}{caret}</th>;
			})}
		</tr>
	</thead>);

	return (<>
		<input type="text" className="form-control float-end" style={{maxWidth: '15em'}} placeholder='Search Table'></input>
		<table className="table table-hover ">
			<caption>
				{caption ? caption : ''}
				<nav aria-label="Page navigation example" className='float-end'>
					<ul className="pagination">
						<li className="page-item"><a className="page-link" href="#">Previous</a></li>
						<li className="page-item"><a className="page-link" href="#">1</a></li>
						<li className="page-item"><a className="page-link" href="#">2</a></li>
						<li className="page-item"><a className="page-link" href="#">3</a></li>
						<li className="page-item"><a className="page-link" href="#">Next</a></li>
					</ul>
				</nav>	
			</caption>
			{header}
			<tbody>
				{rows.map(row=>{
					return (<tr key={`row-${row.id}`}>
						{columns.map((col, col_idx)=>{
							let value = row[col.col];
							if(col.render) value = col.render(value, row);
							return (<td key={`row-${row.id}-col_idx`}>{value}</td>);
						})}
					</tr>);
				})}
			</tbody>
		</table>
		
	</>);

}