
import { dominio , path } from "./config.js";
import {loadForm} from "./forms.js";

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
export const loadControlCantidad = ( nombre ) => {

	const [ controlCantidad, botonSumar, botonRestar, maxEl, minEl ] = loadElements( [`${nombre} .display-cantidad`,`${nombre} .boton-sumar`,`${nombre} .boton-restar`, `${nombre} input[name="max"]`, `${nombre} input[name="min"]`] );

	const min = minEl.value;
	const max = maxEl.value;
	const [ dataCantidad, enviar ] = loadForm( nombre, ()=>{} );

	let count = controlCantidad.value;

	const sumar = ( e ) => {
		e.preventDefault();
		++count;
		if( count > max ) {
			count = max;
			botonSumar.disabled = true;
		}
		controlCantidad.value = count;
		botonRestar.disabled = false;
	}

	const restar = ( e ) => {
		e.preventDefault();
		--count;
		if( count < min ) {
			count = min;
			botonRestar.disabled = true;
		}
		controlCantidad.value = count;
		botonSumar.disabled = false;
	}

	botonSumar.addEventListener('click',sumar);
	botonRestar.addEventListener('click',restar);

	return [ count, enviar ];

}
