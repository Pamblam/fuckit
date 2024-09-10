/**
 * Navbar.jsx
 * The navigation bar at the top of the page, with branding.
 */

import {useRef, useCallback} from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCode, faMagnifyingGlass } from '@fortawesome/free-solid-svg-icons';

import {useNavHelper} from '#hooks/useNavHelper';

export function Navbar(){
	const navigate = useNavHelper();
	let inputRef = useRef();

	let setInputRef = useCallback(node=>{
		if(node){
			inputRef.current = node;
		}
	});

	return (<nav className="navbar navbar-expand-lg bg-light mb-4">
		<div className="container-fluid">
			<a className="navbar-brand" href="#" onClick={e=>navigate(e, '/')}>
				<FontAwesomeIcon icon={faCode} /> Fuckit
			</a>
			<ul className="navbar-nav">
				<li className="nav-item">
					<a className="nav-link" href="./custom_page">Custom Page</a>
				</li>
			</ul>
			<button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span className="navbar-toggler-icon" />
			</button>
			<div className="collapse navbar-collapse" id="navbarSupportedContent">
				<form className="d-flex ms-auto" role="search" onSubmit={e=>navigate(e, '/search/'+encodeURIComponent(inputRef.current.value.trim()))}>
					<div className="input-group">
						<input type="search" className="form-control" placeholder="Search" ref={setInputRef} />
						<button className="btn btn-secondary" type="submit">
							<FontAwesomeIcon icon={faMagnifyingGlass} />
						</button>
					</div>
				</form>
			</div>
		</div>
	</nav>);

}