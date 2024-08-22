/**
 * BreadCrumbs.jsx
 * A breadcrumbs component.
 */

import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCode } from '@fortawesome/free-solid-svg-icons';
import { useNavigate } from "react-router-dom";

export function Breadcrumbs({crumbs}){
	const navigate = useNavigate();
	const onAnchorClick = (e, path) => {
		e.preventDefault();
		navigate(path);
	};

	return (<nav aria-label="breadcrumb">
		<ol className="breadcrumb">
			{crumbs.map((crumb, index)=>{
				if(index === 0){
					return (<li key={index} className="breadcrumb-item"><a href="#" onClick={e=>onAnchorClick(e, crumb.path)}><FontAwesomeIcon icon={faCode} /> {crumb.title}</a></li>);
				}else if(index === crumbs.length-1){
					return (<li key={index} className="breadcrumb-item active" aria-current="page">{crumb.title}</li>);
				}else{
					return (<li key={index} className="breadcrumb-item"><a href="#" onClick={e=>onAnchorClick(e, crumb.path)}> {crumb.title}</a></li>);
				}
			})}
		</ol>
	</nav>);

}