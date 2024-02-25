import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCode } from '@fortawesome/free-solid-svg-icons';
import { useParams } from "react-router-dom";
import { APIRequest } from '../modules/APIRequest.js';

export function Post(){
    const { slugOrId } = useParams();
    React.useEffect(()=>{
        if(slugOrId) (async ()=>{
            let res = new APIRequest(`Post/${slugOrId}`).get();
            console.log(res);
        })();
    }, [slugOrId]);


	return (<div>
		<h1><FontAwesomeIcon icon={faCode} /> Fuckit</h1>
		<p>Post {slugOrId} here.</p>
	</div>);
} 