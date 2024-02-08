import {APIRequest} from  './APIRequest.js';

export const User = (()=>{

    let user = {
        id: null,
        username: null,
        display_name: null
    };

	Object.keys(user).forEach(k=>{
		let val = localStorage.getItem(`user.${k}`);
		if(val) user[k] = val;
	});

    return {

		async validateSession(){
			if(!user.id) return false;
			let request = new APIRequest();
			request.set('validateSession');
			let res = (await request.send()).validateSession;
			return res.data && res.data.valid;
		},

        set(prop, val){
            if(user.hasOwnProperty(prop)){
                user[prop] = val;
				localStorage.setItem(`user.${prop}`);
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

})();