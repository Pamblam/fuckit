/**
 * UserSession.js
 * Class to handle the user's session on the client side.
 */

import { APIRequest } from "./APIRequest.js";

class UserSession{

	constructor(){
		// last time the session was authenticated by the server
		this.server_auth_time = null;

		// Set Session properties
		this.props = {
			last_checked: null,
			id: null,
			username: null,
			display_name: null,
			token: null
		};

		this.validateServerPromise = null;
		this.loadFromStorage();
	}

	async isExpired(){
		// token is valid for only six hours
		let now = new Date().getTime();
		return !this.props.last_checked || this.props.last_checked >= now - (1000 * 60 * 60 * 6);
	}

	async logout(){
		let res = await new APIRequest('Session', this).delete();
		Object.keys(this.props).forEach(key=>{
			this.set(key, null);
		});
	}

	async updateToken(){
		let res = await new APIRequest('Session/updateToken', this).patch();
		if(!res.has_error && res.data.User && res.data.User.id){
			this.set('display_name', res.data.User.display_name);
			this.set('username', res.data.User.username);
			this.set('id', res.data.User.id);
			//this.set('last_checked', now);
			return true;
		}
		return false;
	}

	// Log in to start a session
	async login(username, password){
		let res = await new APIRequest('Session', this).post({username, password});
		if(!res.has_error && res.data.User && res.data.User.id){
			this.set('display_name', res.data.User.display_name);
			this.set('username', res.data.User.username);
			this.set('id', res.data.User.id);
			return true;
		}
		return false;
	}

	// Check if user is currently logged in, without asking the server
	isLoggedIn(){
		return this.props.last_checked && this.props.id;
	}

	// If there is a current session older than 5 minutes, validate it with the server
	validateSession(){
		if(this.validateServerPromise) return this.validateServerPromise;
		this.validateServerPromise = new Promise(async done=>{
			if(this.props.token){
				let now = new Date().getTime();
				let five_minutes_ago = now - (1000 * 60 * 5);	
				if(this.props.last_checked && (this.props.last_checked > five_minutes_ago)){
					done(true);
				}else{
					let res = await new APIRequest('Session', this).get();
					if(!res.has_error && res.data.User && res.data.User.id){
						this.set('display_name', res.data.User.display_name);
						this.set('username', res.data.User.username);
						this.set('id', res.data.User.id);
						this.set('last_checked', now);
						done(true);
					}else{
						done(false);
					}
				}
			}else{
				done(false);
			}
			
		}).then((res)=>{
			this.validateServerPromise = null;
			return res;
		});
		return this.validateServerPromise;
	}

	loadFromStorage(){
		Object.keys(this.props).forEach(prop=>{
			let val = localStorage.getItem(`session.${prop}`);
			this.set(prop, val || null);
		});
		this.props.id = +this.props.id;
		this.props.last_checked = +this.props.last_checked;
	}

	set(prop, val){
		if(this.props.hasOwnProperty(prop)){
			this.props[prop] = val;
			if(val === null){
				localStorage.removeItem(`session.${prop}`);
			}else{
				localStorage.setItem(`session.${prop}`, val);
			}
			return true;
		}
		return false;
	}

	get(prop){
		if(this.props.hasOwnProperty(prop)){
			return this.props[prop];
		}
	}
}

export const userSession = new UserSession();