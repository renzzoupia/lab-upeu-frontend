<?php

// Configuración cURL para obtener datos de mantenimiento desde la API
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
$mantenimientoData = json_decode($response, true);

$mantenimientos = $mantenimientoData["Detalles"];

// Colores para el gráfico
$colores = array("blue", "cyan", "magenta", "orange", "gold");

// Inicializar contadores para "Mantenimiento" y "Solucionado"
$estadoMantenimiento = [];
foreach ($mantenimientos as $mantenimiento) {
  $prodId = $mantenimiento["prod_id"];
  if (!isset($estadoMantenimiento[$prodId])) {
    $estadoMantenimiento[$prodId] = [
      "prod_nombre" => $mantenimiento["prod_nombre"],
      "Mantenimiento" => 0,
      "Solucionado" => 0
    ];
  }
  if ($mantenimiento["mant_estado"] == "Mantenimiento") {
    $estadoMantenimiento[$prodId]["Mantenimiento"]++;
  } elseif ($mantenimiento["mant_estado"] == "Solucionado") {
    $estadoMantenimiento[$prodId]["Solucionado"]++;
  }
}

?>

<!--=====================================
PRODUCTOS EN MANTENIMIENTO
======================================-->

<div class="box box-default">

  <div class="box-header with-border">
    <h3 class="box-title">Productos en mantenimiento</h3>
  </div>

  <div class="box-body">
    <div class="chart-responsive">
      <canvas id="pieChartMantenimiento" height="150"></canvas>
    </div>
    <ul class="chart-legend clearfix">
      <?php
      foreach ($estadoMantenimiento as $producto) {
        echo ' <li><i class="fa fa-circle-o text-red"></i> ' . $producto["prod_nombre"] . ' (Mantenimiento: ' . $producto["Mantenimiento"] . ', Solucionado: ' . $producto["Solucionado"] . ')</li>';
      }
      ?>
    </ul>
  </div>
</div>

<script>
  // -------------
  // - PIE CHART MANTENIMIENTO -
  // -------------
  var pieChartCanvasMantenimiento = $('#pieChartMantenimiento').get(0).getContext('2d');
  var pieChartMantenimiento = new Chart(pieChartCanvasMantenimiento);
  var PieDataMantenimiento = [
    <?php
    foreach ($estadoMantenimiento as $producto) {
      echo "{
        value    : " . $producto["Mantenimiento"] . ",
        color    : '" . $colores[0] . "',
        highlight: '" . $colores[0] . "',
        label    : 'Mantenimiento - " . $producto["prod_nombre"] . "'
      },";
      echo "{
        value    : " . $producto["Solucionado"] . ",
        color    : '" . $colores[1] . "',
        highlight: '" . $colores[1] . "',
        label    : 'Solucionado - " . $producto["prod_nombre"] . "'
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
  pieChartMantenimiento.Doughnut(PieDataMantenimiento, pieOptions);
</script>
