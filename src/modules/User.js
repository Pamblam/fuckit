export const User = (()=>{
    let user = {
        id: null,
        username: null,
        display_name: null,
        session_id: null
    };
    return {
        isLoggedIn(){
            
        },
        set(prop, val){
            if(user.hasOwnProperty(prop)){
                user[prop] = val;
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