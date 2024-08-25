/**
 * Footer.jsx
 * Shows a simple footer component with links.
 */

import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCode } from '@fortawesome/free-solid-svg-icons';
import { Link } from 'react-router-dom';
import { useNavigate } from "react-router-dom";

import { AppStateContext } from '#App';

export function Footer(){
	const navigate = useNavigate();
	const {session, userSession} = React.useContext(AppStateContext);
	const [sessionState, setSessionState] = session;

	let footer_link = '';
	if(sessionState === true){
		let doLogout = async (e)=>{
			e.preventDefault();
			await userSession.logout();
			setSessionState(false);
			navigate('/');
		};
		footer_link = <>| <a href='#' onClick={doLogout}>Logout</a> | <Link to="/admin">Admin</Link></>
	}else{
		footer_link = <>| <Link to="/admin">Admin</Link></>
	}

	return (<nav className="navbar bg-light">
		<div className="container-fluid">
			<span className="navbar-text text-center w-100 small">
				<FontAwesomeIcon icon={faCode} /> Fuckit CMS {footer_link}
			</span>
		</div>
	</nav>);
}


