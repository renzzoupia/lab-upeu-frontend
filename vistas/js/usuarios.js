

/*=============================================
SUBIENDO LA FOTO DEL USUARIO
=============================================*/
$(".nuevaFoto").change(function(){

	var imagen = this.files[0];
	
	/*=============================================
  	VALIDAMOS EL FORMATO DE LA IMAGEN SEA JPG O PNG
  	=============================================*/

  	if(imagen["type"] != "image/jpeg" && imagen["type"] != "image/png"){

  		$(".nuevaFoto").val("");

  		 swal({
		      title: "Error al subir la imagen",
		      text: "¡La imagen debe estar en formato JPG o PNG!",
		      type: "error",
		      confirmButtonText: "¡Cerrar!"
		    });

  	}else if(imagen["size"] > 2000000){

  		$(".nuevaFoto").val("");

  		 swal({
		      title: "Error al subir la imagen",
		      text: "¡La imagen no debe pesar más de 2MB!",
		      type: "error",
		      confirmButtonText: "¡Cerrar!"
		    });

  	}else{

  		var datosImagen = new FileReader;
  		datosImagen.readAsDataURL(imagen);

  		$(datosImagen).on("load", function(event){

  			var rutaImagen = event.target.result;

  			$(".previsualizar").attr("src", rutaImagen);

  		})

  	}
})

/*=============================================
EDITAR USUARIO
=============================================*/
$(".tablas").on("click", ".btnEditarUsuario", function(){

	var idUsuario = $(this).attr("idUsuario");

	var datos = new FormData();
	datos.append("idUsuario", idUsuario);


	var settings = {
		"url": `${CONFIG.API_BASE_URL}usuario/${idUsuario}`,
		"method": "GET",
		"timeout": 0,
	  };
	  
	  $.ajax(settings).done(function (response) {
		// Si la respuesta es una cadena de texto, conviértela a un objeto JSON
		if (typeof response === 'string') {
			response = JSON.parse(response);
		}
	
		console.log(response); // Verifica la estructura del JSON
	
		if (response && response.Detalles && response.Detalles.length > 0) {
			var detalles = response.Detalles[0];
			
			console.log(detalles.usua_id);
			console.log(detalles.usua_nombrecompleto);
			console.log(detalles.usua_username);
			console.log(detalles.usua_clave);
			console.log(detalles.usua_role_id);
			console.log(detalles.usua_dni);
			
			$("#usuaId").val(detalles.usua_id);
			$("#editarUsuaNombreCompleto").val(detalles.usua_nombrecompleto);
			$("#editarUsuaUsername").val(detalles.usua_username);
			$("#editarUsuaClave").val(detalles.usua_clave);
			$("#editarUsuaRoleId").val(detalles.usua_role_id);
			$("#editarUsuaDni").val(detalles.usua_dni);
		} else {
			console.error("La estructura del JSON no es la esperada o Detalles está vacío.");
		}
	  });
	/*

	var idUsuario = $(this).attr("idUsuario");
	
	var datos = new FormData();
	datos.append("idUsuario", idUsuario);

	$.ajax({

		url:"ajax/usuarios.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function(respuesta){
			
			$("#editarNombre").val(respuesta["nombre"]);
			$("#editarUsuario").val(respuesta["usuario"]);
			$("#editarPerfil").html(respuesta["perfil"]);
			$("#editarPerfil").val(respuesta["perfil"]);
			$("#fotoActual").val(respuesta["foto"]);

			$("#passwordActual").val(respuesta["password"]);

			if(respuesta["foto"] != ""){

				$(".previsualizarEditar").attr("src", respuesta["foto"]);

			}else{

				$(".previsualizarEditar").attr("src", "vistas/img/usuarios/default/anonymous.png");

			}

		}

	});
*/
})
/*=============================================
CONFIRMAR EDITAR USUARIO
=============================================*/
$(document).ready(function() {
    $("#formEditarUsuario").on("submit", function(event) {
        event.preventDefault(); // Evita que el formulario se envíe de forma tradicional

		var usuaId = $("#usuaId").val();
        var editarUsuaNombreCompleto = $("#editarUsuaNombreCompleto").val();
        var editarUsuaUsername = $("#editarUsuaUsername").val();
        var editarUsuaClave = $("#editarUsuaClave").val();
        var editarUsuaDni = $("#editarUsuaDni").val();

        // Configura los datos y la solicitud AJAX
        var settings = {
			"url": `${CONFIG.API_BASE_URL}usuario/${usuaId}`,
			"method": "PUT",
			"timeout": 0,
			"headers": {
			  "Content-Type": "application/x-www-form-urlencoded",
			  "Authorization": CONFIG.API_AUTH_HEADER
			},
			"data": {
			  "usua_nombrecompleto": editarUsuaNombreCompleto,
			  "usua_username": editarUsuaUsername,
			  "usua_clave": editarUsuaClave,
			  "usua_role_id": "3",
			  "usua_dni": editarUsuaDni
			},
			success: function(response) {
                console.log("Edición exitosa:", response);
                // Aquí puedes agregar el código para actualizar la interfaz de usuario según sea necesario
                swal({
					type: "success",
					title: "Usuario ha sido modifficado correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
						if (result.value) {

						window.location = "usuarios";

						}
					})
                // Opcional: actualizar la lista de categorías o hacer algo más después de una edición exitosa
            },
            error: function(xhr, status, error) {
                console.error("Error en editar usuario:", error);
                swal({
					type: "error",
					title: "¡Usuario no puede ir vacía o llevar caracteres especiales!",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
					  if (result.value) {

					  window.location = "usuarios";

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
GUARDAR USUARIO
=============================================*/
$(document).ready(function() {
    $("#formRegistrarUsuario").on("submit", function(event) {
        event.preventDefault(); // Evita que el formulario se envíe de forma tradicional

        // Captura los valores de los campos
        var usua_nombrecompleto = $("#nuevoUsuaNombreCompleto").val();
        var usua_username = $("#nuevoUsuaUsername").val();
        var usua_clave = $("#nuevoUsuaClave").val();
        var usua_role_id = $("#nuevoUsuaRoleId").val();
        var usua_dni = $("#nuevoUsuaDni").val();

        // Configura los datos y la solicitud AJAX
        var settings = {
			"url": `${CONFIG.API_BASE_URL}usuario`,
			"method": "POST",
			"timeout": 0,
			"headers": {
				"Content-Type": "application/x-www-form-urlencoded"
			},
			"data": {
				"usua_nombrecompleto": usua_nombrecompleto,
				"usua_username": usua_username,
				"usua_clave": usua_clave,
				"usua_role_id": "3",
				"usua_dni": usua_dni
			},
			success: function(response) {
                console.log("Registro exitosa:", response);
                // Aquí puedes agregar el código para actualizar la interfaz de usuario según sea necesario
                swal({
					type: "success",
					title: "Usuario ha sido guardada correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
						if (result.value) {

						window.location = "usuarios";

						}
				})
                // Opcional: actualizar la lista de categorías o hacer algo más después de una edición exitosa
            },
            error: function(xhr, status, error) {
                console.error("Error en crear:", error);
                swal({
					type: "error",
					title: "¡Usuario no puede ir vacía o llevar caracteres especiales!",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
					  if (result.value) {

					  window.location = "usuarios";

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
ACTIVAR USUARIO
=============================================*/
$(".tablas").on("click", ".btnActivar", function(){

	var idUsuario = $(this).attr("idUsuario");
	var estadoUsuario = $(this).attr("estadoUsuario");

	var datos = new FormData();
 	datos.append("activarId", idUsuario);
  	datos.append("activarUsuario", estadoUsuario);

  	$.ajax({

	  url:"ajax/usuarios.ajax.php",
	  method: "POST",
	  data: datos,
	  cache: false,
      contentType: false,
      processData: false,
      success: function(respuesta){

      		if(window.matchMedia("(max-width:767px)").matches){

	      		 swal({
			      title: "El usuario ha sido actualizado",
			      type: "success",
			      confirmButtonText: "¡Cerrar!"
			    }).then(function(result) {
			        if (result.value) {

			        	window.location = "usuarios";

			        }


				});

	      	}

      }

  	})

  	if(estadoUsuario == 0){

  		$(this).removeClass('btn-success');
  		$(this).addClass('btn-danger');
  		$(this).html('Desactivado');
  		$(this).attr('estadoUsuario',1);

  	}else{

  		$(this).addClass('btn-success');
  		$(this).removeClass('btn-danger');
  		$(this).html('Activado');
  		$(this).attr('estadoUsuario',0);

  	}

})

/*=============================================
REVISAR SI EL USUARIO YA ESTÁ REGISTRADO
=============================================*/

$("#nuevoUsuario").change(function(){

	$(".alert").remove();

	var usuario = $(this).val();

	var datos = new FormData();
	datos.append("validarUsuario", usuario);

	 $.ajax({
	    url:"ajax/usuarios.ajax.php",
	    method:"POST",
	    data: datos,
	    cache: false,
	    contentType: false,
	    processData: false,
	    dataType: "json",
	    success:function(respuesta){
	    	
	    	if(respuesta){

	    		$("#nuevoUsuario").parent().after('<div class="alert alert-warning">Este usuario ya existe en la base de datos</div>');

	    		$("#nuevoUsuario").val("");

	    	}

	    }

	})
})

/*=============================================
ELIMINAR USUARIO
=============================================*/

$(".tablas").on("click", ".btnEliminarUsuario", function(){
	var eliminarUsuaId = $(this).attr("eliminarUsuaId");
	console.log(eliminarUsuaId);

    swal({
        title: '¿Está seguro de borrar usuario?',
        text: "¡Si no lo está puede cancelar la acción!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar usuario!'
    }).then(function(result){
		
        if(result.value){
            var settings = {
				"url": `${CONFIG.API_BASE_URL}usuario/${eliminarUsuaId}`,
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
					title: "Usuario ha sido eliminado correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
						if (result.value) {

						window.location = "usuarios";

						}
					})
              });

        }

    })

})




