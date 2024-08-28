/**
 * App.js
 * The top-level view of the application.
 */

import React from 'react';
import {Outlet} from 'react-router';

import {Navbar} from '#components/Navbar';
import {Footer} from '#components/Footer';
import {userSession} from '#modules/UserSession';

export const AppStateContext = React.createContext();

export function App(){
	const [sessionState, setSessionState] = React.useState(null);

	React.useEffect(()=>{
		const checkSession = async () => setSessionState(await userSession.validateSession());
		checkSession();
		const timer = setInterval(checkSession, 60000);
		return ()=>clearInterval(timer);
	}, []);
	
	let appStateContextValue = {session:[sessionState, setSessionState], userSession};
	return (<AppStateContext.Provider value={appStateContextValue}>
		<div className="container">
			<Navbar />
			<Outlet />
			<Footer />
		</div>
	</AppStateContext.Provider>);

}