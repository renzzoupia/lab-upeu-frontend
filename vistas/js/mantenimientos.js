/*=============================================
EDITAR MANTENIMIENTO
=============================================*/
$(".tablas").on("click", ".btnEditarMantenimiento", function(){

	var mantId = $(this).attr("mantId");

	var settings = {
		"url": `${CONFIG.API_BASE_URL}mantenimiento/${mantId}`,
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

			console.log(detalles.mant_inve_id);
			console.log(detalles.mant_fechainicio);
			console.log(detalles.mant_fechadevolucion);
			console.log(detalles.mant_resultado);
			console.log(detalles.mant_estado);
			
			$("#editarMantId").val(detalles.mant_id);
			$("#editarInveId").val(detalles.mant_inve_id);
			$("#editarInveNombreId").val(detalles.prod_nombre);
			// Transformar la fecha de inicio al formato yyyy-MM-dd
			var fechaDeInicio = detalles.mant_fechainicio.split(" ")[0];
			$("#editarMantFechainicio").val(fechaDeInicio);

			// Transformar la fecha de devolución al formato yyyy-MM-dd
			var fechaDeDevolucion = detalles.mant_fechadevolucion.split(" ")[0];
			$("#editarMantFechadevolucion").val(fechaDeDevolucion);

			$("#editarMantResultado").val(detalles.mant_resultado);
			// Encuentra la opción seleccionada y actualiza su texto
			$("#editarInveId option:selected").text(detalles.prod_nombre);
		} else {
			console.error("La estructura del JSON no es la esperada o Detalles está vacío.");
		}
	  });
})
/*=============================================
CONFIRMAR EDITAR MANTENIMIENTO
=============================================*/
$(document).ready(function() {
    $("#formEditarMantenimiento").on("submit", function(event) {
        event.preventDefault(); // Evita que el formulario se envíe de forma tradicional

		var editarMantId = $("#editarMantId").val();
        var editarInveId = $("#editarInveId").val();
        var editarMantFechainicio = $("#editarMantFechainicio").val();
        var editarMantFechadevolucion = $("#editarMantFechadevolucion").val();
		var editarMantResultado = $("#editarMantResultado").val();
        // Configura los datos y la solicitud AJAX
        var settings = {
			"url": `${CONFIG.API_BASE_URL}mantenimiento/${editarMantId}`,
			"method": "PUT",
			"timeout": 0,
			"headers": {
			  "Content-Type": "application/x-www-form-urlencoded",
			  "Authorization": CONFIG.API_AUTH_HEADER
			},
			"data": {
			  "mant_inve_id": editarInveId,
			  "mant_fechainicio": editarMantFechainicio,
			  "mant_fechadevolucion": editarMantFechadevolucion,
			  "mant_resultado": editarMantResultado,
			  "mant_estado": "Solucionado"
			},
			success: function(response) {
                console.log("Edición exitosa:", response);
                // Aquí puedes agregar el código para actualizar la interfaz de usuario según sea necesario
                swal({
					type: "success",
					title: "Mantenimiento ha sido modifficado correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
						if (result.value) {

						window.location = "mantenimientos";

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

					  window.location = "mantenimientos";

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
GUARDAR MANTENIMIENTO
=============================================*/
$(document).ready(function() {
    $("#formRegistrarMantenimiento").on("submit", function(event) {
        event.preventDefault(); // Evita que el formulario se envíe de forma tradicional

        // Captura los valores de los campos
        var prodId = $("#prodId").val();
        var nuevoMantFechainicio = $("#nuevoMantFechainicio").val();
        var nuevoMantFechadevolucion = $("#nuevoMantFechadevolucion").val();

        // Configura los datos y la solicitud AJAX
        var settings = {
			"url": `${CONFIG.API_BASE_URL}mantenimiento`,
			"method": "POST",
			"timeout": 0,
			"headers": {
				"Content-Type": "application/x-www-form-urlencoded",
				"Authorization": CONFIG.API_AUTH_HEADER
			},
			"data": {
				"mant_inve_id": prodId,
				"mant_fechainicio": nuevoMantFechainicio,
				"mant_fechadevolucion": nuevoMantFechadevolucion,
				"mant_estado": "Mantenimiento"
			},
			success: function(response) {
                console.log("Registro exitosa:", response);
                // Aquí puedes agregar el código para actualizar la interfaz de usuario según sea necesario
                swal({
					type: "success",
					title: "Mantenimiento ha sido guardado correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
						if (result.value) {

						window.location = "mantenimientos";

						}
				})
                // Opcional: actualizar la lista de categorías o hacer algo más después de una edición exitosa
            },
            error: function(xhr, status, error) {
                console.error("Error en crear:", error);
                swal({
					type: "error",
					title: "Mantenimiento no puede ir vacía o llevar caracteres especiales!",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
					  if (result.value) {

					  window.location = "mantenimientos";

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
ELIMINAR LABORATORIO
=============================================*/
//$(".tablas").on("click", ".btnEditarLaboratorio", function(){

$(".tablas").on("click", ".btnEliminarMantenimiento", function(){
	var eliminarMantId = $(this).attr("eliminarMantId");

    swal({
        title: '¿Está seguro de borrar mantenimiento?',
        text: "¡Si no lo está puede cancelar la acción!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar mantenimiento!'
    }).then(function(result){
		
        if(result.value){
			console.log(eliminarMantId);
            var settings = {
				"url": `${CONFIG.API_BASE_URL}mantenimiento/${eliminarMantId}`,
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
					title: "Mantenimiento ha sido eliminado correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
						if (result.value) {

						window.location = "mantenimientos";

						}
					})
              });

        }

    })

})