import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCode, faMagnifyingGlass } from '@fortawesome/free-solid-svg-icons';
import { useNavigate } from 'react-router';

export function Navbar(){
	const navigate = useNavigate();
	let inputRef = React.useRef();

	let setInputRef = React.useCallback(node=>{
		if(node){
			inputRef.current = node;
		}
	});

	let submitSearch = e => {
		e.preventDefault();
		navigate('/search/'+encodeURIComponent(inputRef.current.value.trim()));
	};

	const onLogoClick = e => {
		e.preventDefault();
		navigate('/');
	};

	return (<nav className="navbar navbar-expand-lg bg-light mb-4">
		<div className="container-fluid">
			<a className="navbar-brand" href="#" onClick={onLogoClick}>
				<FontAwesomeIcon icon={faCode} /> Fuckit
			</a>
			<button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span className="navbar-toggler-icon" />
			</button>
			<div className="collapse navbar-collapse" id="navbarSupportedContent">
				<form className="d-flex ms-auto" role="search" onSubmit={submitSearch}>
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