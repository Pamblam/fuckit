/**
 * CustomPage.jsx
 * An example custom page.
 */

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCode } from '@fortawesome/free-solid-svg-icons';

export function CustomPage(){
	return (<div>
		<h1><FontAwesomeIcon icon={faCode} /> Fuckit - Pamblam Theme</h1>
		<p>This is a sample custom page</p>
	</div>);
}