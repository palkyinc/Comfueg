@extends('layouts.plantilla')
@section('contenido')
@can('alta_contrato')
@php
$mostrarSololectura = true;
@endphp
<h3>Agregar nuevo contrato</h3>
    <div id="cliente">
        <div v-bind:class="class_cliente_seleccionado" role="alert">
            Cliente: ID: @{{id_cliente}} | @{{apellido}}, @{{nombre}} | @{{cod_area_cel}} 15 @{{celular}}
            <button type="button" class="btn btn-secondary">Editar</button>
        </div>
        <div v-bind:class="class_formulario_cliente">
        <h4>Cliente:</h4>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <div class="input-group mb-3">
                        <input  type="text" 
                                v-model="id_cliente"
                                placeholder="ID Genesys"
                                v-bind:class="class_id_cliente">
                    </div>    
                    <p>@{{mensaje_id_cliente}}</p>
                </div>
                <div class="form-group col-md-2">
                    <label for=""></label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="nombre">Nombre: </label>
                    <input type="text" v-model="nombre" v-bind:class="class_nombre">
                    <p>@{{mensaje_nombre}}</p>
                </div>
                <div class="form-group col-md-4">
                    <label for="apellido">Apellido: </label>
                    <input type="text" v-model="apellido" v-bind:class="class_apellido">
                    <p>@{{mensaje_apellido}}</p>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="cod_area_tel">Código de área Tel: </label>
                    <input v-bind:class="class_cod_area_tel" placeholder="Sin Ceros" v-model="cod_area_tel">
                    <p>@{{mensaje_cod_area_tel}}</p>
                </div>
                <div class="form-group col-md-4">
                    <label for="telefono">Teléfono: </label>
                    <input type="text" v-model="telefono" v-bind:class="class_telefono">
                    <p>@{{mensaje_telefono}}</p>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="cod_area_cel">Código de área Cel: </label>
                    <input v-bind:class="class_cod_area_cel" name="cod_area_cel" placeholder="Sin Ceros" v-model="cod_area_cel">
                    <p>@{{mensaje_cod_area_cel}}</p>
                </div>
                <div class="form-group col-md-1">
                    <label for="prefijo">Prefijo</label>
                    <input type="text" name="prefijo" v-bind:value="15" v-bind:class="class_prefijo" disabled>
                </div>
                <div class="form-group col-md-4">
                    <label for="celular">Celular: </label>
                    <input type="text" v-model="celular"v-bind:class="class_celular">
                    <p>@{{mensaje_celular}}</p>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="email">Correo Electrónico: </label>
                    <input type="email" v-model="email" v-bind:class="class_email">
                    <p>@{{mensaje_email}}</p>
                </div>
            </div>
                <p>@{{mensaje_cliente}}</p>
                <button v-bind:class="class_guardar_button_cliente" v-on:click="guardar_cliente">Guardar</button>
                <button v-bind:class="class_cambiar_button_cliente" v-on:click="cambiar_cliente">Cambiar</button>
                <button v-bind:class="class_seleccionar_button_cliente" v-on:click="seleccionar_cliente">Seleccionar</button>
        </div>
    </div>

    @section('javascript')
                        <script src="vue.js/vendor/js/{{$vuejs}}"></script>
                        <script> website = '{{$website}}'</script>
                        <script src="vue.js/resources/js/altaContrato.js"></script>
                    @endsection

@endcan
@include('sinPermiso')
@endsection