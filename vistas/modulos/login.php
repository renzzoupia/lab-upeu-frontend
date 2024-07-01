 
<style>
    .login-box {
      background: #ffffff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 300px; /* Ajustar el tamaño máximo */
    }
    .login-logo img {
      width: 100%;
      max-width: 150px; /* Ajustar el tamaño del logotipo */
      margin: 0 auto;
    }
    .login-box-body {
      padding: 10px; /* Reducir el padding */
    }
    .login-box-msg {
      margin: 0;
      text-align: center;
      font-size: 1.3em; /* Ajustar el tamaño de la fuente */
      margin-bottom: 15px;
      color: #000000;
    }
    .btn-primary {
      background-color: #003264;
      border-color: #003264;
    }
    .btn-primary:hover {
      background-color: #002244;
      border-color: #002244;
    }
  </style>

<div id="back"></div>
<div class="login-box">
  
  <div class="login-logo">
    <img src="vistas/img/plantilla/logo-upeu-login.png" class="img-responsive">
  </div>

  <div class="login-box-body">
    <p class="login-box-msg">Portal de los laboratorios UPeU - Tarapoto 2024</p>

    <form method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Ingresa tu usuario" name="ingUsuario" required>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Ingresa tu contraseña" name="ingPassword" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

      <div class="row">
        <div class="col-xs-12">
          <button type="submit" class="btn btn-primary btn-block ">Iniciar sesión</button>
        </div>
      </div>

      <?php
        $login = new ControladorUsuarios();
        $login->ctrLoginUsuario();
      ?>
    </form>
  </div>
</div>

</div>
