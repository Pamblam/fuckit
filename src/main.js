import React from 'react';
import ReactDOM from 'react-dom/client';
import {APIRequest} from "./modules/APIRequest.js";

const PendingThreadCard = React.lazy(()=>import('./PendingThreadCard.jsx').then(module => ({ default: module.PendingThreadCard })));

(async function main(){

	const rootDiv = document.getElementById('app_container');
	const reactRoot = ReactDOM.createRoot(rootDiv);
	reactRoot.render(<Egroup meta={meta} isGroupAdmin={$_SESSION.grp_admin_of.includes(+group_id)} user={$_SESSION.user} highlightPostId={featured_post_id} />);

})();