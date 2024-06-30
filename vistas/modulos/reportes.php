
<div class="content-wrapper">

  <section class="content-header">

    <h1>

      Reportes

    </h1>

    <ol class="breadcrumb">

      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">Productos en stock</li>

    </ol>

  </section>

  <section class="content">


  <div class="box-body">
        
        <div class="row">

           <div class="col-md-6 col-xs-12">
             
            <?php

            include "reportes/productos-en-stock.php";

            ?>

           </div>

            <div class="col-md-6 col-xs-12">
             
            <?php

            include "reportes/productos-en-prestamo.php";

            ?>

           </div>
           <div class="col-md-6 col-xs-12">
             
            <?php

            include "reportes/productos-en-mantenimiento.php";

            ?>

           </div>
          
        </div>

      </div>


  </section>

</div>