import { useEffect } from "react";
import { useLocation, useNavigationType } from "react-router-dom";
import ReactGA from "react-ga4";
import app_config from '#config/app';

export function AnalyticsListener() {
	const location = useLocation();
	const navType = useNavigationType();

	useEffect(() => {
		if(app_config?.ga_tag){
			ReactGA.send({
				hitType: "pageview",
				page: location.pathname + location.search,
				title: document.title,
			});
		}
	}, [location, navType, app_config]);

	return null;
}