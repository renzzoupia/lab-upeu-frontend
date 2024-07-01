/*=============================================
ASIGNAR LABORATORIO
=============================================*/
$(".tablas").on("click", ".btnAsignarLaboratorio", function(){

	var asignarLaboId = $(this).attr("asignarLaboId");
	console.log("xd" + asignarLaboId);

	
	var settings = {
		"url": `${CONFIG.API_BASE_URL}laboratorio/${asignarLaboId}`,
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
			console.log(detalles.labo_nombre);
			console.log(detalles.labo_descripcion);
			console.log(detalles.labo_escu_id);
			
			$("#editarUslaLaboId").val(detalles.labo_id);
			$("#asignarLaboNombre").val(detalles.labo_nombre);
		} else {
			console.error("La estructura del JSON no es la esperada o Detalles está vacío.");
		}
	  });
})

/*=============================================
CONFIRMAR LA SIGNACION USUARIO LABORATORIO
=============================================*/
$(document).ready(function() {
    $("#formAsignarLaboratorio").on("submit", function(event) {
        event.preventDefault(); // Evita que el formulario se envíe de forma tradicional

		var editarUsLaLaboId = $("#editarUslaLaboId").val();
		var asignarUsuarioId = $("#asignarUsuarioId").val();
		var asignarFechaInicio = $("#asignarFechaInicio").val();
		var asignarFechaFin = $("#asignarFechaFin").val();
        // Configura los datos y la solicitud AJAX
        var settings = {
			"url": `${CONFIG.API_BASE_URL}UsuarioLaboratorio`,
			"method": "POST",
			"timeout": 0,
			"headers": {
			  "Content-Type": "application/x-www-form-urlencoded",
			  "Authorization": CONFIG.API_AUTH_HEADER
			},
			"data": {
			  "usla_usua_id": asignarUsuarioId,
			  "usla_labo_id": editarUsLaLaboId,
			  "usla_periodo_inicio": asignarFechaInicio,
			  "usla_periodo_fin": asignarFechaFin
			},
			success: function(response) {
                console.log("Asignacion exitosa:", response);
                // Aquí puedes agregar el código para actualizar la interfaz de usuario según sea necesario
                swal({
					type: "success",
					title: "Asignado correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
						if (result.value) {

						window.location = "laboratorios";

						}
					})
                // Opcional: actualizar la lista de categorías o hacer algo más después de una edición exitosa
            },
            error: function(xhr, status, error) {
                console.error("Error en editar laboratorio:", error);
                swal({
					type: "error",
					title: "Laboratorio no puede ir vacía o llevar caracteres especiales!",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
					  if (result.value) {

					 window.location = "laboratorios";

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
EDITAR LABORATORIO
=============================================*/
$(".tablas").on("click", ".btnEditarLaboratorio", function(){

	var laboId = $(this).attr("laboId");
	console.log(laboId);
	var settings = {
		"url": `${CONFIG.API_BASE_URL}laboratorio/${laboId}`,
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
			console.log(detalles.labo_nombre);
			console.log(detalles.labo_descripcion);
			console.log(detalles.labo_escu_id);
			
			$("#laboId").val(detalles.labo_id);
			$("#editarLaboNombre").val(detalles.labo_nombre);
			$("#editarLaboDescripcion").val(detalles.labo_descripcion);
			$("#editarLaboEscuId").val(detalles.labo_escu_id);
			// Encuentra la opción seleccionada y actualiza su texto
			$("#editarLaboEscuId option:selected").text(detalles.escu_nombre);
		} else {
			console.error("La estructura del JSON no es la esperada o Detalles está vacío.");
		}
	  });
})
/*=============================================
CONFIRMAR EDITAR LABORATORIO
=============================================*/
$(document).ready(function() {
    $("#formEditarLaboratorio").on("submit", function(event) {
        event.preventDefault(); // Evita que el formulario se envíe de forma tradicional

		var laboId = $("#laboId").val();
        var editarLaboNombre = $("#editarLaboNombre").val();
        var editarLaboDescripcion = $("#editarLaboDescripcion").val();
        var editarLaboEscuId = $("#editarLaboEscuId").val();

        // Configura los datos y la solicitud AJAX
        var settings = {
			"url": `${CONFIG.API_BASE_URL}laboratorio/${laboId}`,
			"method": "PUT",
			"timeout": 0,
			"headers": {
			  "Content-Type": "application/x-www-form-urlencoded",
			  "Authorization": CONFIG.API_AUTH_HEADER
			},
			"data": {
			  "labo_nombre": editarLaboNombre,
			  "labo_descripcion": editarLaboDescripcion,
			  "labo_escu_id": editarLaboEscuId
			},
			success: function(response) {
                console.log("Edición exitosa:", response);
                // Aquí puedes agregar el código para actualizar la interfaz de usuario según sea necesario
                swal({
					type: "success",
					title: "Laboratorio ha sido modifficado correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
						if (result.value) {

						window.location = "laboratorios";

						}
					})
                // Opcional: actualizar la lista de categorías o hacer algo más después de una edición exitosa
            },
            error: function(xhr, status, error) {
                console.error("Error en editar laboratorio:", error);
                swal({
					type: "error",
					title: "Laboratorio no puede ir vacía o llevar caracteres especiales!",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
					  if (result.value) {

					  window.location = "laboratorios";

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
GUARDAR LABORATORIO
=============================================*/
$(document).ready(function() {
    $("#formRegistrarLaboratorio").on("submit", function(event) {
        event.preventDefault(); // Evita que el formulario se envíe de forma tradicional

        // Captura los valores de los campos
        var labo_nombre = $("#nuevoLaboNombre").val();
        var labo_descripcion = $("#nuevoLaboDescripcion").val();
        var labo_escu_id = $("#laboEscuId").val();
		console.log(labo_nombre);
		console.log(labo_descripcion);
		console.log(labo_escu_id);

        // Configura los datos y la solicitud AJAX
        var settings = {
			"url": `${CONFIG.API_BASE_URL}laboratorio`,
			"method": "POST",
			"timeout": 0,
			"headers": {
				"Content-Type": "application/x-www-form-urlencoded",
				"Authorization": CONFIG.API_AUTH_HEADER
			},
			"data": {
				"labo_nombre": labo_nombre,
				"labo_descripcion": labo_descripcion,
				"labo_escu_id": labo_escu_id
			},
			success: function(response) {
				// Asegurarse de que la respuesta es un objeto JSON
				var parsedResponse;
				try {
					parsedResponse = typeof response === "object" ? response : JSON.parse(response);
				} catch (e) {
					console.error("Error al parsear la respuesta de la API:", e);
					swal({
						type: "error",
						title: "Error inesperado en la respuesta del servidor",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result) {
						if (result.value) {
							window.location = "laboratorios";
						}
					});
					return;
				}
		
				console.log("Respuesta de la API:", parsedResponse);
		
				if (parsedResponse.Status === 200) {
					swal({
						type: "success",
						title: "Laboratorio ha sido guardado correctamente",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result) {
						if (result.value) {
							window.location = "laboratorios";
						}
					});
				} else {
					swal({
						type: "error",
						title: "Laboratorio no puede ir vacío o llevar caracteres especiales!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result) {
						if (result.value) {
							window.location = "laboratorios";
						}
					});
				}
			},
			error: function(xhr, status, error) {
				console.error("Error en crear:", error);
				swal({
					type: "error",
					title: "Laboratorio no puede ir vacío o llevar caracteres especiales!",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
				}).then(function(result) {
					if (result.value) {
						window.location = "laboratorios";
					}
				});
			}
		};
		
		$.ajax(settings);
		
		  
		  $.ajax(settings).done(function (response) {
			console.log(response);
		  });
    });
});

/*=============================================
ELIMINAR LABORATORIO
=============================================*/
//$(".tablas").on("click", ".btnEditarLaboratorio", function(){

$(".tablas").on("click", ".btnEliminarLaboratorio", function(){
	var eliminarLaboId = $(this).attr("eliminarLaboId");
	console.log(eliminarLaboId);

    swal({
        title: '¿Está seguro de borrar laboratorio?',
        text: "¡Si no lo está puede cancelar la acción!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar laboratorio!'
    }).then(function(result){
		
        if(result.value){
			console.log(eliminarLaboId);
            var settings = {
				"url": `${CONFIG.API_BASE_URL}laboratorio/${eliminarLaboId}`,
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
					title: "Laboratorio ha sido eliminado correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
						if (result.value) {

						window.location = "laboratorios";

						}
					})
              });

        }

    })

})
/*
$(".tablas").on("click", ".btnEditarLaboratorio", function(){
        event.preventDefault(); // Evita que el formulario se envíe de forma tradicional

		var laboId = $("#laboId").val();

        // Configura los datos y la solicitud AJAX
        var settings = {
			"url": `http://localhost:8080/lab-backend/laboratorio/${laboId}`,
			"method": "DELETE",
			"timeout": 0,
			"headers": {
			  "Content-Type": "application/x-www-form-urlencoded",
			  "Authorization": "Basic JDJhJDA3JGRmaGRmcmV4ZmhnZGZoZGZlcnR0Z2VmcUxLVU9jb0VKM3lLNWx1LjNRSUVQMmlsRzN0VjUuOiQyYSQwNyRkZmhkZnJleGZoZ2RmaGRmZXJ0dGdlZWUvaExXSXFVT0hpZVNiZmhmQm5HSU9sMERaS1gvSw=="
			},
			success: function(response) {
                console.log("Edición exitosa:", response);
                // Aquí puedes agregar el código para actualizar la interfaz de usuario según sea necesario
                swal({
					type: "success",
					title: "Laboratorio ha sido modifficado correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
						if (result.value) {

						window.location = "laboratorios";

						}
					})
                // Opcional: actualizar la lista de categorías o hacer algo más después de una edición exitosa
            },
            error: function(xhr, status, error) {
                console.error("Error en editar laboratorio:", error);
                swal({
					type: "error",
					title: "Laboratorio no puede ir vacía o llevar caracteres especiales!",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
					  if (result.value) {

					  window.location = "laboratorios";

					  }
				})
            }
			
		  };
		  $.ajax(settings).done(function (response) {
			console.log(response);
		  });
		  
});*/