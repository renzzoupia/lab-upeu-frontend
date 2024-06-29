<?php

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => API_BASE_URL . 'prestamo',
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

/* USUARIO */
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL =>  API_BASE_URL . 'usuario',
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


if($_SESSION["perfil"] == "Especial" || $_SESSION["perfil"] == "Vendedor"){

  echo '<script>

    window.location = "inicio";

  </script>';

  return;

}

?>
<div class="content-wrapper">

  <section class="content-header">
    
    <h1>
      
      Administrar lista de prestamos
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar lista de prestamos</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
        <button class="btn btn-secundary" data-toggle="modal" data-target="#modalAgregarPrestamo">
          
          Agregar prestamo

        </button>

      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:10px">#</th>
           <th>Producto</th>
           <th>Fecha de Solicitud</th>
           <th>Fecha de Entrega</th>
           <th>Fecha Devuelto</th>
           <th>Estado</th>
           <th>Evidencia</th>
           <th>Acciones</th>

         </tr> 

        </thead>

        <tbody>
          <?php foreach($data["Detalles"] as $key => $prestamo): ?>
            <?php 
            // Verifica si el usuario tiene un perfil que le permite ver todos los registros o solo ciertos registros
            if ($_SESSION["perfil"] == 5 || ($_SESSION["perfil"] == 3 && $_SESSION["labo_id"] == $prestamo["inve_labo_id"])): ?>
          <tr>
            <td><?= ($key + 1) ?></td>
            <td><?= $prestamo["prod_nombre"] ?></td>
            <td><?= substr($prestamo["pres_fechasolicitud"], 0, 10) ?></td>
            <td><?= substr($prestamo["pres_fechaentregado"], 0, 10) ?></td>
            <td><?= substr($prestamo["pres_fecharealdevolucion"], 0, 10) ?></td>
            <td><?= $prestamo["pres_estado"] ?></td>

            <td>
                <img src='<?= $prestamo["pres_evidencia"] ?>' width='40px'>
            </td>
            <td>

              <div class="btn-group">
                <!-- Condición para mostrar el botón solo si el préstamo no está devuelto -->
                <button class="btn btn-link fa-lg btnDevolverPrestamo" 
                        devolverPresId="<?= $prestamo["pres_id"] ?>" 
                        data-toggle="modal" 
                        data-target="#modalDevolverPrestamo"
                        <?= $prestamo["pres_estado"] === "Devuelto" ? 'disabled' : '' ?>>
                    <i class="fa fa-check"></i>
                </button>
                <button class="btn btn-link fa-lg  btnEliminarPrestamo" eliminarPresId="<?= $prestamo["pres_id"] ?>">
                  <i class="fa fa-times"></i>
                </button>
                
              </div>  

            </td>
          </tr>
          <?php endif; ?>
          <?php endforeach ?>
		    </tbody>

       </table>

      </div>

    </div>

  </section>

</div>

<!--=====================================
MODAL AGREGAR PRESTAMO
======================================-->

<div id="modalAgregarPrestamo"  class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" id="formRegistrarPrestamo" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#003264; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar prestamo</h4>

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
                    <select class="form-control input-lg" id="nuevoInveId" name="nuevoInveId" >
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

            <!-- ENTRADA PARA EL CANTIDAD -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                <input type="number" class="form-control input-lg" id="nuevoPresCantidad" name="nuevoPresCantidad" placeholder="Ingresar cantidad" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL CODIGO ALUMNO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                <input type="number" class="form-control input-lg" id="presCodigouniAlumno" name="presCodigouniAlumno" placeholder="Ingresar codigo alumno" required>

              </div>

            </div>

            <!-- ENTRADA PARA FECHA SOLICITUD -->
            
            <div class="form-group">
            <label>Fecha de la solicitud</label>
              <div class="input-group">
                
                <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                <input type="date" class="form-control input-lg" id="nuevoPresFechasolicitud" name="nuevoPresFechasolicitud" placeholder="Ingresar fecha solicitud" required readonly>

              </div>

            </div>

            <!-- ENTRADA PARA FECHA ENTREGADO -->
            
            <div class="form-group">
                <label>Fecha estimada de regreso por parte del alumno</label>
                <div class="input-group">
              
                    <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                    <input type="date" class="form-control input-lg" id="nuevoFechaentregado" name="nuevoFechaentregado" placeholder="Ingresar fecha entregado" required>

                </div>

            </div>

            <!-- ENTRADA PARA SUBIR FOTO -->

             <div class="form-group">
              
              <div class="panel">SUBIR IMAGEN</div>

              <input type="file" class="nuevaImagen" name="nuevaImagen" id="fileInput">

              <p class="help-block">Peso máximo de la imagen 2MB</p>

              <img src="vistas/img/productos/default/anonymous.png" class="img-thumbnail previsualizar" width="100px">

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
MODAL DEVOLVER PRESTAMO
======================================-->

<div id="modalDevolverPrestamo" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form id="formDevolverPrestamo" role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#003264; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Devolver prestamo</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">
            <!-- ENTRADA PARA EL PRODUCTO PRESTADO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-code"></i></span> 
                <input type="hidden"  name="editarPresId" id="editarPresId" required>
                <input type="hidden"  name="editarInveId" id="editarInveId" required>
                <input type="text" class="form-control input-lg" id="editarInveNombreId" name="editarInveNombreId" required readonly>

              </div>

            </div>
            <!-- ENTRADA PARA EL CANTIDAD -->
            
            <div class="form-group">
            <label>Cantidad prestada</label>
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                <input type="number" class="form-control input-lg" id="editarPresCantidad" name="editarPresCantidad" placeholder="Ingresar cantidad" required readonly>

              </div>

            </div>

            <!-- ENTRADA PARA EL CODIGO ALUMNO -->
            
            <div class="form-group">
            <label>Codigo del alumno</label>
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                <input type="number" class="form-control input-lg" id="editarPresCodigouniAlumno" name="editarPresCodigouniAlumno" placeholder="Ingresar codigo alumno" required readonly>

              </div>

            </div>

            <!-- ENTRADA PARA FECHA SOLICITUD -->
            
            <div class="form-group">
            <label>Fecha de la solicitud</label>
              <div class="input-group">
                
                <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                <input type="date" class="form-control input-lg" id="editarPresFechasolicitud" name="editarPresFechasolicitud" placeholder="Ingresar fecha solicitud" required readonly>

              </div>

            </div>

            <!-- ENTRADA PARA FECHA ENTREGA ESTIMADA -->
            
            <div class="form-group">
                <label>Fecha de devolucion estimada</label>
                <div class="input-group">
              
                    <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                    <input type="date" class="form-control input-lg" id="editarFechaentregado" name="editarFechaentregado" placeholder="Ingresar fecha entregado" required readonly>

                </div>

            </div>

            <!-- ENTRADA PARA EL FECHA DEVOLUCION -->
            
            <div class="form-group">
                <label>Fecha de devolucion real</label>
                <div class="input-group">
              
                    <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                    <input type="date" class="form-control input-lg" id="editarPresFechadevolucion" name="editarPresFechadevolucion"  required>

                </div>

            </div>

            <!-- ENTRADA PARA EL OBSERVACION -->
            
            <div class="form-group">
            <label>Observaciones de devuelto</label>
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                <input type="text" class="form-control input-lg" id="editarPresObservacion" name="editarPresObservacion" placeholder="Ingresar observacion" required>

              </div>

            </div>

            <!-- ENTRADA PARA SUBIR FOTO -->

             <div class="form-group">
              
              <div class="panel">SUBIR IMAGEN</div>

              <input type="file" class="nuevaImagen" name="nuevaImagen" id="editarImagen">

              <p class="help-block">Peso máximo de la imagen 2MB</p>

              <img src="vistas/img/productos/default/anonymous.png" id="imagenPrevisualizar" class="img-thumbnail previsualizar" width="100px">

            </div>

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Modificar prestamo</button>

        </div>

      </form>

    </div>

  </div>

</div>

<!--=====================================
MODAL EDITAR PRESTAMO
======================================-->

<div id="modalEditarPrestamo" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form id="formEditarPrestamo" role="form" method="post" enctype="multipart/form-data">

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

            <!-- ENTRADA PARA ELEGIR PRODUCTO -->
            
            <div class="form-group">
                <div class="input-group">
                    <input type="hidden"  name="editarPresId" id="editarPresId" required>
                    <span class="input-group-addon"><i class="fa fa-th"></i></span> 
                    <select class="form-control input-lg" id="editarInveId" name="editarInveId" >
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

            <!-- ENTRADA PARA EL CANTIDAD -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                <input type="number" class="form-control input-lg" id="editarPresCantidad" name="editarPresCantidad" placeholder="Ingresar cantidad" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL CODIGO ALUMNO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                <input type="number" class="form-control input-lg" id="editarPresCodigouniAlumno" name="editarPresCodigouniAlumno" placeholder="Ingresar codigo alumno" required>

              </div>

            </div>

            <!-- ENTRADA PARA FECHA SOLICITUD -->
            
            <div class="form-group">
            <label>Ingresar fecha de la solicitud</label>
              <div class="input-group">
                
                <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                <input type="date" class="form-control input-lg" id="editarPresFechasolicitud" name="editarPresFechasolicitud" placeholder="Ingresar fecha solicitud" required>

              </div>

            </div>

            <!-- ENTRADA PARA FECHA ENTREGADO -->
            
            <div class="form-group">
                <label>Ingresar fecha de entrega</label>
                <div class="input-group">
              
                    <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                    <input type="date" class="form-control input-lg" id="editarFechaentregado" name="editarFechaentregado" placeholder="Ingresar fecha entregado" required>

                </div>

            </div>

            <!-- ENTRADA PARA EL FECHA DEVOLUCION -->
            
            <div class="form-group">
                <label>Ingresar fecha de devolución</label>
                <div class="input-group">
              
                    <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                    <input type="date" class="form-control input-lg" id="editarPresFechadevolucion" name="editarPresFechadevolucion" placeholder="Ingresar fecha devolucion" required>

                </div>

            </div>

            <!-- ENTRADA PARA EL OBSERVACION -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                <input type="text" class="form-control input-lg" id="editarPresObservacion" name="editarPresObservacion" placeholder="Ingresar observacion" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL ESTADO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                <input type="text" class="form-control input-lg" id="editarPresEstado" name="editarPresEstado" placeholder="Ingresar estado" required>

              </div>

            </div>

            <!-- ENTRADA PARA SUBIR FOTO -->

             <div class="form-group">
              
              <div class="panel">SUBIR IMAGEN</div>

              <input type="file" class="nuevaImagen" name="nuevaImagen" id="fileInput">

              <p class="help-block">Peso máximo de la imagen 2MB</p>

              <img src="vistas/img/productos/default/anonymous.png" class="img-thumbnail previsualizar" width="100px">

            </div>

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Modificar prestamo</button>

        </div>

      </form>

    </div>

  </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', (event) => {
  const dateInput = document.getElementById('nuevoPresFechasolicitud');
  const today = new Date().toISOString().split('T')[0];
  dateInput.value = today;
});
document.addEventListener('DOMContentLoaded', (event) => {
  const dateInput = document.getElementById('editarPresFechadevolucion');
  const today = new Date().toISOString().split('T')[0];
  dateInput.value = today;
});
</script>

