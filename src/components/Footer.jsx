import React from 'react';
import ReactDOM from 'react-dom/client';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCode } from '@fortawesome/free-solid-svg-icons';
import { Link } from 'react-router-dom';

export function Footer(){
	return (<nav className="navbar bg-light">
		<div className="container-fluid">
			<span className="navbar-text text-center w-100 small">
				<FontAwesomeIcon icon={faCode} /> Fuckit CMS | <Link to="/admin">Admin</Link>
			</span>
		</div>
	</nav>);
}


