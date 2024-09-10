
import { useNavigate } from "react-router-dom";
import {useEffect, useState} from 'react';

export function useNavHelper(){

	let [path, setPath] = useState(null);
	let [event, setEvent] = useState(null);
	let [scrollListener, setScrollListener] = useState(null);

	const navigate = useNavigate();

	useEffect(()=>{
		if(path){
			
			// If the event is set, cancel it
			if(event) event.preventDefault();

			// If we're currently scrolling, cancel the listener;
			if(scrollListener) removeEventListener('scroll', scrollListener);

			// Function to do the navigation only when the page is scrolled to the top
			let sl = ()=>{
				if(scrollY === 0){
					removeEventListener('scroll', sl);
					setScrollListener(null);
					navigate(path);
					setEvent(null);
					setPath(null);
				}
			};

			// If the page is scrolled to the top, do the navigation
			// else, scroll to the top and then do the navigation
			if(scrollY === 0){
				sl();
			}else{
				setScrollListener(sl);
				addEventListener('scroll', sl);
				scrollTo({
					top: 0,
					behavior: 'smooth'
				});
			}

		}
	}, [path, event]);

	return function(a, b){
		if(a.preventDefault){
			setEvent(a);
			setPath(b);
		}else{
			setEvent(null);
			setPath(a);
		}
	}
}