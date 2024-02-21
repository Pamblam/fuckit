import React from 'react';
import { AdminPage } from '../components/AdminPage.jsx';
import { useNavigate } from "react-router-dom";
import { APIRequest } from '../modules/APIRequest.js';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faImage, faCircleCheck } from '@fortawesome/free-solid-svg-icons';
import { FI } from '../modules/FI.js';
import { AppStateContext } from '../App.jsx';

export function NewPost(){
	const {userSession} = React.useContext(AppStateContext);
	const navigate = useNavigate();
	const [errorMessage, setErrorMessage] = React.useState();

	const textarea_ref = React.useRef();
	const preview_tab_ref = React.useRef();
	const new_post_preview_ref = React.useRef();
	const img_btn_ref = React.useRef();
	const fi_instance_ref = React.useRef();
	const graph_img_ref = React.useRef();

	let crumbs = [{title:"Home", path:"/"},{title:"Admin",path:'/admin'},{title:"New Post",path:'/new_post'}];

	const onSumbit = e=>{
		e.preventDefult();
		console.log('caught form submission');
	};

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
					setErrorMessage('Unable to upload image');
					return;
				}
				if(!graph_img_ref.current){
					graph_img_ref.current = res.data.path;
				}

				let img_md = `![](${res.data.path})`;
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
		e.stopPropagation();
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
		if (node) {
			preview_tab_ref.current = node;
			preview_tab_ref.current.addEventListener('show.bs.tab', getMDPreview);
		}
	});

	const set_new_post_preview_ref = React.useCallback(node=>{
		if (node) new_post_preview_ref.current = node;
	});

	return (<AdminPage crumbs={crumbs}>
		<form onSubmit={onSumbit}>

			{errorMessage && (<div className='alert alert-danger'>{errorMessage}</div>)}

			<div className="mb-3">
				<label htmlFor="new_post_title" className="form-label">Post Title</label>
				<input data-lpignore="true" type="text" className="form-control" id="new_post_title" aria-describedby="new_post_title_help" />
				<div id="new_post_title_help" className="form-text">Give the post a title.</div>
			</div>

			<div className="mb-3">
				<label htmlFor="new_post_summary" className="form-label">Post Summary</label>
				<input data-lpignore="true" type="text" className="form-control" id="new_post_summary" aria-describedby="new_post_summary_help" />
				<div id="new_post_summary_help" className="form-text">Summary should be 2-4 sentences.</div>
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
					<textarea data-lpignore="true" ref={set_textarea_ref} className="form-control" id="new_post_textarea" aria-describedby="new_post_textarea_help"></textarea>
					<div id="new_post_textarea_help" className="form-text">Compose your post using Markdown.</div>
				</div>
				<div className="tab-pane container fade px-0 pt-3" id="new_post_preview" ref={set_new_post_preview_ref}>
					<p>preview</p>
				</div>
			</div>

			<div className="mb-3">
				<div className="form-check">
					<input className="form-check-input" type="checkbox" id="new_post_publish" defaultChecked={true} />
					<label className="form-check-label" htmlFor="new_post_publish">
						Publish this Post?
					</label>
				</div>
			</div>

			<button className="mb-3 btn btn-primary" type='submit'><FontAwesomeIcon icon={faCircleCheck} /> Save</button>
		</form>
	</AdminPage>);
} 