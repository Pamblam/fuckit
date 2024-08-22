/**
 * LoginForm.jsx
 * Login form component. 
 */

import React from 'react';
import { AppStateContext } from '../App.jsx';

export function LoginForm(){
	const {session, userSession} = React.useContext(AppStateContext);
	const [,setSessionState] = session;

	let username_input = React.useRef();
	let password_input = React.useRef();
	let error_div = React.useRef();
	let form = React.useRef();

	const doLogin = async e => {
		e.preventDefault();
		if(form.current.dataset.pending == '1') return;
		form.current.dataset.pending = '1';
		error_div.current.classList.add('d-none');
		let username = username_input.current.value;
		let password = password_input.current.value;
		let success = await userSession.login(username, password);
		if(!success){
			error_div.current.classList.remove('d-none');
		}else{
			setSessionState(true);
		}
		form.current.dataset.pending = '0';
	};

	return (<div className="card mx-auto mb-3" style={{maxWidth: '18rem'}}>
		<div className="card-header">
			Login
		</div>
		<div className="card-body">
			<div className='alert alert-danger d-none' ref={error_div}>Invalid Login</div>
			<form onSubmit={doLogin} ref={form}>
				<div className="mb-3 mt-3">
					<label className="form-label">Username:</label>
					<input ref={username_input} type="text" className="form-control" placeholder="Enter Username" />
				</div>
				<div className="mb-3">
					<label className="form-label">Password:</label>
					<input ref={password_input} type="password" className="form-control" placeholder="Enter password" />
				</div>
				<button type="submit" className="btn btn-primary">Submit</button>
			</form> 
		</div>
	</div>);
}