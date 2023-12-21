import React from 'react';
import ReactDOM from 'react-dom/client';
import {Navbar} from './components/Navbar.jsx';
import {Footer} from './components/Footer.jsx';

const Home = React.lazy(()=>import('./views/Home.jsx').then(module => ({ default: module.Home })));

export function App(){

	let [view, setView] = React.useState('home');
	let [pageParams, setPageParams] = React.useState({});
	let [user, setUser] = React.useState(null);

	let viewComponent = '';
	switch(view){
		case "home":
			viewComponent = <Home />;
			break;
	}

	return (<div className="container">
		<Navbar />
		{viewComponent}
		<Footer />
	</div>);

}