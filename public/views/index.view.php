<main>

	<div class="container">
	  <div class="row">
	    <div class="col-6 col-sm-8">
			  <article class="principal" id="articulos">
    <h1>Página Principal</h1>
    <h2><?php
    $dia = date ('N');
    switch ($dia) {
      case '1':
        echo "Hoy es lunes, el lado bueno es que la semana tiene un solo Lunes.";
        break;
      case '2':
        echo "Hoy es martes, Ríe y el mundo reirá contigo, ronca y dormirás solo. Anthony Burgess.";
        break;
      case '3':
        echo "Hoy es miercoles, a las 12 del mediodia la semana se parte al medio.";
        break;
      case '4':
        echo "Hoy es jueves, ya falta menos para el fin de semana.";
        break;
      case '5':
        echo "Hoy es viernes, es el dia para sonreir.";
        break;
      case '6':
        echo "Hoy es sabado, Del Griego Shabbaton, a su vez del Hebreo Shabbat, esto significa “Reposo”..";
        break;
      case '7':
        echo "Hoy es Domingo, NO deberias estar trabajando!!.";
        break;
      default:
        echo "Hoy es Lunes, Fuerza que recién empieza la semana.";
        break;
    }

     ?></h2>
  </article>
		</div>
	    <div class="col-6 col-sm-4">
			<?php if ($datos->getAutenticado() != 1) {
				echo '<h2>Usuario sin Loguearse</h2>';
			} ?>
	    </div>

	    <!-- Force next columns to break to new line -->
	    <div class="w-100 d-none d-md-block"></div>

	    <div class="col-6 col-sm-8">
		
	    </div>
	    <div class="col-6 col-sm-4">
		
	    </div>
	  </div>
	</div>

	
</main>