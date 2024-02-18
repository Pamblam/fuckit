export class APIRequest {

	constructor(path) {
		this.endpoint = "./api/";
		this.path = path;
		this.params = {};
		this.abortController = new AbortController();
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
		let opts = {
			method: this.method,
			signal: this.abortController.signal,
		};
		let endpoint = `${this.endpoint}${this.path}`;
		let headers = {};
		let token = localStorage.getItem('x-auth-token');
		if (token) headers.Authorization = token;
		opts.headers = headers;

		if (this.method === 'GET') {
			let params = new URLSearchParams(this.params);
			endpoint += `?${params.toString()}`;
		} else {
			let fd = new FormData();
			Object.keys(this.params).forEach(key => {
				fd.append(key, this.params[key])
			});
			opts.body = fd;
		}

		let response = await fetch(endpoint, opts);
		let token_header = response.headers.get('x-auth-token');
		if (token_header) localStorage.setItem('x-auth-token', token_header);
		return await response.json();
	}
}