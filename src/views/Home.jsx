import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCode } from '@fortawesome/free-solid-svg-icons';
import { PostsSummary } from '../components/PostsSummary.jsx';

export function Home(){
	return (<div>
		<h1><FontAwesomeIcon icon={faCode} /> Fuckit</h1>
		<p>A simple PHP, SQLite, and ReactJS CMS for programmers.</p>
		<PostsSummary />
	</div>);
} 