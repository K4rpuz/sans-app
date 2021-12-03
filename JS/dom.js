
import { dominio , path } from "./config.js";

export const hideElement = ( element ) => {
	element.classList.add('hide');
}

export const showElement = ( element ) => {
	element.classList.remove('hide');
}

export const toggleElementVisibility = ( element ) => {
	if( element.classList.contains('hide') ) showElement( element );
	else hideElement( element );
}

export const loadElements = ( elementClasses ) => {
	const elements = [];
	elementClasses.forEach( elementClass => {
		elements.push( document.querySelector(`.${elementClass}`));
	});
	return elements;
}

export const updateNameNavBar = ( newName ) => {
	document.querySelector('.nombre-usuario').innerHTML= newName;
}

export const makeAFetch = async ( path,parameters ) => {
	const formData = new FormData();
	parameters.forEach(parameter => {
			formData.append(parameter[0], parameter[1]);
	});
	const response = await fetch(`${ dominio }/${ path }`,{
			method: 'POST',
			body: formData
		});
		const json = await response.json();
		return json;
}
