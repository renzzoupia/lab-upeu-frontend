<header class="main-header" style="background-color: #003264; color: white;">
 	
	<!--=====================================
	LOGOTIPO
	======================================-->
	<a href="inicio" class="logo"  style="background-color: #003264; color: white;">
		
		<!-- logo mini -->
		<span class="logo-mini">
			
			<img src="vistas/img/plantilla/icono-upeu-a.png" class="img-responsive">

		</span>

		<!-- logo normal -->

		<span class="logo-lg">
			
			<img src="vistas/img/plantilla/logo-upeu.png" class="img-responsive" >

		</span>

	</a>

	<!--=====================================
	BARRA DE NAVEGACIÓN
	======================================-->
	<nav class="navbar navbar-inverse" role="navigation"  style="background-color: #003264; color: white;">
		
		<!-- Botón de navegación -->

	 	<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      	</a>

		<!-- perfil de usuario -->

		<div class="navbar-custom-menu">
				
		<ul class="nav navbar-nav">
        	<li class="dropdown">
          	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Hola <?php  echo $_SESSION["nombre"]; ?><span class="caret"></span></a>
          	<ul class="dropdown-menu">
            	<li><a href="#">Mi perfil</a></li>
            	<li role="separator" class="divider"></li>
            	<li><a href="salir">Cerrar sesión</a></li>
          </ul>
        	</li>
      	</ul>

		</div>
	</nav>

</header>