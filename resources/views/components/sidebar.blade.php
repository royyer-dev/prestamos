@if(Auth::check())
<nav class="sidebar nav flex-column pt-5">
    <a href="{{url('/')}}" class="nav-link">Inicio</a>
    <a href="{{ url('/catalogos/puestos') }}" class="nav-link">Puestos</a>
    <a href="{{ url('/empleados') }}" class="nav-link">Empleados</a>
    <a href="{{ url('/movimientos/prestamos') }}" class="nav-link">Préstamos</a>
    <a href="{{ url('/reportes/') }}" class="nav-link">Reportes</a>
    <a href="#" onclick="event.preventDefault(); document.getElementByID('logout-form').submit();" class="nav-link">Salir</a>
</nav>
@endif