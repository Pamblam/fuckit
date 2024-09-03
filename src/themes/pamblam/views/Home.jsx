/**
 * Home.jsx
 * The initial landing page
 */


import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCode } from '@fortawesome/free-solid-svg-icons';

import { PostsSummary } from '#components/PostsSummary';

export function Home(){
	return (<div>
		<h1><FontAwesomeIcon icon={faCode} /> Fuckit - Pamblam Theme</h1>
		<p>A simple PHP, SQLite, and ReactJS CMS for programmers.</p>
		<PostsSummary />
	</div>);
}