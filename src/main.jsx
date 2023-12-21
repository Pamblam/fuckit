import React from 'react';
import ReactDOM from 'react-dom/client';
import {App} from './App.jsx';

(async function main(){

	const rootDiv = document.getElementById('app_container');
	const reactRoot = ReactDOM.createRoot(rootDiv);
	reactRoot.render(<App />);

})();