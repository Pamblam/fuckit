import React from 'react';
import ReactDOM from 'react-dom/client';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCode } from '@fortawesome/free-solid-svg-icons';
import { Link } from 'react-router-dom';
import { useNavigate } from "react-router-dom";

export function Footer({User}){
	const navigate = useNavigate();
	const [, updateState] = React.useState();
	const forceUpdate = React.useCallback(() => updateState({}), []);
	User.onChange(()=>forceUpdate());

	let footer_link;
	if(!User.get('id')){
		footer_link = <Link to="/login">Login</Link>
	}else{
		let doLogout = async (e)=>{
			e.preventDefault();
			await User.logout();
			navigate('/login');
		};
		footer_link = <>
			<a href='#' onClick={doLogout}>Logout</a> | <Link to="/admin">Admin</Link>
		</>
	}

	return (<nav className="navbar bg-light">
		<div className="container-fluid">
			<span className="navbar-text text-center w-100 small">
				<FontAwesomeIcon icon={faCode} /> Fuckit CMS | {footer_link}
			</span>
		</div>
	</nav>);
}


