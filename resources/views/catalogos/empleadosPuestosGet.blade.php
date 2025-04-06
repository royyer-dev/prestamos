@extends("components.layout")
@section("content")
@component("components.breadcrumbs",["breadcrumbs"=>$breadcrumbs])
@endcomponent

<div class="row">
    <div class="form-group my-3">
        <h1>Puestos del empleado</h1>
    </div>
    <div>empleado: {{$empleado->nombre}}</div>
    <div class="col"></div>
    <div class="col-auto">
        <a class="btn btn-primary" href='{{url("/empleados/{$empleado->id_empleado}/puestos/cambiar")}}'>cambiar</a>
    </div>
</div>

<table class="table" id="maintable">
<thead>
    <tr>
        <th scope="col">ID</th>
        <th scope="col">Puesto</th>
        <th scope="col">FECHA DE INICIO</th>
        <th scope="col">FECHA DE TERMINO</th>
    </tr>
</thead>
<tbody>
@foreach($detalle_empleado_puesto as $detalle)
    <tr>
        <td class="text-center">{{ $detalle->id_detalle }}</td>
        <td class="text-center">{{ $detalle->puesto }}</td>
        <td class="text-center">{{ $detalle->fecha_inicio }}</td>
        <td class="text-center">{{ $detalle->fecha_fin }}</td>
    </tr>
@endforeach
</tbody>
</table>

<script>

</script>
@endsection