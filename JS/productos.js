import {loadControlCantidad} from "./dom.js";

document.addEventListener('DOMContentLoaded', () => {
	const [ cantidad, sendCantidad ] = loadControlCantidad( 'control-cantidad' );
	console.log(cantidad, sendCantidad);
});
