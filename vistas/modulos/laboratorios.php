<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => API_BASE_URL . 'laboratorio',
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

/* USUARIO */
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => API_BASE_URL . 'usuario',
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

$responseusuario = curl_exec($curl);

curl_close($curl);
$dataUsuario = json_decode($responseusuario, true);

/* ESCUELA */
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => API_BASE_URL . 'escuela',
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

$responseescuela = curl_exec($curl);

curl_close($curl);
$dataEscuela = json_decode($responseescuela, true);


if($_SESSION["perfil"] == "3"){

  echo '<script>

    window.location = "inicio";

  </script>';

  return;

}

?>

<div class="content-wrapper">

  <section class="content-header">
    
    <h1>
      
      Administrar laboratorios
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar laboratorios</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
        <button class="btn btn-secundary" data-toggle="modal" data-target="#modalAgregarLaboratorio">
          
          Agregar laboratorio

        </button>

      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:10px">#</th>
           <th>Nombre</th>
           <th>Escuela</th>
           <th>Encargado</th>
           <th>Periodo de inicio</th>
           <th>Periodo de fin </th>
           <th>Acciones</th>

         </tr> 

        </thead>

        <tbody>
          <?php foreach($data["Detalles"] as $key => $laboratorio): ?>
          <tr>
            <td><?= ($key + 1) ?></td>
            <td><?= $laboratorio["labo_nombre"] ?></td>
            <td><?= $laboratorio["escu_nombre"] ?></td>
            <td><?= !empty($laboratorio["usua_nombrecompleto"]) ? $laboratorio["usua_nombrecompleto"] : "No encargado" ?></td>
            <td><?= !empty($laboratorio["usla_periodo_inicio"]) ? $laboratorio["usla_periodo_inicio"] : "No disponible" ?></td>
            <td><?= !empty($laboratorio["usla_periodo_fin"]) ? $laboratorio["usla_periodo_fin"] : "No disponible" ?></td>
            <td>

              <div class="btn-group">
              <button class="btn btn-link fa-lg btnAsignarLaboratorio " 
                asignarLaboId="<?= $laboratorio["labo_id"] ?>" 
                data-toggle="modal" data-target="#modalAsignarLaboratorio"
                <?= !empty($laboratorio["usua_nombrecompleto"]) ? 'disabled' : '' ?>>
                <i class="fa fa-user icono-azul"></i>
              </button>
              <button class="btn btn-link fa-lg btnEditarLaboratorio" laboId="<?= $laboratorio["labo_id"] ?>"  data-toggle="modal" data-target="#modalEditarLaboratorio">
                  <i class="fa fa-pencil icono-amarillo"></i>
              </button>
                <button class="btn btn-link fa-lg btnEliminarLaboratorio" eliminarLaboId="<?= $laboratorio["labo_id"] ?>">
                  <i class="fa fa-times icono-rojo"></i>
                </button>
                
              </div>  

            </td>
          </tr>
          <?php endforeach ?>
		    </tbody>

       </table>

      </div>

    </div>

  </section>

</div>
<!--=====================================
MODAL AGREGAR USUARIO LABORATORIO
======================================-->

<div id="modalAsignarLaboratorio"  class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" id="formAsignarLaboratorio" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#003264; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Asignar encargado al laboratorio</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL LABORATORIO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="asignarLaboNombre" placeholder="Ingresar nombre del laboratorio" id="asignarLaboNombre" required readonly>
                <input type="hidden"  name="editarUslaLaboId" id="editarUslaLaboId" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL USUARIO -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 
                <select class="form-control input-lg" id="asignarUsuarioId" name="asignarUsuarioId" >
                  <option value="">Selecionar usuario</option>
                  <?php foreach ($dataUsuario["Detalles"] as $key => $usuario): ?>
                  <option value="<?= $usuario["usua_id"] ?>">
                    <?= $usuario["usua_nombrecompleto"] ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>

            </div>

            <div class="form-group">
            <label>Ingresar fecha de inicio</label>
              <div class="input-group">
                
                <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                <input type="date" class="form-control input-lg" id="asignarFechaInicio" name="asignarFechaInicio" placeholder="Ingresar fecha solicitud" required>

              </div>

            </div>

            <div class="form-group">
            <label>Ingresar fecha de fin</label>
              <div class="input-group">
                
                <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                <input type="date" class="form-control input-lg" id="asignarFechaFin" name="asignarFechaFin" placeholder="Ingresar fecha solicitud" required>

              </div>

            </div>

            <!-- ENTRADA PARA LA ESCUELA ID 
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="laboEscuId" placeholder="Ingresar escuela" id="laboEscuId">

              </div>

            </div>-->
            <!-- ENTRADA PARA SELECCIONAR CATEGORÍA -->

            
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
MODAL AGREGAR LABORATORIO
======================================-->

<div id="modalAgregarLaboratorio"  class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" id="formRegistrarLaboratorio" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#003264; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar laboratorio</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-text-width"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevoLaboNombre" placeholder="Ingresar nombre del laboratorio" id="nuevoLaboNombre" required>

              </div>

            </div>

            <!-- ENTRADA PARA LA DESCRIPCION -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-text-width"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevoLaboDescripcion" placeholder="Ingresar descripción" id="nuevoLaboDescripcion" required>

              </div>

            </div>

            <!-- ENTRADA PARA LA ESCUELA ID 
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="laboEscuId" placeholder="Ingresar escuela" id="laboEscuId">

              </div>

            </div>-->
            <!-- ENTRADA PARA SELECCIONAR CATEGORÍA -->

            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 
                <select class="form-control input-lg" id="laboEscuId" name="laboEscuId" required>
                  <option value="">Selecionar escuela</option>
                  <?php foreach ($dataEscuela["Detalles"] as $key => $escuela): ?>
                  <option value="<?= $escuela["escu_id"] ?>">
                    <?= $escuela["escu_nombre"] ?>
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
MODAL EDITAR LABORATORIO
======================================-->

<div id="modalEditarLaboratorio" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form id="formEditarLaboratorio" role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#003264; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar laboratorio</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE  -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-text-width"></i></span> 

                <input type="text" class="form-control input-lg" id="editarLaboNombre" name="editarLaboNombre" value="" required>
                <input type="hidden"  name="laboId" id="laboId" required>

              </div>

            </div>
            <!-- ENTRADA PARA LA DESCRIPCION -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-text-width"></i></span> 

                <input type="text" class="form-control input-lg" id="editarLaboDescripcion" name="editarLaboDescripcion" value="" required>

              </div>

            </div>
            <!-- ENTRADA PARA LA ESCUELA -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 
                <select class="form-control input-lg" id="editarLaboEscuId" name="editarLaboEscuId" required>
                  <option value="">Selecionar escuela</option>
                  <?php foreach ($dataEscuela["Detalles"] as $key => $escuela): ?>
                  <option value="<?= $escuela["escu_id"] ?>">
                    <?= $escuela["escu_nombre"] ?>
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

          <button type="submit" class="btn btn-secundary">Modificar laboratorio</button>

        </div>

      </form>

    </div>

  </div>

</div>