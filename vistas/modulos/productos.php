<?php

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

$response = curl_exec($curl);

curl_close($curl);
$data = json_decode($response, true);

/* ESCUELA */
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => API_BASE_URL . 'TipoProducto',
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

$responsetipoproducto = curl_exec($curl);

curl_close($curl);
$dataTipoProducto = json_decode($responsetipoproducto, true);

?>
<div class="content-wrapper">

  <section class="content-header">
    
    <h1>
      
      Administrar productos
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar productos</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarProducto">
          
          Agregar producto

        </button>

      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:10px">#</th>
           <th>Nombre</th>
           <th>Código</th>
           <th>Tipo de producto</th>
           <th>Acciones</th>
           
         </tr> 

        </thead>      
        <tbody>
          <?php foreach($data["Detalles"] as $key => $productos): ?>
          <tr>
            <td><?= ($key + 1) ?></td>
            <td><?= $productos["prod_nombre"] ?></td>
            <td><?= $productos["prod_codigoinventario"] ?></td>
            <td><?= $productos["tipr_nombre"] ?></td>
            <td>

              <div class="btn-group">
                <button class="btn btn-warning btnEditarProducto" prodId="<?= $productos["prod_id"] ?>" data-toggle="modal" data-target="#modalEditarProducto">
                  <i class="fa fa-pencil"></i>
                </button>
                <button class="btn btn-danger btnEliminarProducto" eliminarProdId="<?= $productos["prod_id"] ?>">
                  <i class="fa fa-times"></i>
                </button>
              </div>  

            </td>
          </tr>
          <?php endforeach ?>
		</tbody>

       </table>

       <input type="hidden" value="<?php echo $_SESSION['perfil']; ?>" id="perfilOculto">

      </div>

    </div>

  </section>

</div>

<!--=====================================
MODAL AGREGAR PRODUCTO
======================================-->

<div id="modalAgregarProducto"  class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" id="formRegistrarProducto" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#003264; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar Producto</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE DEL PRODUCTO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevoProdNombre" placeholder="Ingresar nombre del producto" id="nuevoProdNombre" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL CODIGO DE INVENTARIO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="number" class="form-control input-lg" name="nuevoProdCodigoinventario" placeholder="Ingresar identificador" id="nuevoProdCodigoinventario" required>

              </div>

            </div>
            <!-- ENTRADA PARA SELECCIONAR TIPO PRODUCTO -->

            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 
                <select class="form-control input-lg" id="nuevoProdTiprId" name="nuevoProdTiprId" >
                  <option value="">Selecionar tipo producto</option>
                  <?php foreach ($dataTipoProducto["Detalles"] as $key => $tipoProducto): ?>
                  <option value="<?= $tipoProducto["tipr_id"] ?>">
                    <?= $tipoProducto["tipr_nombre"] ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>

            </div>
          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-#003264">Guardar laboratorio</button>

        </div>

      </form>

    </div>

  </div>

</div>

<!--=====================================
MODAL EDITAR PRODUCTO
======================================-->

<div id="modalEditarProducto" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form id="formEditarProducto" role="form" method="post" enctype="multipart/form-data">

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

            <!-- ENTRADA PARA EL NOMBRE DEL PRODUCTO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="editarProdNombre" placeholder="Ingresar nombre del producto" id="editarProdNombre" required>
                <input type="hidden"  name="prodId" id="prodId" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL CODIGO DE INVENTARIO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="editarProdCodigoinventario" placeholder="Ingresar codigo" id="editarProdCodigoinventario" required>

              </div>

            </div>
            <!-- ENTRADA PARA SELECCIONAR TIPO PRODUCTO -->

            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 
                <select class="form-control input-lg" id="editarProdTiprId" name="editarProdTiprId" >
                  <option value="">Selecionar tipo producto</option>
                  <?php foreach ($dataTipoProducto["Detalles"] as $key => $tipoProducto): ?>
                  <option value="<?= $tipoProducto["tipr_id"] ?>">
                    <?= $tipoProducto["tipr_nombre"] ?>
                  </option>
                  <?php endforeach; ?>
                </select>
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



