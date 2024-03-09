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
	const [recentPosts, setRecentPosts] = React.useState([]);

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
            setPost(res.data);
        })();
    }, []);

    let component = (<p>Loading</p>);

    if(post){
        component = (<div className='row'>
			<div className={`${recentPosts.length ? 'col-md-9' : 'col'}`}>
				<h3>{post.post.title}</h3>
				<small>by {post.author.display_name} on {new Date(post.post.create_ts * 1000).toLocaleDateString()}</small>
				<hr />
				<div dangerouslySetInnerHTML={{__html: post.post.body}}></div>
				<br/>
			</div>
            {recentPosts.length && (<div className='col-md-3'>
				<p>Recent posts here...</p>
			</div>)}
        </div>);
        if(post.post.publish !== '1'){
            component = (<AuthPage>{component}</AuthPage>);
        }
    }

	return (<>{component}</>);
} 