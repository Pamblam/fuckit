
import React from 'react';
import { APIRequest } from '../modules/APIRequest';

export function LoginForm(){
	let username_input = React.useRef();
	let password_input = React.useRef();
	let form = React.useRef();

	const doLogin = async () => {
		if(form.current.dataset.pending == '1') return;
		form.current.dataset.pending = '1';
		let password = password_input.current.value;
		let username = username_input.current.value;
		let request = new APIRequest();
		request.set('login', 'username', username);
		request.set('login', 'password', password);
		let res = (await request.send()).login;
		if(res.success)
	};

	return (<div className="card mx-auto mb-3" style={{maxWidth: '18rem'}}>
		<div className="card-header">
			Login
		</div>
		<div className="card-body">
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