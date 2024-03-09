import React from 'react';
import { useNavigate } from "react-router-dom";
import { APIRequest } from '../modules/APIRequest.js';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faImage, faCircleCheck } from '@fortawesome/free-solid-svg-icons';
import { FI } from '../modules/FI.js';
import { AppStateContext } from '../App.jsx';

export function PostForm({slugOrId}){
	const {userSession} = React.useContext(AppStateContext);
	const navigate = useNavigate();
	const [errorMessage, setErrorMessage] = React.useState();
	const [successMessage, setSuccessMessage] = React.useState();
	const [postData, setPostData] = React.useState({});

	const new_post_title_ref = React.useRef();
	const new_post_summary_ref = React.useRef();
	const textarea_ref = React.useRef();
	const preview_tab_ref = React.useRef();
	const new_post_preview_ref = React.useRef();
	const img_btn_ref = React.useRef();
	const fi_instance_ref = React.useRef();
	const graph_img_ref = React.useRef();
	const new_post_publish_ref = React.useRef();
	const post_id_ref = React.useRef();
	const submitting_ref = React.useRef(false);

	React.useEffect(()=>{
		if(Object.keys(postData).length){
			post_id_ref.current = postData.post.id;
			graph_img_ref.current = postData.post.graph_img;
			if(new_post_title_ref.current) new_post_title_ref.current.value = postData.post.title;
			if(new_post_summary_ref.current) new_post_summary_ref.current.value = postData.post.summary;
			if(textarea_ref.current) textarea_ref.current.value = postData.post.body;
			if(new_post_publish_ref.current) new_post_publish_ref.current.checked = postData.post.published === 1;
		}
	}, [postData]);

	React.useEffect(()=>{
        if(slugOrId) (async ()=>{
            let res;
            if(/^\d+$/.test(slugOrId)){
                res = await new APIRequest(`Post/${slugOrId}`).get({raw:1});
            }else{
                res = await new APIRequest(`Post`).get({slug:slugOrId, raw:1});
            }
            if(res.has_error){
                navigate('/404')
            }
			setPostData(res.data);
        })();
    }, []);

	const onSumbit = async e=>{
		e.preventDefault();
		if(submitting_ref.current) return;
		submitting_ref.current = true;
		const title = new_post_title_ref.current.value.trim();
		const summary = new_post_summary_ref.current.value.trim();
		const body = textarea_ref.current.value.trim();
		const publish = new_post_publish_ref.current.checked ? 1 : 0;
		const graph_img = graph_img_ref.current;
		const post_id = post_id_ref.current;

		let props = {
			title,
			summary,
			body,
			publish
		};

		if(graph_img) props.graph_img = graph_img;

		let verbiage1 = publish == 1 ? 'Post' : 'Draft'
		let verbiage2 = post_id ? 'updated' : 'saved'
		let verbiage3 = publish == 1 ? 'published' : 'preview'
		let time = new Date().toLocaleTimeString();

		if(post_id){
			let res = await new APIRequest(`Post/${post_id}`, userSession).patch(props);
			if(res.has_error){
				setErrorMessage(res.message);
			}else{
				let link = location.href.replace(/(\/new_post|\/edit_post\/[^\/]*)\/?/, `/post/${res.data.Post.slug}`);
				let message = `${verbiage1} ${verbiage2} at ${time}<br><small>${verbiage1} ${verbiage3}: <a href='${link}' target=_blank>${link}</a></small>`;
				setSuccessMessage(message);
			}

			let pd = Object.assign({}, postData);
			pd.post = res.data.Post;
			setPostData(pd);
		}else{
			let res = await new APIRequest('Post', userSession).post(props);
			if(res.has_error){
				setErrorMessage(res.message);
			}else{
				let link = location.href.replace(/(\/new_post|\/edit_post\/[^\/]*)\/?/, `/post/${res.data.Post.slug}`);
				let message = `${verbiage1} ${verbiage2} at ${time}<br><small>${verbiage1} ${verbiage3}: <a href='${link}' target=_blank>${link}</a></small>`;
				setSuccessMessage(message);
			}
			post_id_ref.current = res.data.Post.id;

			let pd = Object.assign({}, postData);
			pd.post = res.data.Post;
			setPostData(pd);
		}

		submitting_ref.current = false;
	};

	const set_new_post_title_ref = React.useCallback(node=>{
		if (node){
			new_post_title_ref.current = node;
			if(postData.post) new_post_title_ref.current.value = postData.post.title;
		} 
	});

	const set_new_post_summary_ref = React.useCallback(node=>{
		if (node){
			new_post_summary_ref.current = node; 
			if(postData.post) new_post_summary_ref.current.value = postData.post.summary;
		} 
	});

	const set_new_post_publish_ref = React.useCallback(node=>{
		if (node){
			new_post_publish_ref.current = node; 
			if(postData.post) new_post_publish_ref.current.checked = postData.post.published === 1;
		}
	});

	const set_img_btn_ref = React.useCallback(node=>{
		if (img_btn_ref.current) {
			fi_instance_ref.current.destroy();
		}
		if (node) {
			img_btn_ref.current = node;
			fi_instance_ref.current = new FI({
				button: node,
				accept: ["png", "jpg", "jpeg", "gif"],
				multi: false
			});
			fi_instance_ref.current.register_callback(async function(){
				let files = fi_instance_ref.current.get_files();
				if(!files || !files.length) return;
				let file = files[0];
				fi_instance_ref.current.clear_files();
				let res = await new APIRequest('Image', userSession).post({img: file});
				if(res.has_error){
					setErrorMessage(res.message);
					return;
				}
				if(!graph_img_ref.current){
					graph_img_ref.current = res.data.path;
				}

				let img_md = `![](${encodeURI(res.data.path)})`;
				let lines = textarea_ref.current.value.split("\n");
				if(!lines.at(-1).trim()) lines[lines.length-1] = img_md;
				else lines.push(img_md);
				textarea_ref.current.value = lines.join("\n");

				let text = textarea_ref.current.value;
				new_post_preview_ref.current.innerHTML = '<p>Loading...</p>';
				res = await new APIRequest('ParseMD').get({md: text});
				if(!res.has_error) new_post_preview_ref.current.innerHTML = res.data.html;
				else setErrorMessage('Unable to process markdown');
			});
		}
	});

	const autoExpandTextarea = function(e){
		if(e) e.stopPropagation();
		this.setAttribute('rows', 1);
		var cs = getComputedStyle(this);
		var paddingTop = +cs.paddingTop.substr(0, cs.paddingTop.length-2);
		var paddingBottom = +cs.paddingBottom.substr(0, cs.paddingBottom.length-2);
		var lineHeight = +cs.lineHeight.substr(0, cs.lineHeight.length-2);
		var rows = (this.scrollHeight - (paddingTop + paddingBottom)) / lineHeight;
		this.setAttribute('rows', rows);
	};

	const set_textarea_ref = React.useCallback(node=>{
		if (textarea_ref.current) {
			textarea_ref.current.removeEventListener('input', autoExpandTextarea);
		}
		if (node) {
			textarea_ref.current = node;
			textarea_ref.current.addEventListener('input', autoExpandTextarea);
			if(postData.post){
				textarea_ref.current.value = postData.post.body;
				autoExpandTextarea.call(textarea_ref.current);
			}
		}
	});

	const getMDPreview = async function(e){
		e.stopPropagation();
		let text = textarea_ref.current.value;
		new_post_preview_ref.current.innerHTML = '<p>Loading...</p>';
		let res = await new APIRequest('ParseMD').get({md: text});
		if(!res.has_error) new_post_preview_ref.current.innerHTML = res.data.html;
		else setErrorMessage('Unable to process markdown');
	};

	const set_preview_tab_ref = React.useCallback(node=>{
		if (set_preview_tab_ref.current) {
			preview_tab_ref.current.removeEventListener('show.bs.tab', getMDPreview);
		}
		if(node) {
			preview_tab_ref.current = node;
			preview_tab_ref.current.addEventListener('show.bs.tab', getMDPreview);
		}
	});

	const set_new_post_preview_ref = React.useCallback(node=>{
		if (node) new_post_preview_ref.current = node;
	});

	return (<form onSubmit={onSumbit}>

			{successMessage && (<div className='alert alert-success alert-dismissible'><span dangerouslySetInnerHTML={{__html: successMessage}}></span><button type="button" className="btn-close" onClick={e=>{e.preventDefault(); setSuccessMessage(null);}}></button></div>)}
			{errorMessage && (<div className='alert alert-danger alert-dismissible'>{errorMessage}<button type="button" className="btn-close" onClick={e=>{e.preventDefault(); setErrorMessage(null);}}></button></div>)}

			<div className="mb-3">
				<label className="form-label">Post Title</label>
				<input data-lpignore="true" type="text" className="form-control" ref={set_new_post_title_ref} />
				<div className="form-text">Give the post a title.</div>
			</div>

			<div className="mb-3">
				<label className="form-label">Post Summary</label>
				<input data-lpignore="true" type="text" className="form-control" ref={set_new_post_summary_ref} />
				<div className="form-text">Summary should be 2-4 sentences.</div>
			</div>

			<div className="clearfix">
				<button className="btn btn-primary float-end" ref={set_img_btn_ref}><FontAwesomeIcon icon={faImage} /> Insert Image</button>
				<ul className="nav nav-tabs" style={{borderBottom: 'none'}}>
					<li className="nav-item">
						<a className="nav-link active" data-bs-toggle="tab" href="#new_post_compose">Compose</a>
					</li>
					<li className="nav-item">
						<a ref={set_preview_tab_ref} className="nav-link" data-bs-toggle="tab" href="#new_post_preview">Preview</a>
					</li>
				</ul>
			</div>
			<div className="tab-content mb-3">
				<div className="tab-pane container active px-0 pt-3" id="new_post_compose">
					<textarea data-lpignore="true" ref={set_textarea_ref} className="form-control"></textarea>
					<div className="form-text">Compose your post using Markdown.</div>
				</div>
				<div className="tab-pane container fade px-0 pt-3" id="new_post_preview" ref={set_new_post_preview_ref}>
					<p>preview</p>
				</div>
			</div>

			<div className="mb-3">
				<div className="form-check">
					<input className="form-check-input" type="checkbox" id="new_post_publish" defaultChecked={true} ref={set_new_post_publish_ref} />
					<label className="form-check-label" htmlFor="new_post_publish">
						Publish this Post?
					</label>
				</div>
			</div>

			<button className="mb-3 btn btn-primary" type='submit'><FontAwesomeIcon icon={faCircleCheck} /> Save</button>
		</form>);
} 