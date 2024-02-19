import React from 'react';
import ReactDOM from 'react-dom/client';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCode, faSpinner } from '@fortawesome/free-solid-svg-icons';
import { AdminPostsTable } from '../components/AdminPostsTable.jsx';
import { LoginForm } from '../components/LoginForm.jsx';

export function Admin({User}){
	let [loggedIn, setloggedIn] = React.useState(null);

	React.useEffect(()=>{
		(async ()=>{
			let valid = await User.validateSession();
			console.log('valid', valid);
			setloggedIn(valid);
		})();
	}, []);

	let cmp = (<div className='text-center my-5'>
		<FontAwesomeIcon icon={faSpinner} pulse size="3x" />
	</div>);

	if(loggedIn === true){
		cmp = (<AdminPostsTable />);
	}else{
		cmp = (<LoginForm onSuccess={()=>setloggedIn(true)} User={User} />);
	}

	return (<div>
		<h1><FontAwesomeIcon icon={faCode} /> Admin Area</h1>
		{cmp}
	</div>);
} 