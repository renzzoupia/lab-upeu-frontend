<?php

// Configuración cURL para obtener datos del inventario desde la API
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

$response = curl_exec($curl);

curl_close($curl);
$data = json_decode($response, true);

$inventario = $data["Detalles"];

// Colores para el gráfico
$colores = array("red", "green", "yellow", "aqua", "purple", "blue", "cyan", "magenta", "orange", "gold");

// Filtrar y unificar productos por `prod_id`
$productosUnicos = [];

foreach ($inventario as $producto) {
  if ($_SESSION["perfil"] == 5) {
    // Si el perfil es 5, mostrar todos los productos sin repetidos
    if (!isset($productosUnicos[$producto["prod_id"]])) {
      $productosUnicos[$producto["prod_id"]] = $producto;
    } else {
      $productosUnicos[$producto["prod_id"]]["inve_cantidad_disponible"] += $producto["inve_cantidad_disponible"];
    }
  } elseif ($_SESSION["perfil"] == 3) {
    // Si el perfil es 3, mostrar solo los productos cuyo `inve_labo_id` coincida con `$_SESSION["labo_id"]`
    if ($producto["inve_labo_id"] == $_SESSION["labo_id"]) {
      if (!isset($productosUnicos[$producto["prod_id"]])) {
        $productosUnicos[$producto["prod_id"]] = $producto;
      } else {
        $productosUnicos[$producto["prod_id"]]["inve_cantidad_disponible"] += $producto["inve_cantidad_disponible"];
      }
    }
  }
}

$productosUnicos = array_values($productosUnicos); // Reindexar array
?>

<!--=====================================
PRODUCTOS CON STOCK
======================================-->

<div class="box box-default">

  <div class="box-header with-border">
    <h3 class="box-title">Productos con stock</h3>
  </div>

  <div class="box-body">
    <div class="row">
      <div class="col-md-7">
        <div class="chart-responsive">
          <canvas id="pieChart" height="150"></canvas>
        </div>
      </div>

      <div class="col-md-5">
        <ul class="chart-legend clearfix">
          <?php
          for ($i = 0; $i < count($productosUnicos) && $i < 10; $i++) {
            echo ' <li><i class="fa fa-circle-o text-' . $colores[$i] . '"></i> ' . $productosUnicos[$i]["prod_nombre"] . '</li>';
          }
          ?>
        </ul>
      </div>
    </div>
  </div>

  <div class="box-footer no-padding">
    <ul class="nav nav-pills nav-stacked">
      <?php
      for ($i = 0; $i < count($productosUnicos) && $i < 5; $i++) {
        echo '<li>
                <a>
                ' . $productosUnicos[$i]["prod_nombre"] . '
                <span class="pull-right text-' . $colores[$i] . '">
                ' . $productosUnicos[$i]["inve_cantidad_disponible"] . '
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
  // - PIE CHART -
  // -------------
  var pieChartCanvas = $('#pieChart').get(0).getContext('2d');
  var pieChart = new Chart(pieChartCanvas);
  var PieData = [
    <?php
    for ($i = 0; $i < count($productosUnicos) && $i < 10; $i++) {
      echo "{
        value    : " . $productosUnicos[$i]["inve_cantidad_disponible"] . ",
        color    : '" . $colores[$i] . "',
        highlight: '" . $colores[$i] . "',
        label    : '" . $productosUnicos[$i]["prod_nombre"] . "'
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
  pieChart.Doughnut(PieData, pieOptions);
</script>
