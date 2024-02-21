import React from 'react';
import { AdminPostsTable } from '../components/AdminPostsTable.jsx';
import { AdminPage } from '../components/AdminPage.jsx';

export function Admin(){
	let crumbs = [{title:"Home", path:"/"},{title:"Admin Dashboard",path:'/admin'}];
	return (<AdminPage crumbs={crumbs}>
		<AdminPostsTable />
	</AdminPage>);
} 