import React from 'react';
import { ServerTable } from '../components/ServerTable.jsx';
import { AdminPage } from '../components/AdminPage.jsx';

export function Admin(){
	let crumbs = [{title:"Home", path:"/"},{title:"Admin Dashboard",path:'/admin'}];

	let columns = [
		{col: 'create_ts', display: 'Created', render(v){ return new Date(v*1000).toLocaleDateString() }},
		{col: 'title', display: 'Title'},
		{col: 'action', display: 'Action'}
	];

	return (<AdminPage crumbs={crumbs}>
		<ServerTable caption="my sweet table" columns={columns} query='admin_posts' />
	</AdminPage>);
} 