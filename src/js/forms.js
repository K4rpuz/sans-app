
import { dominio } from './config.js';

const bloquearCopiadoPasswordField = () => {
	const campos =	document.querySelectorAll('input[type="password"]');
	campos.forEach(campo => {
		campo.oncopy = e => e.preventDefault();
		campo.onpaste = e => e.preventDefault();
	});
}

export const loadForm = ( nombreForm, onChangeCallback ) => {
	
	const inputs = document.querySelectorAll(`.${ nombreForm } input`);

	const data = {
		valores : {
		}
	}
	const handlerChangeInput = ({ target }) => {
		data.valores = {
			... data.valores,
			[ target.name ]: target.value
		}	
		onChangeCallback();
	}

	inputs.forEach( input => {
		if(input.type == 'submit') return;
		input.addEventListener('input', handlerChangeInput);	
	});
	const postTo = async ( path ) => {
		const formData = new FormData();
		inputs.forEach(input => {
			if(input.type=='submit') return;
			formData.append(input.name, input.value);
		});
		const response = await fetch(`${ dominio }/${ path }`,{
			method: 'POST',
			body: formData
		});
		const json = await response.json();
		return json;
	}

	return [ data, postTo ];
}

document.addEventListener('DOMContentLoaded', async () => {
	bloquearCopiadoPasswordField();
});
