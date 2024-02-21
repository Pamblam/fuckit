import React from 'react';
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