import React from 'react';
import ReactDOM from 'react-dom/client';
import {App} from './App.jsx';
import {BrowserRouter as Router, Route, Routes} from "react-router-dom";
import {base_url} from '../config/config.json';

import {Home} from './views/Home.jsx';
import {AllPosts} from './views/AllPosts.jsx';
import {NewPost} from './views/NewPost.jsx';
import {EditPost} from './views/EditPost.jsx';
import {Search} from './views/Search.jsx';
import {NotFound} from './views/NotFound.jsx';
import {Post} from './views/Post.jsx';

(async function main(){
	const rootDiv = document.getElementById('app_container');
	const reactRoot = ReactDOM.createRoot(rootDiv);
	reactRoot.render(<React.StrictMode>
			<Router basename={base_url}>
				<Routes>
					<Route path="/" element={<App />}>
						<Route path="/" element={<Home />} />
						<Route path="/admin" element={<AllPosts />} />
						<Route path="/new_post" element={<NewPost />} />
						<Route path="/post/:slugOrId" element={<Post />} />
						<Route path="/edit_post/:slugOrId" element={<EditPost />} />
						<Route path="/search/:query" element={<Search />} />
						<Route path="*" element={<NotFound />}  />
					</Route>
				</Routes>
			</Router>
		</React.StrictMode>);

})();