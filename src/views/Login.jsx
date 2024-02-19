import React from 'react';
import ReactDOM from 'react-dom/client';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCode, faSpinner } from '@fortawesome/free-solid-svg-icons';
import { LoginForm } from '../components/LoginForm.jsx';
import { useNavigate } from "react-router-dom";

export function Login({User}){
	const navigate = useNavigate();

	let [loggedIn, setloggedIn] = React.useState(null);

	React.useEffect(()=>{
		(async ()=>{
			let valid = await User.validateSession();
			setloggedIn(valid);
		})();
	}, []);

	let cmp = (<div className='text-center my-5'>
		<FontAwesomeIcon icon={faSpinner} pulse size="3x" />
	</div>);

	if(loggedIn === true){
		navigate('/admin');
	}else{
		cmp = (<LoginForm onSuccess={()=>setloggedIn(true)} User={User} />);
	}

	return (<div>
		<h1><FontAwesomeIcon icon={faCode} /> Login</h1>
		{cmp}
	</div>);
} 