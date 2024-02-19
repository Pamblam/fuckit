import React from 'react';
import ReactDOM from 'react-dom/client';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCode } from '@fortawesome/free-solid-svg-icons';
import { Link } from 'react-router-dom';

export function Footer({User}){

	let footer_link;
	if(User.get('id')){
		footer_link = <Link to="/admin">Admin</Link>
	}else{
		footer_link = <a href='#' onclick={footer_link_ref}>Logout</a>
	}

	return (<nav className="navbar bg-light">
		<div className="container-fluid">
			<span className="navbar-text text-center w-100 small">
				<FontAwesomeIcon icon={faCode} /> Fuckit CMS | {footer_link}
			</span>
		</div>
	</nav>);
}


