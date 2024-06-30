/*=============================================
EDITAR PRODUCTO
=============================================*/

$(".tablas").on("click", ".btnEditarProducto", function(){
	var prodId = $(this).attr("prodId");
	console.log(prodId);
	var settings = {
		"url": `${CONFIG.API_BASE_URL}producto/${prodId}`,
		"method": "GET",
		"timeout": 0,
		"headers": {
			"Authorization": CONFIG.API_AUTH_HEADER
		},
	  };
	  
	  $.ajax(settings).done(function (response) {
		// Si la respuesta es una cadena de texto, conviértela a un objeto JSON
		if (typeof response === 'string') {
			response = JSON.parse(response);
		}
	
		console.log(response); // Verifica la estructura del JSON
	
		if (response && response.Detalles && response.Detalles.length > 0) {
			var detalles = response.Detalles[0];
			console.log(detalles.prod_nombre);
			console.log(detalles.prod_codigoinventario);
			console.log(detalles.prod_tipr_id);
			
			$("#prodId").val(detalles.prod_id);
			$("#editarProdNombre").val(detalles.prod_nombre);
			$("#editarProdCodigoinventario").val(detalles.prod_codigoinventario);
			$("#editarProdTiprId").val(detalles.prod_tipr_id);
			$("#editarProdDescripcion").val(detalles.prod_descripcion);
			$("#editarProdMarca").val(detalles.prod_marca);
			$("#editarProdModelo").val(detalles.prod_modelo);
			$("#editarProdEspecificacion").val(detalles.prod_especificaciones);
			$("#editarProdUbicacion").val(detalles.prod_ubicacion);
			// Encuentra la opción seleccionada y actualiza su texto
			$("#editarProdTiprId option:selected").text(detalles.tipr_nombre);
		} else {
			console.error("La estructura del JSON no es la esperada o Detalles está vacío.");
		}
	  });
})
/*=============================================
CONFIRMAR EDITAR PRODUCTO
=============================================*/
$(document).ready(function() {
    $("#formEditarProducto").on("submit", function(event) {
        event.preventDefault(); // Evita que el formulario se envíe de forma tradicional

		var prodId = $("#prodId").val();
        var editarProdNombre = $("#editarProdNombre").val();
        var editarProdCodigoinventario = $("#editarProdCodigoinventario").val();
        var editarProdTiprId = $("#editarProdTiprId").val();
		var editarProdDescripcion = $("#editarProdDescripcion").val();
		var editarProdMarca = $("#editarProdMarca").val();
		var editarProdModelo = $("#editarProdModelo").val();
		var editarProdEspecificacion = $("#editarProdEspecificacion").val();
		var editarProdUbicacion = $("#editarProdUbicacion").val();

        // Configura los datos y la solicitud AJAX
        var settings = {
			"url": `${CONFIG.API_BASE_URL}producto/${prodId}`,
			"method": "PUT",
			"timeout": 0,
			"headers": {
			  "Content-Type": "application/x-www-form-urlencoded",
			  "Authorization": CONFIG.API_AUTH_HEADER
			},
			"data": {
			  "prod_nombre": editarProdNombre,
			  "prod_codigoinventario": editarProdCodigoinventario,
			  "prod_tipr_id": editarProdTiprId,
			  "prod_descripcion": editarProdDescripcion,
			  "prod_marca": editarProdMarca,
			  "prod_modelo": editarProdModelo,
			  "prod_especificaciones": editarProdEspecificacion,
			  "prod_ubicacion": editarProdUbicacion,
			},
			success: function(response) {
                console.log("Edición exitosa:", response);
                // Aquí puedes agregar el código para actualizar la interfaz de usuario según sea necesario
                swal({
					type: "success",
					title: "Producto ha sido modifficado correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
						if (result.value) {

						window.location = "productos";

						}
					})
                // Opcional: actualizar la lista de categorías o hacer algo más después de una edición exitosa
            },
            error: function(xhr, status, error) {
                console.error("Error en editar Producto:", error);
                swal({
					type: "error",
					title: "Producto no puede ir vacía o llevar caracteres especiales!",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
					  if (result.value) {

					  window.location = "productos";

					  }
				})
            }
			
		  };
		  $.ajax(settings).done(function (response) {
			console.log(response);
		  });
		  
    });
});
/*=============================================
GUARDAR PRODUCTO
=============================================*/
$(document).ready(function() {
    $("#formRegistrarProducto").on("submit", function(event) {
        event.preventDefault(); // Evita que el formulario se envíe de forma tradicional

        // Captura los valores de los campos
        var nuevoProdNombre = $("#nuevoProdNombre").val();
        var nuevoProdCodigoinventario = $("#nuevoProdCodigoinventario").val();
        var nuevoProdTiprId = $("#nuevoProdTiprId").val();
		var nuevoProdDescripcion = $("#nuevoProdDescripcion").val();
		var nuevoProdMarca = $("#nuevoProdMarca").val();
		var nuevoProdModelo = $("#nuevoProdModelo").val();
		var nuevoProdEspecificacion = $("#nuevoProdEspecificacion").val();
		var nuevoProdUbicacion = $("#nuevoProdUbicacion").val();
		console.log(nuevoProdNombre);
		console.log(nuevoProdCodigoinventario);
		console.log(nuevoProdTiprId);

        // Configura los datos y la solicitud AJAX
        var settings = {
			"url": `${CONFIG.API_BASE_URL}producto`,
			"method": "POST",
			"timeout": 0,
			"headers": {
				"Content-Type": "application/x-www-form-urlencoded",
				"Authorization": CONFIG.API_AUTH_HEADER
			},
			"data": {
				"prod_nombre": nuevoProdNombre,
				"prod_codigoinventario": nuevoProdCodigoinventario,
				"prod_tipr_id": nuevoProdTiprId,
				"prod_descripcion": nuevoProdDescripcion,
				"prod_marca": nuevoProdMarca,
				"prod_modelo": nuevoProdModelo,
				"prod_especificaciones": nuevoProdEspecificacion,
				"prod_ubicacion": nuevoProdUbicacion
			},
			success: function(response) {
                console.log("Registro exitosa:", response);
                // Aquí puedes agregar el código para actualizar la interfaz de usuario según sea necesario
                swal({
					type: "success",
					title: "Producto ha sido guardado correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
						if (result.value) {

						window.location = "productos";

						}
				})
                // Opcional: actualizar la lista de categorías o hacer algo más después de una edición exitosa
            },
            error: function(xhr, status, error) {
                console.error("Error en crear:", error);
                swal({
					type: "error",
					title: "Producto no puede ir vacía o llevar caracteres especiales!",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
					  if (result.value) {

					  window.location = "productos";

					  }
				})
            }
			
		  };
		  
		  $.ajax(settings).done(function (response) {
			console.log(response);
		  });
    });
});

/*=============================================
ELIMINAR PRODUCTO
=============================================*/

$(".tablas").on("click", ".btnEliminarProducto", function(){
	var eliminarProdId = $(this).attr("eliminarProdId");
	console.log(eliminarProdId);

    swal({
        title: '¿Está seguro de borrar producto?',
        text: "¡Si no lo está puede cancelar la acción!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar Producto!'
    }).then(function(result){
		
        if(result.value){
			console.log(eliminarProdId);
            var settings = {
				"url": `${CONFIG.API_BASE_URL}producto/${eliminarProdId}`,
                "method": "DELETE",
                "timeout": 0,
                "headers": {
                  "Authorization": CONFIG.API_AUTH_HEADER
                },
              };
              
              $.ajax(settings).done(function (response) {
                console.log(response);
                swal({
					type: "success",
					title: "Este producto ha sido eliminado correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
						if (result.value) {

						window.location = "productos";

						}
					})
              });

        }

    })

})
	
