@extends('layouts.plantilla')

    @section('contenido')
            <h2>
        @php
            
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
        @endphp     
        </h2>

<div class="card-columns" style="margin-top: 3%;">
  <div class="card">
    <img class="card-img-top" src=".../100px160/" alt="Card image cap">
    <div class="card-body">
      <h5 class="card-title">Card title that wraps to a new line</h5>
      <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
    </div>
  </div>
  <div class="card p-3">
    <blockquote class="blockquote mb-0 card-body">
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
      <footer class="blockquote-footer">
        <small class="text-muted">
          Someone famous in <cite title="Source Title">Source Title</cite>
        </small>
      </footer>
    </blockquote>
  </div>
  <div class="card">
    <img class="card-img-top" src=".../100px160/" alt="Card image cap">
    <div class="card-body">
      <h5 class="card-title">Card title</h5>
      <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
      <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
    </div>
  </div>
  <div class="card bg-primary text-white text-center p-3">
    <blockquote class="blockquote mb-0">
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat.</p>
      <footer class="blockquote-footer">
        <small>
          Someone famous in <cite title="Source Title">Source Title</cite>
        </small>
      </footer>
    </blockquote>
  </div>
  <div class="card text-center">
    <div class="card-body">
      <h5 class="card-title">Card title</h5>
      <p class="card-text">This card has a regular title and short paragraphy of text below it.</p>
      <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
    </div>
  </div>
  <div class="card">
    <img class="card-img" src=".../100px260/" alt="Card image">
  </div>
  <div class="card p-3 text-right">
    <blockquote class="blockquote mb-0">
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
      <footer class="blockquote-footer">
        <small class="text-muted">
          Someone famous in <cite title="Source Title">Source Title</cite>
        </small>
      </footer>
    </blockquote>
  </div>
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Card title</h5>
      <p class="card-text">This is another card with title and supporting text below. This card has some additional content to make it slightly taller overall.</p>
      <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
    </div>
  </div>
</div>

    @endsection
