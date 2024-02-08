import React from 'react';
import ReactDOM from 'react-dom/client';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCode } from '@fortawesome/free-solid-svg-icons';

export function Admin(){
	return (<div>
		<h1><FontAwesomeIcon icon={faCode} /> Admin Area</h1>
		<p>yo yo yo.</p>
	</div>);
} 