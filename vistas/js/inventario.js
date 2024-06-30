$(document).ready(function() {
	var initialStock = 0;  // Variable para almacenar el stock inicial
  
	// Actualizar stock actual al seleccionar un producto
	$("#nuevoInveProdId").on("change", function() {
	  var stock = $(this).find(':selected').data('stock');
	  initialStock = stock;  // Guardar el stock inicial
	  $("#stockActual").val(stock);
	  $("#nuevoInveTipomovimiento").val('');  // Resetear tipo de movimiento
	  $("#nuevoInveCantidadDisponible").val('');  // Resetear cantidad disponible
	});
  
	// Manejar cambios en el tipo de movimiento y la cantidad disponible
	$("#nuevoInveTipomovimiento, #nuevoInveCantidadDisponible").on("change keyup", function() {
	  var tipoMovimiento = $("#nuevoInveTipomovimiento").val();
	  var cantidadDisponible = parseFloat($("#nuevoInveCantidadDisponible").val());
	  var nuevoStock = initialStock;
  
	  if (!isNaN(cantidadDisponible)) {
		if (tipoMovimiento === "Ingreso") {
		  nuevoStock = initialStock + cantidadDisponible;
		} else if (tipoMovimiento === "Retiro") {
		  nuevoStock = initialStock - cantidadDisponible;
		}
	  }
  
	  $("#stockActual").val(nuevoStock);
	  
	  // Si se vuelve a seleccionar "Seleccionar tipo de movimiento", restaurar el stock inicial
	  if (tipoMovimiento === "") {
		$("#stockActual").val(initialStock);
	  }
	});
  });


/*=============================================
EDITAR INVENTARIO
=============================================*/
$(".tablas").on("click", ".btnEditarInventario", function(){

	var inveId = $(this).attr("inveId");
	console.log(inveId);
	var settings = {
		"url": `${CONFIG.API_BASE_URL}inventario/${inveId}`,
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
			console.log("aqui estoyy" + detalles.labo_nombre);
			console.log(detalles.labo_descripcion);
			console.log(detalles.labo_escu_id);
			
			$("#editarInveId").val(detalles.inve_id);
			$("#editarInveTipomovimiento").val(detalles.inve_tipomovimiento);
			$("#editarInveProdId").val(detalles.inve_prod_id);
			$("#editarInveCantidadDisponible").val(detalles.inve_cantidad_disponible);
            $("#editarInveLaboId").val(detalles.inve_labo_id);

            // Transformar la fecha de inicio al formato yyyy-MM-dd
			var fecha = detalles.inve_fecha.split(" ")[0];
			$("#editarInveFecha").val(fecha);

			// Encuentra la opción seleccionada y actualiza su texto
			$("#editarInveProdId option:selected").text(detalles.prod_nombre);
            $("#editarInveTipomovimiento option:selected").text(detalles.inve_tipomovimiento);
		} else {
			console.error("La estructura del JSON no es la esperada o Detalles está vacío.");
		}
	  });
})
/*=============================================
CONFIRMAR EDITAR INVENTARIO
=============================================*/
$(document).ready(function() {
    $("#formEditarInventario").on("submit", function(event) {
        event.preventDefault(); // Evita que el formulario se envíe de forma tradicional

		var editarInveId = $("#editarInveId").val();
        var editarInveTipomovimiento = $("#editarInveTipomovimiento").val();
        var editarInveProdId = $("#editarInveProdId").val();
        var editarInveCantidadDisponible = $("#editarInveCantidadDisponible").val();
        var editarInveLaboId = $("#editarInveLaboId").val();
        var editarInveFecha = $("#editarInveFecha").val();

        // Configura los datos y la solicitud AJAX
        var settings = {
			"url": `${CONFIG.API_BASE_URL}inventario/${editarInveId}`,
			"method": "PUT",
			"timeout": 0,
			"headers": {
			  "Content-Type": "application/x-www-form-urlencoded",
			  "Authorization": CONFIG.API_AUTH_HEADER
			},
			"data": {
			    "inve_tipomovimiento": editarInveTipomovimiento,
                "inve_prod_id": editarInveProdId,
                "inve_cantidad_disponible": editarInveCantidadDisponible,
                "inve_labo_id": "3",
                "inve_fecha": editarInveFecha
			},
			success: function(response) {
                console.log("Edición exitosa:", response);
                // Aquí puedes agregar el código para actualizar la interfaz de usuario según sea necesario
                swal({
					type: "success",
					title: "Inventario ha sido modifficado correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
						if (result.value) {

						window.location = "inventario";

						}
					})
                // Opcional: actualizar la lista de categorías o hacer algo más después de una edición exitosa
            },
            error: function(xhr, status, error) {
                console.error("Error en editar laboratorio:", error);
                swal({
					type: "error",
					title: "Inventario no puede ir vacía o llevar caracteres especiales!",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
					  if (result.value) {

					  window.location = "inventario";

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
GUARDAR INVENTARIO
=============================================*/
$(document).ready(function() {
    $("#formRegistrarInventario").on("submit", function(event) {
        event.preventDefault(); // Evita que el formulario se envíe de forma tradicional
        // Acceder a la configuración definida en config.js

        // Captura los valores de los campos
        var nuevoInveProdId = $("#nuevoInveProdId").val();
        var nuevoInveTipomovimiento = $("#nuevoInveTipomovimiento").val();
        //var nuevoInveCantidadDisponible = $("#nuevoInveCantidadDisponible").val();
		var nuevoStockActual = $("#stockActual").val();
        var inveLaboId = $("#inveLaboId").val();
        var nuevoInveFecha = $("#nuevoInveFecha").val();
		
        // Configura los datos y la solicitud AJAX
        var settings = {
			"url": `${CONFIG.API_BASE_URL}inventario`,
			"method": "POST",
			"timeout": 0,
			"headers": {
				"Content-Type": "application/x-www-form-urlencoded",
				"Authorization": CONFIG.API_AUTH_HEADER
			},
			"data": {
				"inve_tipomovimiento": nuevoInveTipomovimiento,
                "inve_prod_id": nuevoInveProdId,
                "inve_cantidad_disponible": nuevoStockActual,
                "inve_labo_id": inveLaboId,
                "inve_fecha": nuevoInveFecha
			},
			success: function(response) {
                console.log("Registro exitosa:", response);
                // Aquí puedes agregar el código para actualizar la interfaz de usuario según sea necesario
                swal({
					type: "success",
					title: "Laboratorio ha sido guardado correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
						if (result.value) {

						window.location = "inventario";

						}
				})
                // Opcional: actualizar la lista de categorías o hacer algo más después de una edición exitosa
            },
            error: function(xhr, status, error) {
                console.error("Error en crear:", error);
                swal({
					type: "error",
					title: "Laboratorio no puede ir vacía o llevar caracteres especiales!",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
					  if (result.value) {

					  window.location = "inventario";

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
ELIMINAR INVENTARIO
=============================================*/
//$(".tablas").on("click", ".btnEditarLaboratorio", function(){

$(".tablas").on("click", ".btnEliminarInventario", function(){
	var eliminarInveId = $(this).attr("eliminarInveId");
	console.log(eliminarInveId);

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
            var settings = {
                "url": `${CONFIG.API_BASE_URL}inventario/${eliminarInveId}`,
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
					title: "Inventario ha sido eliminado correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
						if (result.value) {

						window.location = "inventario";

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
    $(".btnImprimirReporte").on("click", function() {
        var codigoVenta = $(this).attr("inveId"); // Asegúrate de que el atributo inveId esté presente en el botón

        window.open("extensiones/tcpdf/pdf/reporteinventario.php", "_blank");
    });
});