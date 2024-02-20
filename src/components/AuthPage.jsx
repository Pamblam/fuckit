import React from 'react';
import ReactDOM from 'react-dom/client';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faSpinner } from '@fortawesome/free-solid-svg-icons';
import { LoginForm } from '../components/LoginForm.jsx';
import { AppStateContext } from '../App.jsx';

export function AuthPage({children}){
	const {session} = React.useContext(AppStateContext);
	const [sessionState] = session;

	let component;
	if(sessionState){
		component = <>{children}</>
	}else{
		component = <LoginForm  />
	}

	return (<>
		{component}
	</>);
} 