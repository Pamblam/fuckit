/**
 * Post.jsx
 * The page that displays a post.
 */

import {useState, useEffect} from 'react';
import { useParams } from "react-router-dom";

import {useNavHelper} from '#hooks/useNavHelper';
import { APIRequest } from '#modules/APIRequest';
import { AuthPage } from '#components/AuthPage';
import { SidebarPosts } from '#components/SidebarPosts';

export function Post(){
    const navigate = useNavHelper();
    const { slugOrId } = useParams();
    const [post, setPost] = useState();

    useEffect(()=>{
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
    }, [slugOrId]);

    let component = (<p>Loading</p>);

    if(post){
        component = (<>
		<h3>{post.post.title}</h3>
			<small>by {post.author.display_name} on {new Date(post.post.create_ts * 1000).toLocaleDateString()}</small>
			<hr />

			<div className='row'>
				<div className='col-md-9'>
					<div dangerouslySetInnerHTML={{__html: post.post.body}}></div>
					<br/>
				</div>
				<div className='col-md-3'>
					<SidebarPosts />
					<br/>
				</div>
			</div>

		</>);

        if(post.post.published != '1'){
            component = (<AuthPage>{component}</AuthPage>);
        }
    }

	return (<>{component}</>);
} 