import { hideElement, loadElements, showElement } from './dom.js';

document.addEventListener("DOMContentLoaded", () => {
	const [ loginContainer,registerContainer ] = loadElements([
		'login','register'
	]);

	hideElement(registerContainer);

	document.querySelector('#toggle-auth').addEventListener("click", ( { target } ) => {
		let container;
		if( target.textContent[0] === 'R' ){
			target.textContent = "Login";
			container = loginContainer;	
			showElement(registerContainer);
		}else{
			target.textContent = "Registrarse";
			container = registerContainer;
			showElement(loginContainer);
		}
		hideElement(container);
	});
});
