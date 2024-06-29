<?php
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://restful.informaticapp.com/clientes/'.$_GET['id'],
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'PUT',
		CURLOPT_POSTFIELDS => 
			'nombre='.$_POST["nombre"].
			'&correo='.$_POST["correo"].
			'&zip='.$_POST["zip"].
			'&telefono1='.$_POST["telefono1"].
			'&telefono2='.$_POST["telefono2"].
			'&pais='.$_POST["pais"].
			'&direccion='.$_POST["direccion"],
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/x-www-form-urlencoded',
			'Authorization: Basic YTJhYTA3YWRmaGRmcmV4ZmhnZGZoZGZlcnR0Z2VOTHJpalkvaDhxeWE5M3NycUZTOFZXaWs1ZjJZSFlDOm8yYW8wN29kZmhkZnJleGZoZ2RmaGRmZXJ0dGdldjVQS0hDS3Y4SjFkZFg2NVlScmZwLnRLdkRzZjRJUw=='
		),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		$data = json_decode($response, true);
		header("Location: index.php");

	}else{
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://restful.informaticapp.com/clientes/'.$_GET['id'],
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_HTTPHEADER => array(
			'Authorization: Basic YTJhYTA3YWRmaGRmcmV4ZmhnZGZoZGZlcnR0Z2VOTHJpalkvaDhxeWE5M3NycUZTOFZXaWs1ZjJZSFlDOm8yYW8wN29kZmhkZnJleGZoZ2RmaGRmZXJ0dGdldjVQS0hDS3Y4SjFkZFg2NVlScmZwLnRLdkRzZjRJUw=='
		),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		$data = json_decode($response, true);
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Registrar Cliente</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

	<!-- JS, Popper.js, and jQuery -->
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
	<h1>Registrar Clientes</h1>
	<form method="post" class="col-xl-8 offset-2">
		<input type="hidden" name="id" value="<?= $data["Detalle"]['id'] ?>">
		<input type="text" name="nombre" class="form-control" value="<?= $data["Detalle"]['nombre'] ?>">
		<input type="email" name="correo" class="form-control" value="<?= $data["Detalle"]['correo'] ?>">
		<input type="text" name="zip"  class="form-control" value="<?= $data["Detalle"]['zip'] ?>">
		<input type="phone" name="telefono1"  class="form-control" value="<?= $data["Detalle"]['telefono1'] ?>">
		<input type="phone" name="telefono2"  class="form-control" value="<?= $data["Detalle"]['telefono2'] ?>">
		<input type="text" name="pais" class="form-control" value="<?= $data["Detalle"]['pais'] ?>">
		<input type="text" name="direccion" class="form-control" value="<?= $data["Detalle"]['direccion'] ?>">
		<button type="submit" class="btn btn-success">Guardar</button>
		<a href="index.php" class="btn btn-danger">Cancelar</a>
	</form>
	
</div>
</body>
</html>