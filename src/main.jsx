import React from 'react';
import ReactDOM from 'react-dom/client';
import {App} from './App.jsx';
import {StrictMode} from 'react';
import {BrowserRouter as Router, Route, Routes} from "react-router-dom";
import {Home} from './views/Home.jsx';
import {config} from './config.js';
import { Admin } from './views/Admin.jsx';
import { Login } from './views/Login.jsx';
import { User } from './modules/User.js';

(async function main(){

	const rootDiv = document.getElementById('app_container');
	const reactRoot = ReactDOM.createRoot(rootDiv);
	reactRoot.render(<StrictMode>
			<Router basename={config.base_url}>
				<Routes>
					<Route path="/" element={<App User={User} />}>
						<Route path="/" element={<Home User={User} />} />
						<Route path="/admin" element={<Admin User={User} />} />
						<Route path="/login" element={<Login User={User} />} />
					</Route>
				</Routes>
			</Router>
		</StrictMode>);

})();