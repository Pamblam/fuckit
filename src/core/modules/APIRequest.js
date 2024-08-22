/**
 * APIRequest.js
 * Class to handle all AJAX requests.
 */

import {base_url} from '../../../config/config.json';

export class APIRequest {

	constructor(path, session=null) {
		this.endpoint = `${base_url}api/`;
		this.path = path;
		this.params = {};
		this.abortController = new AbortController();
		this.session = session;
	}

	get(params) {
		if (params) this.params = params;
		this.method = 'GET';
		return this.send();
	}

	post(params) {
		if (params) this.params = params;
		this.method = 'POST';
		return this.send();
	}

	put(params) {
		if (params) this.params = params;
		this.method = 'PUT';
		return this.send();
	}

	delete(params) {
		if (params) this.params = params;
		this.method = 'DELETE';
		return this.send();
	}

	patch(params) {
		if (params) this.params = params;
		this.method = 'PATCH';
		return this.send();
	}

	abort() {
		this.abortController.abort();
	}

	async send() {

		if(this.session && this.session.isExpired() && this.path !== 'Session/updateToken'){
			await this.session.updateToken();
		}

		let endpoint = `${this.endpoint}${this.path}`;

		let opts = {
			method: this.method,
			signal: this.abortController.signal,
		};

		if(this.session && this.session.get('token')){
			let headers = {};
			headers.Authorization = this.session.get('token');
			opts.headers = headers;
		}

		if(Object.keys(this.params).length){
			if (this.method === 'GET') {
				let params = new URLSearchParams(this.params);
				endpoint += `?${params.toString()}`;
			} else {
				let fd = new FormData();
				Object.keys(this.params).forEach(key => {
					if(this.params[key] instanceof File){
						fd.append(key, this.params[key], this.params[key].name);
					}else{
						fd.append(key, this.params[key]);
					}
				});
				opts.body = fd;
			}
		}
		

		let response = await fetch(endpoint, opts);

		if(this.session){
			let token_header = response.headers.get('x-auth-token');
			if (token_header) this.session.set('token', token_header);
		}
		
		return await response.json();
	}
}