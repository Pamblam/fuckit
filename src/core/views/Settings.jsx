/**
 * Settings.jsx
 * The admin page that displays all configuration options.
 */

import {useContext, useState, useRef, useCallback, useEffect} from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import server_config from '#config/server';
import app_config from '#config/app';
import { faCircleCheck, faGear } from '@fortawesome/free-solid-svg-icons';

import { AdminPage } from '#components/AdminPage';
import { APIRequest } from '#modules/APIRequest';
import { AppStateContext } from '#App';

export function Settings(){
	const config = Object.assign({}, server_config, app_config);
	const {userSession} = useContext(AppStateContext);
	const [loading, setLoading] = useState(false);
	const [themes, setThemes] = useState([]);

	useEffect(()=>{
		(async ()=>{
			setLoading(true);
			let res = await new APIRequest('Config/getThemes', this).get();
			setLoading(false);
			setThemes(res?.data?.themes);
		})();
	}, []);

	const [errorMessage, setErrorMessage] = useState();
	const [successMessage, setSuccessMessage] = useState();

	const app_title_ref = useRef();
	const set_new_app_title_ref = useCallback(node=>{
		if (node) app_title_ref.current = node;
	});

	const app_desc_ref = useRef();
	const set_new_app_desc_ref = useCallback(node=>{
		if (node) app_desc_ref.current = node;
	});

	const app_maxfilesize_ref = useRef();
	const set_new_app_maxfilesize_ref = useCallback(node=>{
		if (node) app_maxfilesize_ref.current = node;
	});

	const app_nodepath_ref = useRef();
	const set_new_app_nodepath_ref = useCallback(node=>{
		if (node) app_nodepath_ref.current = node;
	});

	const app_gtag_ref = useRef();
	const set_new_app_gtag_ref = useCallback(node=>{
		if (node) app_gtag_ref.current = node;
	});

	const app_theme_ref = useRef();
	const set_new_theme_ref = useCallback(node=>{
		if (node) app_theme_ref.current = node;
	});

	const app_img_ref = useRef();
	const set_new_app_img_ref = useCallback(node=>{
		if (node) app_img_ref.current = node;
	});

	const onSubmit = async e=>{
		e.preventDefault();
		if(!app_title_ref.current?.value){
			setErrorMessage('No app title provided.');
			return;
		}
		if(!app_desc_ref.current?.value){
			setErrorMessage('No app description provided.');
			return;
		}
		if(!app_maxfilesize_ref.current?.value){
			setErrorMessage('No max filesize provided.');
			return;
		}
		if(isNaN(+app_maxfilesize_ref.current?.value)){
			setErrorMessage('Invalid max filesize provided.');
			return;
		}
		setLoading(true);
		let res = await new APIRequest('Config', userSession).post({
			title: app_title_ref.current.value,
			desc: app_desc_ref.current.value,
			max_upload_size: +app_maxfilesize_ref.current.value,
			theme: app_theme_ref.current.value,
			img: app_img_ref.current?.value,
			node_path: app_nodepath_ref.current?.value,
			ga_tag: app_gtag_ref.current?.value
		});
		setLoading(false);

		if(res.has_error){
			setErrorMessage(res.message);
		}else{
			setSuccessMessage(res.message);
			window.location.reload(true);
		}
	};

	const rebuildOnly = async (e) => {
		e.preventDefault();
		setLoading(true);
		let res = await new APIRequest('Config/rebuild', userSession).post();
		setLoading(false);
		window.location.reload(true);
	};

	let crumbs = [{title:"Home", path:"/"},{title:"Admin",path:'/admin'},{title:"Settings",path:'/settings'}];
	return loading ? <p>Loading...</p> : (<AdminPage crumbs={crumbs}>
		
		<form onSubmit={onSubmit}>

			{successMessage && (<div className='alert alert-success alert-dismissible'><span dangerouslySetInnerHTML={{__html: successMessage}}></span><button type="button" className="btn-close" onClick={e=>{e.preventDefault(); setSuccessMessage(undefined);}}></button></div>)}
			{errorMessage && (<div className='alert alert-danger alert-dismissible'>{errorMessage}<button type="button" className="btn-close" onClick={e=>{e.preventDefault(); setErrorMessage(undefined);}}></button></div>)}

			<div className="mb-3">
				<label className="form-label">App Title</label>
				<input data-lpignore="true" type="text" className="form-control" defaultValue={config?.title||''} ref={set_new_app_title_ref} />
				<div className="form-text">The title of the app or blog.</div>
			</div>

			<div className="mb-3">
				<label className="form-label">App Description</label>
				<input data-lpignore="true" type="text" className="form-control" defaultValue={config?.desc||''} ref={set_new_app_desc_ref} />
				<div className="form-text">A short description of the app or blog.</div>
			</div>

			<div className="mb-3">
				<label className="form-label">Base URL</label>
				<input data-lpignore="true" type="text" className="form-control" defaultValue={config?.base_url||''} disabled />
				<div className="form-text">Relative URL path prefix. The fact that this page is loading means this is already correctly set.</div>
			</div>

			<div className="mb-3">
				<label className="form-label">Node Path</label>
				<input data-lpignore="true" type="text" className="form-control" defaultValue={config?.node_path||''} ref={set_new_app_nodepath_ref} />
				<div className="form-text">Absolute path to the Node binary.</div>
			</div>

			<div className="mb-3">
				<label className="form-label">Max Upload Filesize</label>
				<input data-lpignore="true" type="text" className="form-control" defaultValue={config?.max_upload_size||''} ref={set_new_app_maxfilesize_ref} />
				<div className="form-text">Maximum upload file size.</div>
			</div>

			<div className="mb-3">
				<label className="form-label">Open Graph Image URL</label>
				<input data-lpignore="true" type="text" className="form-control" defaultValue={config?.img||''} ref={set_new_app_img_ref} />
				<div className="form-text">The image that is shown when the blog is shared on social media.</div>
			</div>

			<div className="mb-3">
				<label className="form-label">Google Analytics Measurement ID</label>
				<input data-lpignore="true" type="text" className="form-control" defaultValue={config?.ga_tag||''} ref={set_new_app_gtag_ref} />
				<div className="form-text">G-XXXXXXX string to use for Google Analytics.</div>
			</div>

			<div className="mb-3">
				<label className="form-label">Theme</label>
				<select data-lpignore="true" type="text" className="form-control" ref={set_new_theme_ref}>
					<option value='core' selected={!config?.theme}>Core</option>
					{themes.map((theme)=>{
						return (<option value={theme} key={theme} selected={theme===config?.theme}>{theme}</option>);
					})}
				</select>
				<div className="form-text">Choose from one of the installed themes...</div>
			</div>

			<button className="mb-3 btn btn-primary me-1" type='submit'><FontAwesomeIcon icon={faCircleCheck} /> Save & Rebuild</button>
			<button className="mb-3 btn btn-secondary"><FontAwesomeIcon icon={faGear} onClick={rebuildOnly} /> Rebuild Only</button>
		</form>
	</AdminPage>);
} 