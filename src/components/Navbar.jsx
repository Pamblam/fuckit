import React from 'react';
import ReactDOM from 'react-dom/client';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCode, faMagnifyingGlass } from '@fortawesome/free-solid-svg-icons';

export function Navbar(){

	return (<nav className="navbar navbar-expand-lg bg-light mb-4">
		<div className="container-fluid">
			<a className="navbar-brand" href="#">
				<FontAwesomeIcon icon={faCode} /> Fuckit
			</a>
			<button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span className="navbar-toggler-icon" />
			</button>
			<div className="collapse navbar-collapse" id="navbarSupportedContent">
				<form className="d-flex ms-auto" role="search">
					<div className="input-group">
						<input type="search" className="form-control" placeholder="Search" aria-label="Search" />
						<button className="btn btn-secondary" type="submit">
							<FontAwesomeIcon icon={faMagnifyingGlass} />
						</button>
					</div>
				</form>
			</div>
		</div>
	</nav>);

}