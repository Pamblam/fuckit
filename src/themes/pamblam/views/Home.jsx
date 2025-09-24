/**
 * Home.jsx
 * The initial landing page
 */


import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCode } from '@fortawesome/free-solid-svg-icons';

import { PostsSummary } from '#components/PostsSummary';

export function Home(){
	return (<div>
		<h1><FontAwesomeIcon icon={faCode} /> Milton CMS - Pamblam Theme</h1>
		<p>A modern, portable, light-weight CMS built on PHP, SQLite, and React.</p>
		<PostsSummary />
	</div>);
}