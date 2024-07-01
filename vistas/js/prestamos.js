/*=============================================
EDITAR PRESTAMO
=============================================*/
$(".tablas").on("click", ".btnDevolverPrestamo", function(){

	var presId = $(this).attr("devolverPresId");
	console.log(presId);
	var settings = {
		"url": `${CONFIG.API_BASE_URL}prestamo/${presId}`,
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
			
			$("#editarPresId").val(detalles.pres_id);
			$("#editarInveId").val(detalles.pres_inve_id);
			$("#editarNombreAlumno").val(detalles.pres_nombre_alumno);
			$("#editarInveNombreId").val(detalles.prod_nombre);
			//$("#editarLaboDescripcion").val(detalles.pres_usua_id);
			$("#editarPresCantidad").val(detalles.pres_cantidad);
            $("#editarPresCodigouniAlumno").val(detalles.pres_codigouni_alumno);
            //$("#editarLaboEscuId").val(detalles.pres_evidencia);

            // Transformar la fecha de inicio al formato yyyy-MM-dd
			var fechaDeSolicitud = detalles.pres_fechasolicitud.split(" ")[0];
			$("#editarPresFechasolicitud").val(fechaDeSolicitud);

            // Transformar la fecha de inicio al formato yyyy-MM-dd
			var fechaDeEntregado = detalles.pres_fechaentregado.split(" ")[0];
			$("#editarFechaentregado").val(fechaDeEntregado);

            // Transformar la fecha de inicio al formato yyyy-MM-dd
			var fechaDeDevolucion = detalles.pres_fechadevolucion.split(" ")[0];
			$("#editarPresFechadevolucion").val(fechaDeDevolucion);

            // Transformar la fecha de inicio al formato yyyy-MM-dd
			var fechaDeRealdevolucion = detalles.pres_fecharealdevolucion.split(" ")[0];
			$("#editarPresFechadevolucion").val(fechaDeRealdevolucion);

            $("#editarPresObservacion").val(detalles.pres_observacion);
            $("#editarPresEstado").val(detalles.pres_estado);
			$("#imagenPrevisualizar").attr('src', val(detalles.pres_evidencia));
			//$("#editarPresEstado").val(detalles.pres_evidencia);
			// Encuentra la opción seleccionada y actualiza su texto
			$("#editarInveId option:selected").text(detalles.prod_nombre);
		} else {
			console.error("La estructura del JSON no es la esperada o Detalles está vacío.");
		}
	  });
})

/*=============================================
CONFIRMAR DEVOLUCION PRESTAMO EDITAR
=============================================*/
$(document).ready(function() {
    $("#formDevolverPrestamo").on("submit", function(event) {
		
        event.preventDefault(); // Evita que el formulario se envíe de forma tradicional
		var form = new FormData();
		form.append("pres_inve_id", $("#editarInveId").val());
		form.append("pres_nombre_alumno", $("#editarNombreAlumno").val());
		form.append("pres_cantidad", $("#editarPresCantidad").val());
		form.append("pres_codigouni_alumno", $("#editarPresCodigouniAlumno").val());
		if (editarImagen && editarImagen.files[0]) {
            form.append("pres_evidencia", editarImagen.files[0]);
        }
		form.append("pres_fechasolicitud", $("#editarPresFechasolicitud").val());
		form.append("pres_fechaentregado", $("#editarFechaentregado").val());
		form.append("pres_fechadevolucion", $("#editarFechaentregado").val());
		form.append("pres_fecharealdevolucion", $("#editarPresFecharealdevolucion").val());
		form.append("pres_observacion", $("#editarPresObservacion").val());
		form.append("pres_estado", "Devuelto");
		form.append("id", $("#editarPresId").val());

		var settings = {
			"url": `${CONFIG.API_BASE_URL}prestamo/update`,
			"method": "POST",
			"timeout": 0,
			"headers": {
				"Authorization": CONFIG.API_AUTH_HEADER,
			},
			"processData": false,
			"mimeType": "multipart/form-data",
			"contentType": false,
			"data": form
		};

		$.ajax(settings).done(function (response) {
			console.log("Actualización exitosa:", response);
            swal({
                type: "success",
                title: "El prestamo ha sido actualizado correctamente",
                showConfirmButton: true,
                confirmButtonText: "Cerrar"
            }).then(function(result){
                if (result.value) {
                    window.location = "prestamos";
                }
            })
		}).fail(function(xhr, status, error) {
			console.error("Error en actualizar:", error);
            swal({
                type: "error",
                title: "Error al actualizar el préstamo!",
                showConfirmButton: true,
                confirmButtonText: "Cerrar"
            }).then(function(result){
                if (result.value) {
                    window.location = "prestamos";
                }
            })
        });;
    });
});
/*=============================================
GUARDAR PRESTAMO
=============================================*/
$(document).ready(function() {
    $("#formRegistrarPrestamo").on("submit", function(event) {
        event.preventDefault(); // Evita que el formulario se envíe de forma tradicional

        var fileInput = $("#fileInput")[0];

        if (!fileInput || !fileInput.files || !fileInput.files[0]) {
            console.error("No se ha seleccionado ningún archivo.");
            return;
        }

        var form = new FormData();
        form.append("pres_inve_id", $("#nuevoInveId").val());
        form.append("pres_nombre_alumno", $("#presNombreAlumno").val());
        form.append("pres_cantidad", $("#nuevoPresCantidad").val());
        form.append("pres_codigouni_alumno", $("#presCodigouniAlumno").val());
        form.append("pres_evidencia", fileInput.files[0]);
        form.append("pres_fechasolicitud", $("#nuevoPresFechasolicitud").val());
        form.append("pres_fechaentregado", $("#nuevoPresFechasolicitud").val());
        form.append("pres_fechadevolucion", $("#nuevoPresFechadevolucion").val());
        form.append("pres_observacion", $("#nuevoPresObservacion").val());
        form.append("pres_estado", "Prestado");
        
        var settings = {
			"url": `${CONFIG.API_BASE_URL}prestamo/create`,
          	"method": "POST",
          	"timeout": 0,
          	"headers": {
            "Authorization": CONFIG.API_AUTH_HEADER
			},
			"processData": false,
			"mimeType": "multipart/form-data",
			"contentType": false,
			"data": form
        };
        
        $.ajax(settings).done(function(response) {
            console.log("Registro exitosa:", response);
                // Aquí puedes agregar el código para actualizar la interfaz de usuario según sea necesario
                swal({
					type: "success",
					title: "El prestamo ha sido guardado correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
						if (result.value) {

						window.location = "prestamos";

						}
				})

        }).fail(function(xhr, status, error) {
            console.error("Error en crear:", error);
                swal({
					type: "error",
					title: "El prestamo no puede ir vacía o llevar caracteres especiales!",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
					  if (result.value) {

					  window.location = "prestamos";

					  }
				})
        });

    });
});

/*=============================================
ELIMINAR PRESTAMO
=============================================*/
//$(".tablas").on("click", ".btnEditarLaboratorio", function(){

$(".tablas").on("click", ".btnEliminarPrestamo", function(){
	var eliminarPresId = $(this).attr("eliminarPresId");
	console.log(eliminarPresId);

    swal({
        title: '¿Está seguro de borrar el prestamo?',
        text: "¡Si no lo está puede cancelar la acción!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar prestamo!'
    }).then(function(result){
		
        if(result.value){
            var settings = {
				"url": `${CONFIG.API_BASE_URL}prestamo/${eliminarPresId}`,
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
					title: "Prestamo ha sido eliminado correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
						if (result.value) {

						window.location = "prestamos";

						}
					})
              });

        }

    })

})

/*=============================================
IMPRIMIR FACTURA
=============================================*/

$(document).ready(function() {
    // Escuchar clic directamente en el botón
    $(".btnImprimirReportePrestamo").on("click", function() {
        var codigoVenta = $(this).attr("inveId"); // Asegúrate de que el atributo inveId esté presente en el botón

        window.open("extensiones/tcpdf/pdf/reporteprestamo.php", "_blank");
    });
});
/*=============================================
MOSTRAR PRESTAMO PRESTAMO
=============================================*/
$(".tablas").on("click", ".btnMostrarPrestamo", function(){

	var mostrarPresId = $(this).attr("mostrarPresId");
	var settings = {
		"url": `${CONFIG.API_BASE_URL}prestamo/${mostrarPresId}`,
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
			
			$("#mostrarPresId").val(detalles.pres_id);
			$("#mostrarInveId").val(detalles.pres_inve_id);
			$("#mostrarNombreAlumno").val(detalles.pres_nombre_alumno);
			$("#mostrarInveNombreId").val(detalles.prod_nombre);
			//$("#mostrarLaboDescripcion").val(detalles.pres_usua_id);
			$("#mostrarPresCantidad").val(detalles.pres_cantidad);
            $("#mostrarPresCodigouniAlumno").val(detalles.pres_codigouni_alumno);
            //$("#mostrarLaboEscuId").val(detalles.pres_evidencia);

            // Transformar la fecha de inicio al formato yyyy-MM-dd
			var fechaDeSolicitud = detalles.pres_fechasolicitud.split(" ")[0];
			$("#mostrarPresFechasolicitud").val(fechaDeSolicitud);

            // Transformar la fecha de inicio al formato yyyy-MM-dd
			var fechaDeEntregado = detalles.pres_fechaentregado.split(" ")[0];
			$("#mostrarFechaentregado").val(fechaDeEntregado);

            // Transformar la fecha de inicio al formato yyyy-MM-dd
			var fechaDeDevolucion = detalles.pres_fechadevolucion.split(" ")[0];
			$("#mostrarPresFechadevolucion").val(fechaDeDevolucion);

            // Transformar la fecha de inicio al formato yyyy-MM-dd
			var fechaDeRealdevolucion = detalles.pres_fecharealdevolucion.split(" ")[0];
			$("#mostrarPresFechadevolucion").val(fechaDeRealdevolucion);

            $("#mostrarPresObservacion").val(detalles.pres_observacion);
            $("#mostrarPresEstado").val(detalles.pres_estado);
			$("#imagenPrevisualizar").attr('src', val(detalles.pres_evidencia));
			//$("#mostrarPresEstado").val(detalles.pres_evidencia);
			// Encuentra la opción seleccionada y actualiza su texto
			$("#mostrarInveId option:selected").text(detalles.prod_nombre);
		} else {
			console.error("La estructura del JSON no es la esperada o Detalles está vacío.");
		}
	  });
})


/*=============================================
CONFIRMAR EDITAR PRESTAMO
=============================================*
$(document).ready(function() {
    $("#formDevolverPrestamo").on("submit", function(event) {
        event.preventDefault(); // Evita que el formulario se envíe de forma tradicional

		var editarPresId = $("#editarPresId").val();
        var editarInveId = $("#editarInveId").val();
        var editarPresCantidad = $("#editarPresCantidad").val();
        var editarPresCodigouniAlumno = $("#editarPresCodigouniAlumno").val();
        var editarPresFechasolicitud = $("#editarPresFechasolicitud").val();
        var editarFechaentregado = $("#editarFechaentregado").val();
        var editarPresFechadevolucion = $("#editarPresFechadevolucion").val();
        var editarPresFecharealdevolucion = $("#editarPresFechadevolucion").val();
        var editarPresObservacion = $("#editarPresObservacion").val();
		var editarFileInput = $("#fileInput").val();
        //var editarPresEstado = $("#editarPresEstado").val();
        console.log(editarPresId);

        // Configura los datos y la solicitud AJAX
        var settings = {
			"url": `${CONFIG.API_BASE_URL}prestamo/${editarPresId}`,
			"method": "PUT",
			"timeout": 0,
			"headers": {
			  "Content-Type": "application/x-www-form-urlencoded",
			  "Authorization": CONFIG.API_AUTH_HEADER
			},
			"data": {
			  "pres_inve_id": editarInveId,
			  "pres_usua_id": "5",
			  "pres_cantidad": editarPresCantidad,
              "pres_codigouni_alumno": editarPresCodigouniAlumno,
			  "pres_evidencia": editarFileInput,
              "pres_fechasolicitud": editarPresFechasolicitud,
              "pres_fechaentregado": editarFechaentregado,
              "pres_fechadevolucion": editarPresFechadevolucion,
              "pres_fecharealdevolucion": editarPresFecharealdevolucion,
              "pres_observacion": editarPresObservacion,
              "pres_estado": "Devuelto"
			},
			success: function(response) {
                console.log("Edición exitosa:", response);
                // Aquí puedes agregar el código para actualizar la interfaz de usuario según sea necesario
                swal({
					type: "success",
					title: "Prestamo ha sido modifficado correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
						if (result.value) {

						window.location = "prestamos";

						}
					})
                // Opcional: actualizar la lista de categorías o hacer algo más después de una edición exitosa
            },
            error: function(xhr, status, error) {
                console.error("Error en editar prestamo:", error);
                swal({
					type: "error",
					title: "Prestamo no puede ir vacía o llevar caracteres especiales!",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
					  if (result.value) {

					  window.location = "prestamos";

					  }
				})
            }
			
		  };
		  $.ajax(settings).done(function (response) {
			console.log(response);
		  });
		  
    });
});/
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