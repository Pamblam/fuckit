import React from 'react';
import ReactDOM from 'react-dom/client';
import { AdminPage } from '../components/AdminPage.jsx';
import { useNavigate } from "react-router-dom";

export function NewPost(){
	const navigate = useNavigate();
	const textarea_ref = React.useRef();
	const preview_tab_ref = React.useRef();
	const new_post_preview_ref = React.useRef();

	let crumbs = [{title:"Home", path:"/"},{title:"Admin",path:'/admin'},{title:"New Post",path:'/new_post'}];

	const onSumbit = e=>{
		e.preventDefult();
		console.log('caught form submission');
	};

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

	const getMDPreview = function(e){
		e.stopPropagation();
		let text = textarea_ref.current.value;
		new_post_preview_ref.current.innerHTML = '<p>Loading...</p>';
		console.log(text);
	};

	React.useEffect(()=>{
		if(textarea_ref.current){
			textarea_ref.current.addEventListener('input', autoExpandTextarea);
			return ()=>{
				if(textarea_ref.current) textarea_ref.current.removeEventListener('input', autoExpandTextarea);
			}
		}else{
			console.log('reloading..?');
			navigate('/new_post');
		}
	}, [textarea_ref]);

	React.useEffect(()=>{
		if(preview_tab_ref.current && textarea_ref.current && new_post_preview_ref.current){
			console.log('attaching');
			preview_tab_ref.current.addEventListener('show.bs.tab', getMDPreview);
			return ()=>{
				if(preview_tab_ref.current) preview_tab_ref.current.removeEventListener('show.bs.tab', getMDPreview);
			}
		}else{
			console.log('reloading..? asfd');
			navigate('/new_post');
		}
	}, [preview_tab_ref, textarea_ref, new_post_preview_ref]);

	return (<AdminPage crumbs={crumbs}>
		<form onSubmit={onSumbit}>

			<div className="mb-3">
				<label htmlFor="new_post_title" className="form-label">Post Title</label>
				<input data-lpignore="true" type="text" className="form-control" id="new_post_title" aria-describedby="new_post_title_help" />
				<div id="new_post_title_help" className="form-text">Give the post a title.</div>
			</div>

			<ul className="nav nav-tabs">
				<li className="nav-item">
					<a className="nav-link active" data-bs-toggle="tab" href="#new_post_compose">Compose</a>
				</li>
				<li className="nav-item">
					<a ref={n=>preview_tab_ref.current=n} className="nav-link" data-bs-toggle="tab" href="#new_post_preview">Preview</a>
				</li>
			</ul>
			<div className="tab-content">
				<div className="tab-pane container active px-0 pt-3" id="new_post_compose">
					<div className="mb-3">
						<textarea data-lpignore="true" ref={n=>textarea_ref.current=n} className="form-control" id="new_post_textarea" aria-describedby="new_post_textarea_help"></textarea>
						<div id="new_post_textarea_help" className="form-text">Compose your post using Markdown.</div>
					</div>
				</div>
				<div className="tab-pane container fade px-0 pt-3" id="new_post_preview" ref={n=>new_post_preview_ref.current=n}>
					<p>preview</p>
				</div>
			</div>

		</form>
	</AdminPage>);
} 