import React from 'react';
import ReactDOM from 'react-dom/client';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCode } from '@fortawesome/free-solid-svg-icons';
import { AdminPostsTable } from '../components/AdminPostsTable.jsx';
import { AuthPage } from '../components/AuthPage.jsx';

export function Admin(){
	return (<AuthPage>
		<h1><FontAwesomeIcon icon={faCode} /> Admin Area</h1>
		<AdminPostsTable />
	</AuthPage>);
} 