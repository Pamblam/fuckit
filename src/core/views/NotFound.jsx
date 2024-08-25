/**
 * NotFound.jsx
 * The 404/page not found page. 
 */

import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCode } from '@fortawesome/free-solid-svg-icons';

export function NotFound(){
	return (<div>
		<h1><FontAwesomeIcon icon={faCode} /> Fuckit</h1>
		<p>404: Page not found.</p>
	</div>);
}