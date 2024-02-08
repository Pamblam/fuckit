import React from 'react';
import ReactDOM from 'react-dom/client';
import {App} from './App.jsx';
import {StrictMode} from 'react';
import {BrowserRouter as Router, Route, Routes} from "react-router-dom";
import {Home} from './views/Home.jsx';
import {config} from './config.js';
import { Admin } from './views/Admin.jsx';

(async function main(){

	const rootDiv = document.getElementById('app_container');
	const reactRoot = ReactDOM.createRoot(rootDiv);
	reactRoot.render(<StrictMode>
			<Router basename={config.base_url}>
				<Routes>
					<Route path="/" element={<App />}>
						<Route path="/" element={<Home />} />
						<Route path="/admin" element={<Admin />} />
					</Route>
				</Routes>
			</Router>
		</StrictMode>);

})();