/**
 * Navigate to a path, and optionally cancel a click event.
 * @param ClickEvent|String a - If this is a click event, preventDefault will be called on it, otherwise it is expected to be a path to navigate to
 * @param Strin|undefined b - If a is an Event, this is expected to be the path to navigate to. 
 */
export function FINavigate(nav){
	return function(a, b){
		let path = a;
		if(a.hasOwnProperty('preventDefault')){
			a.preventDefault();
			path = b;
		}
		nav(path);
	}
}


