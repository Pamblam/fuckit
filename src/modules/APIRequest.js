export class APIRequest{
	
	constructor(){
		this.endpoint = "./api/";
		this.method_params = {};
	}
	
	set(method, param_name=null, param_value=null){
		if(!this.method_params[method]){
			this.method_params[method] = {};
		}
		if(param_name !== null && param_value !== null){
			this.method_params[method][param_name] = param_value;
		}
		
		return this;
	}
	
	getQueryString(){
		return Object.keys(this.method_params).map(method => {
			const enc_method = encodeURIComponent(method);
			const method_keys = Object.keys(this.method_params[method]);
			if(!method_keys.length) return enc_method;
			return method_keys.map(param_name => {
				var param_value = this.method_params[method][param_name];
				const enc_param_name = encodeURIComponent(param_name);
				const enc_param_value = encodeURIComponent(param_value);
				return `${enc_method}[${enc_param_name}]=${enc_param_value}`;
			}).join('&');
		}).join('&');
	}
	
	async send(){
		var url = this.endpoint + "?" + this.getQueryString();
		var result;
		try{
			result = await fetch(url, {
				method: "POST"
			}).then(resp=>resp.json());
		}catch(e){
			console.error(e);
			result = {};
			Object.keys(this.method_params).forEach(method=>{
				result[method] = {
					errors: [e.message]
				};
			});
		}
		return result;
	}
}