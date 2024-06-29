<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => API_BASE_URL . 'mantenimiento',
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

/* PRODUCTO */
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

$responseinventario = curl_exec($curl);

curl_close($curl);
$dataInventario = json_decode($responseinventario, true);

if($_SESSION["perfil"] == "Vendedor"){

  echo '<script>

    window.location = "inicio";

  </script>';

  return;

}

?>
<div class="content-wrapper">

  <section class="content-header">
    
    <h1>
      
      Administrar productos en mantenimiento
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar productos en mantenimiento</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarMantenimiento">
          
          Registrar mantenimiento

        </button>

      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:10px">#</th>
           <th>Producto</th>
           <th>Fecha de inicio</th>
           <th>Fecha de devuelta</th>
           <th>Estado</th>
           <th>Acciones</th>
           
         </tr> 

        </thead>      
        <tbody>
          <?php foreach($data["Detalles"] as $key => $mantenimiento): ?>
          <tr>
            <td><?= ($key + 1) ?></td>
            <td><?= $mantenimiento["prod_nombre"] ?></td>
            <td><?= substr($mantenimiento["mant_fechainicio"], 0, 10) ?></td>
            <td><?= substr($mantenimiento["mant_fechadevolucion"], 0, 10) ?></td>
            <td><?= $mantenimiento["mant_estado"] ?></td>
            <td>

              <div class="btn-group">
              <button class="btn btn-success btnEditarMantenimiento" 
                        mantId="<?= $mantenimiento["mant_id"] ?>" 
                        data-toggle="modal" 
                        data-target="#modalEditarMantenimiento"
                        <?= $mantenimiento["mant_estado"] === "Solucionado" ? 'disabled' : '' ?>>
                    <i class="fa fa-pencil"></i>
                </button>
                <button class="btn btn-danger btnEliminarMantenimiento" eliminarMantId="<?= $mantenimiento["mant_id"] ?>">
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
MODAL AGREGAR MANTENIMIENTO
======================================-->

<div id="modalAgregarMantenimiento"  class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" id="formRegistrarMantenimiento" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#003264; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Registrar mantenimiento</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA ELEGIR PRODUCTO -->
            
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-th"></i></span> 
                    <select class="form-control input-lg" id="prodId" name="prodId" >
                        <option value="">Seleccionar producto</option>
                        <?php 
                        $seenProducts = array();
                        foreach ($dataInventario["Detalles"] as $inventario): 
                            if (!in_array($inventario["inve_prod_id"], $seenProducts)): 
                                $seenProducts[] = $inventario["inve_prod_id"];
                        ?>
                        <option value="<?= $inventario["inve_id"] ?>">
                            <?= $inventario["prod_nombre"] ?>
                        </option>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </select>
                </div>
            </div>


            <!-- ENTRADA PARA LA FECHA DE INICIO -->
            
            <div class="form-group">
            <label>Fecha de inicio del mantenimiento</label>
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="date" class="form-control input-lg" name="nuevoMantFechainicio" placeholder="Ingresar descripción" id="nuevoMantFechainicio" required>

              </div>

            </div>

            <!-- ENTRADA PARA LA FECHA DEVOLUCION -->
            <div class="form-group">
            <label>Fecha estimada de devolucion</label>
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="date" class="form-control input-lg" name="nuevoMantFechadevolucion" placeholder="Ingresar descripción" id="nuevoMantFechadevolucion" required>

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
MODAL EDITAR MANTENIMIENTO
======================================-->

<div id="modalEditarMantenimiento" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form id="formEditarMantenimiento" role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#003264; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar mantenimiento</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA ELEGIR PRODUCTO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-code"></i></span> 
                <input type="hidden"  name="editarMantId" id="editarMantId" required>
                <input type="hidden"  name="editarInveId" id="editarInveId" required>
                <input type="text" class="form-control input-lg" id="editarInveNombreId" name="editarInveNombreId" required readonly>

              </div>

            </div>


            <!-- ENTRADA PARA LA FECHA DE INICIO -->
            
            <div class="form-group">
              <label>Fecha de inicio del mantenimiento</label>
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="date" class="form-control input-lg" name="editarMantFechainicio" placeholder="Ingresar descripción" id="editarMantFechainicio" required readonly>

              </div>

            </div>

            <!-- ENTRADA PARA LA FECHA DEVOLUCION -->
            <div class="form-group">
            <label>Fecha de la devolucion del mantenimiento</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="date" class="form-control input-lg" name="editarMantFechadevolucion" placeholder="Ingresar descripción" id="editarMantFechadevolucion" required>
                </div>
            </div>

            <!-- ENTRADA PARA LA RESULTADO -->
            <div class="form-group">
              
            <label>Resultado</label>
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="editarMantResultado" placeholder="Ingresar resultado" id="editarMantResultado" required>

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



