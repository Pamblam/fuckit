/**
 * main.jsx
 * The entry point for the app.
 */

import {StrictMode} from 'react';
import ReactDOM from 'react-dom/client';
import {BrowserRouter as Router, Route, Routes} from "react-router-dom";

import {App} from '#App';
import config from '#config/server';
import {Home} from '#views/Home';
import {AllPosts} from '#views/AllPosts';
import {NewPost} from '#views/NewPost';
import {EditPost} from '#views/EditPost';
import {Search} from '#views/Search';
import {NotFound} from '#views/NotFound';
import {Post} from '#views/Post';
import {CustomPage} from '#views/CustomPage';
import {Settings} from '#views/Settings';

(async function main(){
	const rootDiv = document.getElementById('app_container');
	const reactRoot = ReactDOM.createRoot(rootDiv);
	reactRoot.render(<StrictMode>
			<Router basename={config.base_url}>
				<Routes>
					<Route path="/" element={<App />}>
						<Route path="/" element={<Home />} />
						<Route path="/custom_page" element={<CustomPage />} />
						<Route path="/admin" element={<AllPosts />} />
						<Route path="/new_post" element={<NewPost />} />
						<Route path="/post/:slugOrId" element={<Post />} />
						<Route path="/edit_post/:slugOrId" element={<EditPost />} />
						<Route path="/search/:query" element={<Search />} />
						<Route path="/settings" element={<Settings />} />
						<Route path="*" element={<NotFound />}  />
					</Route>
				</Routes>
			</Router>
		</StrictMode>);
})();