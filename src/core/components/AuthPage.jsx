/**
 * AuthPage.jsx
 * Wrapper component that shows either it's children, or the login page if the user is not logged in.
 */

import React from 'react';

import { LoginForm } from '#components/LoginForm';
import { AppStateContext } from '#App';

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