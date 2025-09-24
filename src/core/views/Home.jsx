/**
 * Home.jsx
 * The initial landing page
 */


import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCode } from '@fortawesome/free-solid-svg-icons';
import miltonLogo from '../assets/img/milton.png';
import { PostsSummary } from '#components/PostsSummary';

export function Home(){
	return (<div className='text-center'>
		<img src={miltonLogo} className='img-fluid' style={{maxWidth: '300px'}} />
		<h1 className='milton'><FontAwesomeIcon icon={faCode} /> Milton CMS</h1>
		<p>A modern, portable, light-weight CMS built on PHP, SQLite, and React. </p>
		<PostsSummary />
	</div>);
}