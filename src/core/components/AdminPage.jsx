
/**
 * AdminPage.jsx 
 * The wrapper component for all the administration pages, includes sidebar, breadcrumbs
 */

import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCaretRight } from '@fortawesome/free-solid-svg-icons';
import { useNavigate } from "react-router-dom";

import { Breadcrumbs } from '#components/Breadcrumbs';
import { AuthPage } from '#components/AuthPage';

export function AdminPage({children, crumbs}){
	const navigate = useNavigate();
	const onAnchorClick = (e, path) => {
		e.preventDefault();
		navigate(path);
	};

	let activePage = crumbs.at(-1);
	let adminLinks = [
		{title: "All Posts", path: "/admin"},
		{title: "New Post", path: "/new_post"}
	];

	return (<AuthPage>
		<div className='row'>
			<div className='col-md-3'>
				<nav className="nav flex-column">
					{adminLinks.map((link, key)=>{
						if(link.path === activePage.path){
							return (<a key={key} className="nav-link active" aria-current="page" href="#" onClick={e=>e.preventDefault()}><FontAwesomeIcon icon={faCaretRight} /> {link.title}</a>);
						}else{
							return (<a key={key} className="nav-link" href="#" onClick={e=>onAnchorClick(e, link.path)}>{link.title}</a>);
						}
					})}
				</nav>
			</div>
			<div className='col-md-9'>
				<Breadcrumbs crumbs={crumbs} />

				{children}
			</div>
		</div>
	</AuthPage>);
}