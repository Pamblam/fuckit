import React from 'react';
import { PostsSummary } from '../components/PostsSummary.jsx';
import { useParams } from "react-router-dom";

export function Search(){
	const { query } = useParams();
	return (<div>
		<PostsSummary searchQuery={query} noResultsText={`No results found for: ${query}`} />
	</div>);
} 