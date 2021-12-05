import { loadForm } from './forms.js';
import { loadElements } from './dom.js';
document.addEventListener("DOMContentLoaded", ()=>{
	if( document.querySelector('.contenedor-form') !== undefined ){
		const [ divCalificaciones ] = loadElements([ 'comentario-calificacion' ]);
		const [ dataComentario, postComentario ] = loadForm( 'comentario-form', () => {
			divCalificaciones.innerHTML = "";
			let inner = "";
			for(let i = 0; i < dataComentario.valores.calificacion; ++i){
				inner += '<img src="../IMG/estrella.png"class="img-calificacion"></img>';
			}
			divCalificaciones.innerHTML = inner;
		} );
	}
});
