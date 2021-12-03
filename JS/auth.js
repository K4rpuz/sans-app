import { hideElement, loadElements, showElement } from './dom.js';
import {loadForm} from './forms.js';
import { path } from './config.js';

document.addEventListener("DOMContentLoaded", () => {
	const [ loginContainer,registerContainer, formLogin, formRegister ] = loadElements([
		'login','register','form-login','form-register'
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

	const [ infoLogin, postLogin ] = loadForm('form-login',() => {});

	formLogin.addEventListener('submit', async (e) => {
		e.preventDefault();
		const resp = await postLogin(path.login);
		console.log(resp);
	});

	const [ infoRegister, postRegister ] = loadForm('form-register',()=>{});
	
	formRegister.addEventListener('submit', async (e) => {
		e.preventDefault();
		const resp = await postRegister(path.register);
		console.log(resp);
	});

});
