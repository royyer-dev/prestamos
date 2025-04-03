@extends("components.layout")
@section("content")
@component("components.breadcrumbs",["breadcrumbs"=>$breadcrumbs])
@endcomponent
    <h1>Préstamos de {{ $empleado->nombre }}</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Monto</th>
                <th>Plazo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($prestamos as $prestamo)
                <tr>
                    <td>{{ $prestamo->id_prestamo }}</td>
                    <td>{{ $prestamo->monto }}</td>
                    <td>{{ $prestamo->plazo }}</td>
                    <td>{{ $prestamo->estado }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection