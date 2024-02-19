import React from 'react';
import ReactDOM from 'react-dom/client';
import {Navbar} from './components/Navbar.jsx';
import {Footer} from './components/Footer.jsx';
import {Outlet} from 'react-router';

const Home = React.lazy(()=>import('./views/Home.jsx').then(module => ({ default: module.Home })));

export function App({User}){

	let [renderCnt, setRenderCnt] = React.useState(0);
	User.onChange(()=>{
		setRenderCnt(renderCnt+1);
	});

	return (<div className="container">
		<Navbar />
		<Outlet />
		<Footer User={User} />
	</div>);

}