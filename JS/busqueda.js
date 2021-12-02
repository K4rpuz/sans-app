
import { loadForm } from './forms.js'; 
import { path } from './config.js';

document.addEventListener('DOMContentLoaded', () => {
	
	const [ dataForm , postTo ] = loadForm('barra-busqueda', async () => {
		const contenedorProductos = document.querySelector('.barra-busqueda__resultados');
		if( dataForm.valores.search === ''){
			contenedorProductos.innerHTML = "";
			return;
		}
		const response = await postTo( path.busqueda );		
		if(response['ANSWER'] !== 'OK') return;
		contenedorProductos.innerHTML="";	
		const productos = response['Productos']; 	
		productos.forEach(producto => {
			const divProducto = document.createElement("div");
			const p = document.createElement('p');
			p.innerHTML = producto;
			divProducto.appendChild(p);
			divProducto.classList.add('barra-busqueda__resultados__producto');
			contenedorProductos.appendChild(divProducto);	
		});
	});	
});
