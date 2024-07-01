<aside class="main-sidebar">

	 <section class="sidebar">

		<ul class="sidebar-menu">

		<?php

		if ($_SESSION["perfil"] == "5") {
			echo '
			<li class="active">
				<a href="inicio">
					<i class="fa fa-home"></i>
					<span>Inicio</span>
				</a>
			</li>
			<li>
				<a href="laboratorios">
					<i class="fa fa-flask"></i>
					<span>Laboratorios</span>
				</a>
			</li>
			<li>
				<a href="usuarios">
					<i class="fa fa-user"></i>
					<span>Usuarios</span>
				</a>
			</li>
			<li>
				<a href="prestamos">
					<i class="fa fa-share"></i>
					<span>Prestamos</span>
				</a>
			</li>
			<li>
				<a href="mantenimientos">
					<i class="fa fa-wrench"></i>
					<span>Mantenimientos</span>
				</a>
			</li>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-list-ul"></i>
					<span>Inventario</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="productos">
							<i class="fa fa-circle-o"></i>
							<span>Administrar productos</span>
						</a>
					</li>
					<li>
						<a href="inventario">
							<i class="fa fa-circle-o"></i>
							<span>Inventario</span>
						</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="reportes">
					<i class="fa fa-registered"></i>
					<span>Reportes</span>
				</a>
			</li>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-user-circle-o"></i>
					<span>Hola '. $_SESSION["nombre"] .'</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="salir">
							<i class="fa fa-power-off"></i>
							<span>Cerrar sesión</span>
						</a>
					</li>
				</ul>
			</li>
			';
		}

		// Este bloque maneja el menú para usuarios con perfil 3 (acceso limitado)
		if ($_SESSION["perfil"] == "3") {
			echo '
			<li class="active">
				<a href="inicio">
					<i class="fa fa-home"></i>
					<span>Inicio</span>
				</a>
			</li>
			<li>
				<a href="prestamos">
					<i class="fa fa-share"></i>
					<span>Prestamos</span>
				</a>
			</li>
			<li>
				<a href="mantenimientos">
					<i class="fa fa-wrench"></i>
					<span>Mantenimientos</span>
				</a>
			</li>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-list-ul"></i>
					<span>Inventario</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="productos">
							<i class="fa fa-circle-o"></i>
							<span>Administrar productos</span>
						</a>
					</li>
					<li>
						<a href="inventario">
							<i class="fa fa-circle-o"></i>
							<span>Inventario</span>
						</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="reportes">
					<i class="fa fa-registered"></i>
					<span>Reportes</span>
				</a>
			</li>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-user-circle-o"></i>
					<span>Hola '. $_SESSION["nombre"] .'</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="salir">
							<i class="fa fa-power-off"></i>
							<span>Cerrar sesión</span>
						</a>
					</li>
				</ul>
			</li>';
		}
		?>

		</ul>

	 </section>

</aside>