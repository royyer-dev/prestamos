<?php

namespace App\Http\Controllers;
use App\Models\Abono;
use App\Models\Prestamo;
use DateTime;
use Francerz\PowerData\Index;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportesController extends Controller
{
    public function indexGet(Request $request)
    {
        return view("reportes.indexGet",[
            "breadcrumbs"=>[
                "Inicio"=>url("/"),
                "Reportes"=>url("/reportes/prestamos-activos")
            ]
        ]);
    }

    public function prestamosActivosGet(Request $request)
    {
    $fecha = Carbon::now()->format("Y-m-d"); // Carbon Fecha actual en formato de texto
    $fecha = $request->query("fecha", $fecha);

    $prestamos = Prestamo::join("empleado", "empleado.id_empleado", "=", "prestamo.id_empleado")
        ->leftJoin("abono", "abono.id_prestamo", "=", "prestamo.id_prestamo")
        ->select("prestamo.id_prestamo", "empleado.nombre", "prestamo.monto")
        ->selectRaw("SUM(abono.monto_capital) AS total_capital")
        ->selectRaw("SUM(abono.monto_interes) AS total_interes")
        ->selectRaw("SUM(abono.monto_cobrado) AS total_cobrado")
        ->groupBy("prestamo.id_prestamo", "empleado.nombre", "prestamo.monto")
        ->where("prestamo.fecha_inicio_descuento", "<=", $fecha)
        ->where("prestamo.fecha_fin_descuento", ">=", $fecha)
        ->get()->all();

    // var_dump($prestamos);

    return view("/reportes/prestamosActivosGet", [
        "fecha" => $fecha,
        "prestamos" => $prestamos,
        "breadcrumbs" => [
            "Inicio" => url("/"),
            "Reportes" => url("/reportes/prestamos-activos")
        ]
    ]);
    }

    public function matrizAbonosGet(Request $request)
    {
        // Establecer fechas por defecto (año actual)
        $fecha_inicio = Carbon::now()->startOfYear()->format("Y-m-d");
        $fecha_fin = Carbon::now()->endOfYear()->format("Y-m-d");
    
        // Obtener fechas del request si existen
        $fecha_inicio = $request->query("fecha_inicio", $fecha_inicio);
        $fecha_fin = $request->query("fecha_fin", $fecha_fin);
    
        // Consulta base
        $query = Abono::join("prestamo", "prestamo.id_prestamo", "=", "abono.id_prestamo")
            ->join("empleado", "empleado.id_empleado", "=", "prestamo.id_empleado")
            ->select(
                "prestamo.id_prestamo",
                "empleado.nombre",
                "abono.monto_cobrado",
                "abono.fecha"
            )
            ->whereBetween("abono.fecha", [$fecha_inicio, $fecha_fin])
            ->orderBy("abono.fecha");
    
        // Obtener resultados
        $abonos = $query->get()->toArray();
    
        // Formatear fechas a año-mes
        foreach ($abonos as &$abono) {
            $abono["fecha"] = Carbon::parse($abono["fecha"])->format("Y-m");
        }
    
        // Crear índice para la vista
        $abonosIndex = new Index($abonos, ["id_prestamo", "fecha"]);
    
        return view("reportes.matrizAbonosGet", [
            "abonosIndex" => $abonosIndex,
            "fecha_inicio" => $fecha_inicio,
            "fecha_fin" => $fecha_fin,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Reportes" => url("/reportes"), // O la URL de la página principal de reportes
                "Matriz de Abonos" => null, // Indica la página actual
            ]
        ]);
    }
}