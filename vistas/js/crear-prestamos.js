/*=============================================
AGREGANDO PRODUCTOS A LA VENTA DESDE LA TABLA
=============================================*/

$(".tablaPrestamos tbody").on("click", "button.agregarProducto", function(){

	var prodId = $(this).attr("prodId");
    
	$(this).removeClass("btn-primary agregarProducto");

	$(this).addClass("btn-default");
    console.log(prodId);

    var settings = {
		"url": `http://localhost:8080/lab-backend/producto/${prodId}`,
		"method": "GET",
		"timeout": 0,
		"headers": {
			"Authorization": "Basic JDJhJDA3JGRmaGRmcmV4ZmhnZGZoZGZlcnR0Z2Vwd2RCVk12aVdXRXdLQkZiMjJoTDZNVWtyRk5xRzhPOiQyYSQwNyRkZmhkZnJleGZoZ2RmaGRmZXJ0dGdlZ2N5cFFKZ2JFZ083TGouWGMyNTRnOXYuemtiTGJoeQ=="
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
            
			var descripcion = detalles.prod_nombre;
          	var stock = detalles.prod_codigoinventario;
          	var precio = detalles.prod_codigoinventario;

            /*=============================================
          	EVITAR AGREGAR PRODUTO CUANDO EL STOCK ESTÁ EN CERO
          	=============================================*/

          	if(stock == 0){

                swal({
                title: "No hay stock disponible",
                type: "error",
                confirmButtonText: "¡Cerrar!"
              });

              $("button[prodId='"+prodId+"']").addClass("btn-primary agregarProducto");

              return;

            }

            $(".nuevoProducto").append(

                '<div class="row" style="padding:5px 15px">'+
  
                '<!-- Descripción del producto -->'+
                
                '<div class="col-xs-9" style="padding-right:0px">'+
                
                  '<div class="input-group">'+
                    
                    '<span class="input-group-addon"><button type="button" class="btn btn-danger btn-xs quitarProducto" prodId="'+prodId+'"><i class="fa fa-times"></i></button></span>'+
  
                    '<input type="text" class="form-control nuevaDescripcionProducto" prodId="'+prodId+'" name="agregarProducto" value="'+descripcion+'" readonly required>'+
  
                  '</div>'+
  
                '</div>'+
  
                '<!-- Cantidad del producto -->'+
  
                '<div class="col-xs-3">'+
                  
                   '<input type="number" class="form-control nuevaCantidadProducto" name="nuevaCantidadProducto" min="1" value="1" stock="'+stock+'" nuevoStock="'+Number(stock-1)+'" required>'+
  
                '</div>' +
  
              '</div>')
              localStorage.removeItem("quitarProducto");

              listarProductos()
			
		} else {
			console.error("La estructura del JSON no es la esperada o Detalles está vacío.");
		}
      });
     

});

/*=============================================
QUITAR PRODUCTOS DE LA VENTA Y RECUPERAR BOTÓN
=============================================*/

var idQuitarProducto = [];

localStorage.removeItem("quitarProducto");

$(".formularioPrestamo").on("click", "button.quitarProducto", function(){

	$(this).parent().parent().parent().parent().remove();

	var prodId = $(this).attr("prodId");
	/*=============================================
	ALMACENAR EN EL LOCALSTORAGE EL ID DEL PRODUCTO A QUITAR
	=============================================*/

	if(localStorage.getItem("quitarProducto") == null){

		idQuitarProducto = [];
	
	}else{

		idQuitarProducto.concat(localStorage.getItem("quitarProducto"))

	}

	idQuitarProducto.push({"prodId":prodId});

	localStorage.setItem("quitarProducto", JSON.stringify(idQuitarProducto));

	$("button.recuperarBoton[prodId='"+prodId+"']").removeClass('btn-default');

	$("button.recuperarBoton[prodId='"+prodId+"']").addClass('btn-primary agregarProducto');

    listarProductos()

})

/*=============================================
AGREGANDO PRODUCTOS DESDE EL BOTÓN PARA DISPOSITIVOS
=============================================*/

var numProducto = 0;

$(".btnAgregarProducto2").click(function(){

	numProducto ++;

    var prodId = $(this).attr("prodId");


    var settings = {
		"url": `http://localhost:8080/lab-backend/producto`,
		"method": "GET",
		"timeout": 0,
		"headers": {
			"Authorization": "Basic JDJhJDA3JGRmaGRmcmV4ZmhnZGZoZGZlcnR0Z2Vwd2RCVk12aVdXRXdLQkZiMjJoTDZNVWtyRk5xRzhPOiQyYSQwNyRkZmhkZnJleGZoZ2RmaGRmZXJ0dGdlZ2N5cFFKZ2JFZ083TGouWGMyNTRnOXYuemtiTGJoeQ=="
		},
    };

      $.ajax(settings).done(function (response) {
        // Si la respuesta es una cadena de texto, conviértela a un objeto JSON
		if (typeof response === 'string') {
			response = JSON.parse(response);
		}
	
		console.log(response); // Verifica la estructura del JSON
	
		if (response && response.Detalles && response.Detalles.length > 0) {
			var detalles = response.Detalles;
			console.log(detalles);

              $(".nuevoProducto").append(

                '<div class="row" style="padding:5px 15px">'+
  
                '<!-- Descripción del producto -->'+
                
                '<div class="col-xs-9" style="padding-right:0px">'+
                
                  '<div class="input-group">'+
                    
                    '<span class="input-group-addon"><button type="button" class="btn btn-danger btn-xs quitarProducto" prodId><i class="fa fa-times"></i></button></span>'+
  
                    '<select class="form-control nuevaDescripcionProducto" id="producto'+numProducto+'" prodId name="nuevaDescripcionProducto" required>'+
  
                    '<option>Seleccione el producto</option>'+
  
                    '</select>'+  
  
                  '</div>'+
  
                '</div>'+
  
                '<!-- Cantidad del producto -->'+
  
                '<div class="col-xs-3 ingresoCantidad">'+
                  
                   '<input type="number" class="form-control nuevaCantidadProducto" id="nuevaCantidadProducto" name="nuevaCantidadProducto" min="1" value="0" stock nuevoStock required>'+
  
                '</div>' +
  
              '</div>');
  
  
              // AGREGAR LOS PRODUCTOS AL SELECT 
  
                detalles.forEach(funcionForEach);
  
               function funcionForEach(item, index){
  
                   //if(item.stock != 0){
  
                       $("#producto"+numProducto).append(
  
                          '<option prodId="'+item.prod_id+'">'+item.prod_nombre+'</option>'
                       )
  
                   
                   //}
  
                   
  
               }
			
		} else {
			console.error("La estructura del JSON no es la esperada o Detalles está vacío.");
		}
      });
})


/*=============================================
GUARDAR PRESTAMO
=============================================*/
$(document).ready(function() {
    $("#formRegistrarPrestamo").on("submit", function(event) {
        event.preventDefault(); // Evita que el formulario se envíe de forma tradicional

        // Captura los valores de los campos
        var nuevoPresInveId = $("#nuevoPresInveId").val();
        var seleccionarPresUsuaId = $("#seleccionarPresUsuaId").val();
        var nuevoPresCantidad = $("#nuevoPresCantidad").val();
        var nuevoPresFechasolicitud = $("#nuevoPresFechasolicitud").val();
        var nuevoPresFechaentregado = $("#nuevoPresFechaentregado").val();
        var nuevoPresFechadevolucion = $("#nuevoPresFechadevolucion").val();
        var nuevoPresFecharealdevolucion = $("#nuevoPresFecharealdevolucion").val();
        var nuevoPresObservacion = $("#nuevoPresObservacion").val();
        var nuevoPresEstado = $("#nuevoPresEstado").val();

		console.log(nuevoPresInveId);
		console.log(seleccionarPresUsuaId);
		console.log(nuevoPresCantidad);
        console.log(nuevoPresFechasolicitud);
        console.log(nuevoPresFechaentregado);
        console.log(nuevoPresFechadevolucion);
        console.log(nuevoPresFecharealdevolucion);
        console.log(nuevoPresObservacion);
        console.log(nuevoPresEstado);

        // Configura los datos y la solicitud AJAX
        var settings = {
			"url": "http://localhost:8080/lab-backend/prestamo",
			"method": "POST",
			"timeout": 0,
			"headers": {
				"Content-Type": "application/x-www-form-urlencoded",
				"Authorization": "Basic JDJhJDA3JGRmaGRmcmV4ZmhnZGZoZGZlcnR0Z2Vwd2RCVk12aVdXRXdLQkZiMjJoTDZNVWtyRk5xRzhPOiQyYSQwNyRkZmhkZnJleGZoZ2RmaGRmZXJ0dGdlZ2N5cFFKZ2JFZ083TGouWGMyNTRnOXYuemtiTGJoeQ=="
			},
			"data": {
                "pres_inve_id": "3",
                "pres_usua_id": seleccionarPresUsuaId,
                "pres_cantidad": "1",
                "pres_fechasolicitud": nuevoPresFechasolicitud,
                "pres_fechaentregado": nuevoPresFechaentregado,
                "pres_fechadevolucion": nuevoPresFechadevolucion,
                "pres_fecharealdevolucion": nuevoPresFecharealdevolucion,
                "pres_observacion": nuevoPresObservacion,
                "pres_estado": nuevoPresEstado
            },
			success: function(response) {
                console.log("Registro exitosa:", response);
                // Aquí puedes agregar el código para actualizar la interfaz de usuario según sea necesario
                swal({
					type: "success",
					title: "Prestamo ha sido guardado correctamente",
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
                console.error("Error en crear:", error);
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
                "url": `http://localhost:8080/lab-backend/laboratorio/${eliminarLaboId}`,
                "method": "DELETE",
                "timeout": 0,
                "headers": {
                  "Authorization": "Basic JDJhJDA3JGRmaGRmcmV4ZmhnZGZoZGZlcnR0Z2Vwd2RCVk12aVdXRXdLQkZiMjJoTDZNVWtyRk5xRzhPOiQyYSQwNyRkZmhkZnJleGZoZ2RmaGRmZXJ0dGdlZ2N5cFFKZ2JFZ083TGouWGMyNTRnOXYuemtiTGJoeQ=="
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