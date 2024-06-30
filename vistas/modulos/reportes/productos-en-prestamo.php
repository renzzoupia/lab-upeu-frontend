<?php

// Configuración cURL para obtener datos de préstamos desde la API
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
$prestamoData = json_decode($response, true);

$prestamos = $prestamoData["Detalles"];

// Colores para el gráfico
$colores = array("yellow", "aqua", "purple", "blue", "cyan", "magenta", "orange", "gold", "red", "green");

// Filtrar y unificar productos por `prod_id` para prestamos
$prestamosUnicos = [];
foreach ($prestamos as $prestamo) {
  if ($_SESSION["perfil"] == 5) {
    if (!isset($prestamosUnicos[$prestamo["prod_id"]])) {
      $prestamosUnicos[$prestamo["prod_id"]] = $prestamo;
    } else {
      $prestamosUnicos[$prestamo["prod_id"]]["pres_cantidad"] += $prestamo["pres_cantidad"];
    }
  } elseif ($_SESSION["perfil"] == 3) {
    if ($prestamo["inve_labo_id"] == $_SESSION["labo_id"]) {
      if (!isset($prestamosUnicos[$prestamo["prod_id"]])) {
        $prestamosUnicos[$prestamo["prod_id"]] = $prestamo;
      } else {
        $prestamosUnicos[$prestamo["prod_id"]]["pres_cantidad"] += $prestamo["pres_cantidad"];
      }
    }
  }
}
$prestamosUnicos = array_values($prestamosUnicos); // Reindexar array
?>

<!--=====================================
PRODUCTOS EN PRÉSTAMO
======================================-->

<div class="box box-default">

  <div class="box-header with-border">
    <h3 class="box-title">Productos en préstamo</h3>
  </div>

  <div class="box-body">
    <div class="row">
      <div class="col-md-7">
        <div class="chart-responsive">
          <canvas id="pieChartPrestamos" height="150"></canvas>
        </div>
      </div>

      <div class="col-md-5">
        <ul class="chart-legend clearfix">
          <?php
          for ($i = 0; $i < count($prestamosUnicos) && $i < 10; $i++) {
            echo ' <li><i class="fa fa-circle-o text-' . $colores[$i] . '"></i> ' . $prestamosUnicos[$i]["prod_nombre"] . '</li>';
          }
          ?>
        </ul>
      </div>
    </div>
  </div>

  <div class="box-footer no-padding">
    <ul class="nav nav-pills nav-stacked">
      <?php
      for ($i = 0; $i < count($prestamosUnicos) && $i < 5; $i++) {
        echo '<li>
                <a>
                ' . $prestamosUnicos[$i]["prod_nombre"] . '
                <span class="pull-right text-' . $colores[$i] . '">
                ' . $prestamosUnicos[$i]["pres_cantidad"] . '
                </span>
                </a>
              </li>';
      }
      ?>
    </ul>
  </div>

</div>

<script>
  // -------------
  // - PIE CHART PRÉSTAMOS -
  // -------------
  var pieChartCanvasPrestamos = $('#pieChartPrestamos').get(0).getContext('2d');
  var pieChartPrestamos = new Chart(pieChartCanvasPrestamos);
  var PieDataPrestamos = [
    <?php
    for ($i = 0; $i < count($prestamosUnicos) && $i < 10; $i++) {
      echo "{
        value    : " . $prestamosUnicos[$i]["pres_cantidad"] . ",
        color    : '" . $colores[$i] . "',
        highlight: '" . $colores[$i] . "',
        label    : '" . $prestamosUnicos[$i]["prod_nombre"] . "'
      },";
    }
    ?>
  ];
  var pieOptions = {
    segmentShowStroke: true,
    segmentStrokeColor: '#fff',
    segmentStrokeWidth: 1,
    percentageInnerCutout: 50,
    animationSteps: 100,
    animationEasing: 'easeOutBounce',
    animateRotate: true,
    animateScale: false,
    responsive: true,
    maintainAspectRatio: false,
    legendTemplate: '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<segments.length; i++){%><li><span style=\'background-color:<%=segments[i].fillColor%>\'></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>',
    tooltipTemplate: '<%=value %> <%=label%>'
  };
  pieChartPrestamos.Doughnut(PieDataPrestamos, pieOptions);
</script>
