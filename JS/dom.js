
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
