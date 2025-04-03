@extends("components.layout")
@section("content")
@component("components.breadcrumbs",["breadcrumbs"=>$breadcrumbs])
@endcomponent
<h1>Agregar abono del préstamo {{$prestamo->id_prestamo}}</h1>
<div class="card">
    <div class="row card-body">
        <div class="col-2">EMPLEADO</div>
        <div class="col">{{$prestamo->nombre}}</div>
    </div>

    <div class="row card-body">
        <div class="col-2">
            ID PRESTAMO
        </div>
        <div class="col-2">
            {{$prestamo->id_prestamo}}
        </div>
        <div class="col-2">
            FECHA APROBACION
        </div>
        <div class="col-2">
            {{$prestamo->fecha_aprobacion}}
        </div>
        <div class="col-2">
            MONTO PRESTADO
        </div>
        <div class="col-2">
            {{$prestamo->monto}}
        </div>
    </div>
</div>

<form method="post" action="{{url("/prestamos/{$prestamo->id_prestamo}/abonos/agregar")}}">
@csrf <!-- Sirve para validar que la petición de los datos enviados provengan del formulario actual -->
    <input type="hidden" name="id_prestamo" value="{{$prestamo->id_prestamo}}">

    <div class="row my-4">
        <div class="form-group mb-3 col-6">
            <label for="num_abono">Número de abono:</label>
            <input type="number" value="{{$num_abono}}" name="num_abono" id="num_abono" class="form-control" required>
        </div>
        <div class="form-group mb-3 col-6">
            <label for="fecha">Fecha</label>
            <input type="date" value="{{now()->format('Y-m-d')}}" name="fecha" id="fecha" class="form-control" required>
        </div>
    </div>

    <div class="row">
        <div class="form-group mb-3 col-6"><label for="monto capital">Monto a capital</label>
        <input type="number" value="{{number_format($pago_fijo, 2, '.', '')}}" step="0.01" name="monto_capital" id="monto_capital" class="form-control" required></div>

        <div class="form-group mb-3 col-6">
            <label for="monto">Monto interés:</label>
            <input type="number" value="{{number_format($monto_interes, 2, '.', '')}}" step="0.01" name="monto_interes" id="monto_interes" class="form-control" required>
        </div>
    </div>

    <div class="row">
        <div class="form-group mb-3 col-6">
            <label for="monto_cobrado">Monto cobrado</label>
            <input type="number" value="{{number_format($monto_cobrado, 2, '.', '')}}" step="0.01" name="monto_cobrado" id="monto_cobrado" class="form-control" required>
        </div>
        <div class="form-group mb-3 col-6">
            <label for="saldo_pendiente">Saldo actual</label>
            <input type="number" value="{{ isset($saldo_pendiente) ? number_format($saldo_pendiente, 2, '.', '') : '0.00' }}" step="0.01" name="saldo_pendiente" id="saldo_pendiente" class="form-control" required>
        </div>
    </div>

    <div class="row">
        <div class="col"></div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </div>
</form>
@endsection