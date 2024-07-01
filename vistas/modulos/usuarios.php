<?php
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

$response = curl_exec($curl);

curl_close($curl);
$data = json_decode($response, true);

/* ROLES */
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => API_BASE_URL . 'roles',
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

$responseroles = curl_exec($curl);

curl_close($curl);
$dataRoles = json_decode($responseroles, true);

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
      
      Administrar usuarios
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar usuarios</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
        <button class="btn btn-secundary" data-toggle="modal" data-target="#modalAgregarUsuario">
          
          Agregar usuario

        </button>

      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:10px">#</th>
           <th>Nombre Completo</th>
           <th>Usuario</th>
           <th>Dni</th>
           <th>Rol</th>
           <th>Estado</th>
           <th>Acciones</th>

         </tr> 

        </thead>

        <tbody>
            <?php foreach($data["Detalles"] as $key => $cliente): ?>
          <tr>
            <td><?= ($key + 1) ?></td>
            <td><?= $cliente["usua_nombrecompleto"] ?></td>
            <td><?= $cliente["usua_username"] ?></td>
            <td><?= $cliente["usua_dni"] ?></td>
            <td><?= $cliente["role_nombre"] ?></td>
            <td><?= $cliente["usua_estado"] ?></td>
            <td>

              <div class="btn-group">
              <button class="btn btn-link fa-lg btnEditarUsuario" idUsuario="<?= $cliente["usua_id"] ?>" data-toggle="modal" data-target="#modalEditarUsuario">
                <i class="fa fa-pencil icono-amarillo"></i>
              </button>
              <button class="btn btn-link fa-lg btnEliminarUsuario" eliminarUsuaId="<?= $cliente["usua_id"] ?>">
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
MODAL AGREGAR USUARIO
======================================-->

<div id="modalAgregarUsuario" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" id="formRegistrarUsuario" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#003264; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar usuario</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE COMPLETO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevoUsuaNombreCompleto" placeholder="Ingresar nombre" id="nuevoUsuaNombreCompleto" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL USERNAME -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevoUsuaUsername" placeholder="Ingresar usuario" id="nuevoUsuaUsername" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL CLAVE -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="password" class="form-control input-lg" name="nuevoUsuaClave" placeholder="Ingresar clave" id="nuevoUsuaClave" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL DNI -->
            
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 
                <input type="number" class="form-control input-lg" name="nuevoUsuaDni" placeholder="Ingresar dni" id="nuevoUsuaDni" required minlength="8" maxlength="8">
              </div>
            </div>

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-secundary">Guardar usuario</button>

        </div>

      </form>

    </div>

  </div>

</div>

<!--=====================================
MODAL EDITAR USUARIO
======================================-->

<div id="modalEditarUsuario" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form id="formEditarUsuario" role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#003264; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar usuario</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE COMPLETO -->
            
            <div class="form-group">
            <label>Nombre completo</label>
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" id="editarUsuaNombreCompleto" name="editarUsuaNombreCompleto" value="" required>
                <input type="hidden"  name="usuaId" id="usuaId" required>

              </div>

            </div>
            <!-- ENTRADA PARA EL USERNAME -->
            
            <div class="form-group">
            <label>Usuario</label>
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" id="editarUsuaUsername" name="editarUsuaUsername" value="" required>

              </div>

            </div>
            <!-- ENTRADA PARA EL CLAVE -->
            
            <div class="form-group">
            <label>Clave</label>
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="password" class="form-control input-lg" id="editarUsuaClave" name="editarUsuaClave" value="" required readonly>

              </div>

            </div>

            <!-- ENTRADA PARA EL DNI -->
            
            <div class="form-group">
              
            <label>Dni</label>
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" id="editarUsuaDni" name="editarUsuaDni" value="" required>

              </div>

            </div>

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-secundary">Modificar usuario</button>

        </div>

      </form>

    </div>

  </div>

</div>
<script>
  document.getElementById('nuevoUsuaDni').addEventListener('input', function (e) {
    var value = e.target.value;

    // Limitar el valor a 8 dígitos
    if (value.length > 8) {
      e.target.value = value.slice(0, 8);
    }
  });

  document.getElementById('formRegistrarUsuario').addEventListener('submit', function (e) {
    var dniInput = document.getElementById('nuevoUsuaDni');
    if (dniInput.value.length !== 8) {
      e.preventDefault();  // Prevenir el envío del formulario
      alert('El DNI debe tener exactamente 8 dígitos');
    }
  });
</script>