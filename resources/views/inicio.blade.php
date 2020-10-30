@extends('layouts.plantilla')

    @section('contenido')
            <h1>Entry point para inicio</h1>
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
    @endsection
