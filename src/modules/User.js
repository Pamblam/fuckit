import {APIRequest} from  './APIRequest.js';


export const User = (()=>{

    // Only validate by the server once per page load
    let validated_by_server = false;

    let change_callbacks = [];

    let user = {
        id: null,
        username: null,
        display_name: null
    };

	Object.keys(user).forEach(k=>{
		let val = localStorage.getItem(`user.${k}`);
		if(val) user[k] = val;
	});

    let loginPromise = null;

    const USER = {

        onChange(fn){
            change_callbacks.push(fn);
        },

        async logout(){
            let res = await new APIRequest('Session').delete();
            if(!res.has_error){
                USER.set('display_name', null);
                USER.set('username', null);
                USER.set('id', null);
                change_callbacks.forEach(fn=>fn());
                localStorage.removeItem('x-auth-token');
                return true;
            }
            return false;
        },

        async login(username, password){
            let res = await new APIRequest('Session').post({username, password});
            if(res.data && res.data.User){
                USER.set('display_name', res.data.User.display_name);
                USER.set('username', res.data.User.username);
                USER.set('id', res.data.User.id);
                change_callbacks.forEach(fn=>fn());
                return true;
            }
            return false;
        },

		async validateSession(){
            if(validated_by_server) return true;
			if(!user.id) return false;
			let res = await new APIRequest('Session').get();
            if(res.data && res.data.LoggedIn){
                USER.set('display_name', res.data.User.display_name);
                USER.set('username', res.data.User.username);
                USER.set('id', res.data.User.id);
                validated_by_server = true;
                change_callbacks.forEach(fn=>fn());
                return true;
            }
            return false;
		},

        set(prop, val){
            if(user.hasOwnProperty(prop)){
                user[prop] = val;
				localStorage.setItem(`user.${prop}`, val);
                return true;
            }
            return false;
        },

        get(prop){
            if(user.hasOwnProperty(prop)){
                return user[prop];
            }
        }
	};

    return USER;
})();