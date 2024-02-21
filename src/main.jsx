import React from 'react';
import ReactDOM from 'react-dom/client';
import {App} from './App.jsx';
import {BrowserRouter as Router, Route, Routes} from "react-router-dom";
import config from '../config/config.json';

import {Home} from './views/Home.jsx';
import {Admin} from './views/Admin.jsx';
import {NewPost} from './views/NewPost.jsx';
import {NotFound} from './views/NotFound.jsx';

(async function main(){
	const rootDiv = document.getElementById('app_container');
	const reactRoot = ReactDOM.createRoot(rootDiv);
	reactRoot.render(<React.StrictMode>
			<Router basename={config.base_url}>
				<Routes>
					<Route path="/" element={<App />}>
						<Route path="/" element={<Home />} />
						<Route path="/admin" element={<Admin />} />
						<Route path="/new_post" element={<NewPost />} />
						<Route path="*" element={<NotFound />}  />
					</Route>
				</Routes>
			</Router>
		</React.StrictMode>);

})();