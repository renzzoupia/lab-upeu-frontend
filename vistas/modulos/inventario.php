<?php
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => API_BASE_URL . 'inventario',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    API_AUTH_HEADER
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$data = json_decode($response, true);

/* ESCUELA */
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => API_BASE_URL . 'producto',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    API_AUTH_HEADER
  ),
));

$responseproducto = curl_exec($curl);

curl_close($curl);
$dataProducto = json_decode($responseproducto, true);

// Crear un mapa de productos y sus stocks
$productoStocks = [];
foreach ($data["Detalles"] as $key => $inventario) {
    $prodId = $inventario["inve_prod_id"];
    if (!isset($productoStocks[$prodId])) {
        $productoStocks[$prodId] = 0;
    }
    $productoStocks[$prodId] += $inventario["inve_cantidad_disponible"]; // Asumiendo que 'stock' es el campo que contiene la cantidad
}

?>
<div class="content-wrapper">

  <section class="content-header">
    
    <h1>
      
      Administrar inventario
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar inventario</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarInventario">
          
          Registrar producto al inventario

        </button>

        <button class="btn btn-info btnImprimirReporte">
          
          Generar reporte

        </button>
        

      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:10px">#</th>
           <th>Producto</th>
           <th>Codigo de inventario</th>
           <th>Tipo movimiento</th>
           <th>Fecha</th>
           <th>Stock</th>
           <th>Acciones</th>
           
         </tr> 

        </thead>      
        <tbody>
          <?php $invertedData = array_reverse($data["Detalles"]); ?>
          <?php foreach($invertedData as $key => $inventario): ?>
            <?php
              $mostrar = false;

              // Verificar el perfil del usuario
              if ($_SESSION["perfil"] == 5) {
                  // Perfil 5 puede ver todo
                  $mostrar = true;
              } elseif ($_SESSION["perfil"] == 3 && $prestamo["inve_labo_id"] == $_SESSION["labo_id"]) {
                  // Perfil 3 solo puede ver si inve_labo_id coincide
                  $mostrar = true;
              }

              // Mostrar los datos si se cumplen las condiciones
              if ($mostrar): ?>
          <tr>
            <td><?= ($key + 1) ?></td>
            <td><?= $inventario["prod_nombre"] ?></td>
            <td><?= $inventario["prod_codigoinventario"] ?></td>
            <td><?= $inventario["inve_tipomovimiento"] ?></td>
            <td><?= substr($inventario["inve_fecha"], 0, 10) ?></td>
            <td><?= $inventario["inve_cantidad_disponible"] ?></td>
            <td>

              <div class="btn-group">
                <button class="btn btn-warning btnEditarInventario" inveId="<?= $inventario["inve_id"] ?>" data-toggle="modal" data-target="#modalEditarInventario">
                  <i class="fa fa-pencil"></i>
                </button>
                <button class="btn btn-danger btnEliminarInventario" eliminarInveId="<?= $inventario["inve_id"] ?>">
                  <i class="fa fa-times"></i>
                </button>
              </div>  

            </td>
          </tr>
    <?php endif; ?>
  <?php endforeach; ?>
		</tbody>

       </table>

       <input type="hidden" value="<?php echo $_SESSION['perfil']; ?>" id="perfilOculto">

      </div>

    </div>

  </section>

</div>

<!--=====================================
MODAL AGREGAR INVENTARIO
======================================-->

<div id="modalAgregarInventario"  class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" id="formRegistrarInventario" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#003264; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar producto al inventario</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

          <!-- ENTRADA PARA SELECCIONAR PRODUCTO -->
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-th"></i></span> 
              <select class="form-control input-lg" id="nuevoInveProdId" name="nuevoInveProdId">
                <option value="">Seleccionar producto</option>
                <?php foreach ($dataProducto["Detalles"] as $key => $producto): ?>
                  <?php
                  $prodId = $producto["prod_id"];
                  $stock = isset($productoStocks[$prodId]) ? $productoStocks[$prodId] : 0;
                  ?>
                  <option value="<?= $prodId ?>" data-stock="<?= $stock ?>">
                    <?= $producto["prod_nombre"] ?> - Stock: <?= $stock ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

            <!-- ENTRADA PARA EL TIPO MOVIMIENTO -->
            
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-th"></i></span>
                    <select class="form-control input-lg" id="nuevoInveTipomovimiento" name="nuevoInveTipomovimiento">
                        <option value="">Seleccionar tipo de movimiento</option>
                        <option value="Ingreso">Ingreso</option>
                        <option value="Retiro">Retiro</option>
                    </select>
                </div>
            </div>

            <!-- ENTRADA PARA CANTIDAD DISPONIBLE -->
            
            <div class="form-group">
            <label>Ingresar cantidad</label>
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 
                <input type="hidden" name="inveLaboId" id="inveLaboId" value="<?php echo $_SESSION['labo_id']; ?>" required>
                <input type="text" class="form-control input-lg" name="nuevoInveCantidadDisponible" placeholder="Ingresar cantidad" id="nuevoInveCantidadDisponible" required>

              </div>

            </div>

            <!-- ENTRADA PARA la CANTIDAD DISPONIBLE -->
            
            <div class="form-group">
              
            <label>Cantidad total</label>
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="stockActual" id="stockActual" required readonly>

              </div>

            </div>
            
            <!-- ENTRADA PARA LA FECHA -->
            
            <div class="form-group">
              <label>Fecha del movimiento</label>
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="date" class="form-control input-lg" name="nuevoInveFecha" placeholder="Ingresar codigo" id="nuevoInveFecha" required>

              </div>

            </div>
            
          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-#003264">Ingresar inventario</button>

        </div>

      </form>

    </div>

  </div>

</div>

<!--=====================================
MODAL EDITAR INVENTARIO
======================================-->

<div id="modalEditarInventario" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form id="formEditarInventario" role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#003264; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar producto</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

          <!-- ENTRADA PARA SELECCIONAR PRODUCTO -->

          <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 
                <select class="form-control input-lg" id="editarInveProdId" name="editarInveProdId" >
                  <option value="">Selecionar producto</option>
                  <?php foreach ($dataProducto["Detalles"] as $key => $producto): ?>
                  <option value="<?= $producto["prod_id"] ?>">
                    <?= $producto["prod_nombre"] ?>
                  </option>
                  <?php endforeach; ?>
                </select>
                <input type="hidden"  name="editarInveId" id="editarInveId" required>
              </div>

          </div>

            <!-- ENTRADA PARA EL TIPO MOVIMIENTO -->
            
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-th"></i></span>
                    <select class="form-control input-lg" id="editarInveTipomovimiento" name="editarInveTipomovimiento">
                        <option value="">Seleccionar tipo de movimiento</option>
                        <option value="Ingreso">Ingreso</option>
                        <option value="Retiro">Retiro</option>
                        <option value="Prestado">Prestado</option>
                    </select>
                </div>
            </div>

            <!-- ENTRADA PARA CANTIDAD DISPONIBLE -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="editarInveCantidadDisponible" placeholder="Ingresar cantidad" id="editarInveCantidadDisponible" required>

              </div>

            </div>

            <!-- ENTRADA PARA LA FECHA -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="date" class="form-control input-lg" name="editarInveFecha" placeholder="Ingresar codigo" id="editarInveFecha" required>

              </div>

            </div>
            
          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Modificar producto</button>

        </div>

      </form>

    </div>

  </div>

</div>     
<script type="text/javascript">
  var laboIdSesion = <?php echo json_encode($_SESSION["labo_id"]); ?>;
</script>
