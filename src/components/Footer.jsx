import React from 'react';
import ReactDOM from 'react-dom/client';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCode } from '@fortawesome/free-solid-svg-icons';

export function Footer(){
	return (<nav class="navbar bg-light">
		<div class="container-fluid">
			<span class="navbar-text text-center w-100 small">
				<FontAwesomeIcon icon={faCode} /> Fuckit CMS | <a href='#'>Admin</a>
			</span>
		</div>
	</nav>);
}


