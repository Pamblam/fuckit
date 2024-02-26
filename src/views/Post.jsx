import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCode } from '@fortawesome/free-solid-svg-icons';
import { useParams } from "react-router-dom";
import { APIRequest } from '../modules/APIRequest.js';
import { useNavigate } from "react-router-dom";
import { AuthPage } from '../components/AuthPage.jsx';

export function Post(){
    const navigate = useNavigate();
    const { slugOrId } = useParams();
    const [post, setPost] = React.useState();

    React.useEffect(()=>{
        if(slugOrId) (async ()=>{
            let res;
            if(/^\d+$/.test(slugOrId)){
                res = await new APIRequest(`Post/${slugOrId}`).get();
            }else{
                res = await new APIRequest(`Post`).get({slug:slugOrId});
            }
            if(res.has_error){
                navigate('/404')
            }
            console.log("setting post", res.data);
            setPost(res.data);
        })();
    }, [slugOrId]);

    let component = (<p>Loading</p>);

    if(post){
        component = (<>
            <h3>{post.post.title}</h3>
            <div dangerouslySetInnerHTML={{__html: post.post.body}}></div>
            <small>by {post.author.display_name}</small>
        </>);
        if(post.post.publish !== '1'){
            component = (<AuthPage>{component}</AuthPage>);
        }
    }

	return (<>{component}</>);
} 