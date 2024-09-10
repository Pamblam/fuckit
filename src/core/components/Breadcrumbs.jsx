/**
 * BreadCrumbs.jsx
 * A breadcrumbs component.
 */

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCode } from '@fortawesome/free-solid-svg-icons';

import {useNavHelper} from '#hooks/useNavHelper';

export function Breadcrumbs({crumbs}){
	const navigate = useNavHelper();

	return (<nav aria-label="breadcrumb">
		<ol className="breadcrumb">
			{crumbs.map((crumb, index)=>{
				if(index === 0){
					return (<li key={index} className="breadcrumb-item"><a href="#" onClick={e=>navigate(e, crumb.path)}><FontAwesomeIcon icon={faCode} /> {crumb.title}</a></li>);
				}else if(index === crumbs.length-1){
					return (<li key={index} className="breadcrumb-item active" aria-current="page">{crumb.title}</li>);
				}else{
					return (<li key={index} className="breadcrumb-item"><a href="#" onClick={e=>navigate(e, crumb.path)}> {crumb.title}</a></li>);
				}
			})}
		</ol>
	</nav>);

}