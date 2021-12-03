
document.addEventListener("DOMContentLoaded", ()=>{

	for(let input of document.getElementsByClassName("input_cantidad_producto")){
		input.onchange = ()=>{
			let [stock,cantidad,id] = input.id.split(",");
			let value = parseInt(input.value);
			if(value > parseInt(stock)){
				input.value = cantidad;
			}else if(value < 1){
				input.value = cantidad;
			}else{
				document.getElementById("form-cantidad-producto_"+id).submit();
			}
			
		};
	}

});
