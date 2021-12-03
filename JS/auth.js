import { hideElement, loadElements, showElement } from './dom.js';
import {loadForm} from './forms.js';
import { path, dominio } from './config.js';

document.addEventListener("DOMContentLoaded", () => {
	const [ loginContainer,registerContainer, formLogin, formRegister, parrafoError ] = loadElements([
		'login','register','form-login','form-register', 'error'
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

	const showError = ( error ) => {
		parrafoError.textContent = error;
		setTimeout(() => {
			parrafoError.textContent = "";
		},2000);
	}

	const [ infoLogin, postLogin ] = loadForm('form-login',() => {});

	formLogin.addEventListener('submit', async (e) => {
		e.preventDefault();
		const resp = await postLogin(path.login);
		if( resp['ANSWER'] === 'OK' )	window.location.replace(`${dominio}/index.php`);
		else showError( 'Rol o contraseña incorrecta' );
	});

	const [ infoRegister, postRegister ] = loadForm('form-register',()=>{});
	
	formRegister.addEventListener('submit', async (e) => {
		e.preventDefault();
		const resp = await postRegister(path.register);
		if( resp['ANSWER'] === 'OK' ) window.location.replace(`${dominio}/index.php`);
		else showError('El rol ingresado está en uso');
	});

});
