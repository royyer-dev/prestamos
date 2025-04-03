<?php

namespace App\Http\Controllers;

Use Datetime;
use App\Models\Puesto;
use App\Models\Empleado;
use App\Models\Det_Emp_Puesto;
use App\Models\Prestamo;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogosController extends Controller
{
    public function home():View
    {
        return view('home',["breadcrumbs"=>[]]);
    }
    
    public function puestosGet():View
    {
       $puestos = Puesto::all();
        return view('catalogos/puestosGet', [
            'puestos' => $puestos,
            "breadcrumbs" => [
                "inicio" => url("/"),
                "Puestos" => url("/catalogos/puestos")
            ]
        ]);
    }

    public function puestosAgregarGet():View
    {
        return view('catalogos/puestosAgregarGet', [
            "breadcrumbs" => [
                "inicio" => url("/"),
                "Puestos" => url("/catalogos/puestos"),
                "Agregar" => url("/catalogos/puestos/agregar")
            ]
        ]);
    }

    public function puestosAgregarPost(Request $request)
    {
        $nombre = $request->input("nombre");
        $sueldo = $request->input("sueldo");
        $puesto = new Puesto([
            "nombre" => strtoupper($nombre),
            "sueldo" => $sueldo
        ]);
        $puesto->save();
        return redirect("/catalogos/puestos");
    }

    public function empleadosGet():View
    {
        $empleados = Empleado::all();
        return view('catalogos/empleadosGet', [
            'empleados' => $empleados,
            "breadcrumbs" => [
                "inicio" => url("/"),
                "Empleados" => url("/empleados")
            ]
        ]);
    }

    public function empleadosAgregarGet():View
    {
        $puestos = Puesto::all();
        return view('catalogos/empleadosAgregarGet', [
            'puestos' => $puestos,
            "breadcrumbs" => [
                "inicio" => url("/"),
                "Empleados" => url("/empleados"),
                "Agregar" => url("/empleados/agregar")
            ]
        ]);
    }

    public function empleadosAgregarPost(Request $request)
    {
        $nombre = $request->input("nombre");
        $fecha_ingreso = $request->input("fecha_ingreso");
        $activo = $request->input("activo");
        $empleado = new Empleado([
            "nombre" => strtoupper($nombre),
            "fecha_ingreso" => $fecha_ingreso,
            "activo" => $activo
        ]);
        $empleado->save();
        $puesto = new Det_Emp_Puesto([
            "id_empleado" => $empleado->id_empleado,
            "id_puesto" => $request->input("puesto"),
            "fecha_inicio" => $empleado->fecha_ingreso
        ]);
        $puesto->save();
        return redirect("/empleados");
    }

    public function empleadosPuestosGet(Request $request, $id_empleado)
    {
        $puestos = Puesto::join("detalle_empleado_puesto", "puesto.id_puesto", "=", "detalle_empleado_puesto.id_puesto")
            ->select("detalle_empleado_puesto.*", "puesto.nombre as puesto", "puesto.sueldo") // Corrección aquí
            ->where("detalle_empleado_puesto.id_empleado", "=", $id_empleado)
            ->get();
    
        $empleado = Empleado::find($id_empleado);
    
        return view('catalogos/empleadosPuestosGet', [
            'puestos' => $puestos,
            'empleado' => $empleado,
            "breadcrumbs" => [
                "inicio" => url("/"),
                "Empleados" => url("/empleados"),
                "Puesto" => url("/empleados/{$id_empleado}/puestos")
            ]
        ]);
    }
    public function empleadosPuestosCambiarGet(Request $request, $id_empleado): View
    {
        $empleado = Empleado::find($id_empleado);
        $puestos = Puesto::all();

        return view('catalogos/empleadosPuestosCambiarGet', [
            "puestos" => $puestos,
            "empleado" => $empleado,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Empleados" => url("/empleados"),
                "Puestos" => url("/empleados/{$id_empleado}/puestos"),
                "Cambiar" => url("/empleados/{$id_empleado}/puestos/cambiar")
            ]
        ]);
    }


    public function empleadosPuestosCambiarPost(Request $request, $id_empleado)
    {
        $fecha_inicio = $request->input("fecha_inicio");
        $fecha_fin = (new DateTime($fecha_inicio))->modify("-1 day");
        $anterior = Det_Emp_Puesto::where("id_empleado", $id_empleado)
            ->whereNull("fecha_fin")
            ->update(["fecha_fin" => $fecha_fin->format("Y-m-d")]);
        $puesto = new Det_Emp_Puesto([
            "id_empleado" => $id_empleado,
            "id_puesto" => $request->input("puesto"),
            "fecha_inicio" => $fecha_inicio,
        ]);
        
        $puesto->save();

        return redirect("/empleados/{$id_empleado}/puestos");
    }

    public function empleadosPrestamosGet(Request $request, $id_empleado)
    {
    $prestamos = Prestamo::where('id_empleado', $id_empleado)->get();
    $empleado = Empleado::find($id_empleado);
    return view('catalogos/empleadosPrestamosGet', [
        'prestamos' => $prestamos,
        'empleado' => $empleado,
        'breadcrumbs' => [
            'Inicio' => url('/'),
            'Empleados' => url('/empleados'),
            'Prestamos' => url("/empleados/{$id_empleado}/prestamos")
        ]
    ]);
    }
    
    public function prestamosGet(): View
    {
        $prestamos = Prestamo::all();
        return view("catalogos/prestamosGet", [
            "prestamos" => $prestamos,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Préstamos" => url("/catalogos/prestamos")
            ]
        ]);
    }

    public function prestamosAgregarGet(): View
    {
        $empleados = Empleado::all();
        return view("catalogos/prestamosAgregarGet", [
            "empleados" => $empleados,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Préstamos" => url("/catalogos/prestamos"),
                "Agregar" => url("/catalogos/prestamos/agregar")
            ]
        ]);
    }

    public function prestamosAgregarPost(Request $request)
    {
        $prestamo = new Prestamo($request->all());
        $prestamo->save();
        return redirect("/catalogos/prestamos");
    }
}



