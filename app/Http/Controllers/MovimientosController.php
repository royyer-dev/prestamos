<?php

namespace App\Http\Controllers;

use App\Models\Abono;
use App\Models\Empleado;
use App\Models\Puesto;
use App\Models\Prestamo;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MovimientosController extends Controller
{
    /**
     * Presenta una lista de todos los préstamos registrados en el sistema
     */
    public function prestamosGet(): View
    {
        $prestamos = Prestamo::join("empleado","prestamo.id_empleado","=","empleado.id_empleado")->get();
        return view("movimientos/prestamosGet", [
            "prestamos" => $prestamos,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Prestamos" => url('/movimientos/prestamos')
            ]
        ]);
    }

    public function prestamosAgregarGet(): View
    {
        $haceunanno = (new DateTime("-1 year"))->format("Y-m-d");
        $empleados = Empleado::where("fecha_ingreso","<",$haceunanno)->get()->all();
        $fecha_actual = SupportCarbon::now();
        $prestamosvigentes = Prestamo::where("fecha_inicio_descuento","<=",$fecha_actual)->where("fecha_fin_descuento",">=",$fecha_actual)->get()->all();
        $empleados = array_column($empleados, null,"id_empleado");
        $prestamosvigentes = array_column($prestamosvigentes, null,"id_empleado");
        $empleados=array_diff_key($empleados,$prestamosvigentes);
        return view("movimientos/prestamosAgregarGet", [
            "empleados" => $empleados,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Prestamos" => url('/movimientos/prestamos'),
                "Agregar" => url('/movimientos/prestamos/agregar')
            ]
        ]);
    }
    
    public function prestamosAgregarPost(Request $request)
    {
    $id_empleado=$request->input("id_empleado");
    $monto=$request->input("monto");
    $puesto=Puesto::join("detalle_empleado_puesto", "puesto.id_puesto", "=", "detalle_empleado_puesto.id_puesto")
        ->where("detalle_empleado_puesto.id_empleado","=",$id_empleado)
        ->whereNull("detalle_empleado_puesto.fecha_fin")->first();
    $sueldox6=$puesto->sueldo*6;
    if ($monto>$sueldox6){
        return view("/error",["error"=>"La solicitud excede el monto permitido"]);
    }
    $fecha_solicitud=$request->input("fecha_solicitud");
    $plazo=$request->input("plazo");
    $fecha_aprobacion=$request->input("fecha_aprobacion");
    $tasa_mensual=$request->input("tasa_mensual");
    $pago_fijo=$request->input("pago_fijo");
    $fecha_inicio_descuento=$request->input("fecha_inicio_descuento");
    $fecha_fin_descuento=$request->input("fecha_fin_descuento");
    $saldo=$request->input("saldo");
    $estado=$request->input("estado");
    $prestamo=new Prestamo([
        "id_empleado"=>$id_empleado,
        "fecha_solicitud"=>$fecha_solicitud,
        "monto"=>$monto,
        "plazo"=>$plazo,
        "fecha_aprobacion"=>$fecha_aprobacion,
        "tasa_mensual"=>$tasa_mensual,
        "pago_fijo"=>$pago_fijo,
        "fecha_inicio_descuento"=>$fecha_inicio_descuento,
        "fecha_fin_descuento"=>$fecha_fin_descuento,
        "saldo"=>$saldo,
        "estado"=>$estado,
    ]);
    $prestamo->save();
    return redirect("/movimientos/prestamos"); // redirige al listado de prestamos
    }

    public function abonosGet($id_prestamo): View
    {
    $abonos = Abono::where("id_prestamo", $id_prestamo)->get()->all();


    // Obtener el prestamo con su relacion de empleado
    $prestamo = Prestamo::join("empleado", "empleado.id_empleado", "=", "prestamo.id_empleado")
            ->where("prestamo.id_prestamo", $id_prestamo)->first();
    
    return view('movimientos/abonosGet', [
        'abonos' => $abonos,
        'prestamo' => $prestamo,
        "breadcrumbs" => [
            "Inicio" => url("/"),
            "Prestamos" => url("/movimientos/prestamos"),
            "Abonos" => url("/movimientos/prestamos/abonos"),
        ]
    ]);
    }
    public function abonosAgregarGet($id_prestamo): View
    {
        $prestamo = Prestamo::join("empleado", "empleado.id_empleado", "=", "prestamo.id_empleado")
            ->where("id_prestamo", $id_prestamo)->first();
    
        $abonos = Abono::where("abono.id_prestamo", $id_prestamo)->get();
        $num_abono = count($abonos) + 1;
    
        // Obtener el último abono registrado
        $ultimo_abono = Abono::where("abono.id_prestamo", $id_prestamo)
            ->orderBy("fecha", "desc")
            ->first();
    
        // Si hay un abono previo, tomamos su saldo actual, si no, usamos el saldo del préstamo
        $saldo_actual = $ultimo_abono ? $ultimo_abono->saldo_actual : $prestamo->saldo_actual;
        
        // Cálculo basado en el saldo actual correcto
        $monto_interes = $saldo_actual * ($prestamo->tasa_mensual / 100);
        $monto_cobrado = $prestamo->pago_fijo + $monto_interes;
        $saldo_pendiente = $saldo_actual - $prestamo->pago_fijo;
    
        if ($saldo_pendiente < 0) {
            $pago_fijo = $prestamo->pago_fijo + $saldo_pendiente;
            $saldo_pendiente = 0;
        } else {
            $pago_fijo = $prestamo->pago_fijo;
        }
    
        return view('movimientos/abonosAgregarGet', [
            'prestamo' => $prestamo,
            'num_abono' => $num_abono,
            'pago_fijo' => $pago_fijo,
            'monto_interes' => $monto_interes,
            'monto_cobrado' => $monto_cobrado,
            'saldo_pendiente' => $saldo_pendiente,
            'breadcrumbs' => [
                "Inicio" => url("/"),
                "Prestamos" => url("/movimientos/prestamos"),
                "Abonos" => url("movimientos/prestamos/{$prestamo->id_prestamo}/abonos"),
                "Agregar" => "",
            ]
        ]);
    }

    public function abonosAgregarPost(Request $request)
{
    $id_prestamo = $request->input("id_prestamo");
    $num_abono = $request->input("num_abono");
    $fecha = $request->input("fecha");
    $monto_capital = $request->input("monto_capital");
    $monto_interes = $request->input("monto_interes");
    $monto_cobrado = $request->input("monto_cobrado");
    $saldo_pendiente = $request->input("saldo_pendiente");

    // Crear el nuevo abono
    $abono = new Abono([
        "id_prestamo" => $id_prestamo,
        "num_abono" => $num_abono,
        "fecha" => $fecha,
        "monto_capital" => $monto_capital,
        "monto_interes" => $monto_interes,
        "monto_cobrado" => $monto_cobrado,
        "saldo_actual" => $saldo_pendiente,
    ]);

    $abono->save();

    // No actualices el saldo del préstamo aquí, ya que no existe la columna saldo_actual.
    // En cambio, actualiza el estado del préstamo si es necesario:
    $prestamo = Prestamo::find($id_prestamo);
    if ($saldo_pendiente < 0.01) {
        $prestamo->estado = 1; // Marcar como pagado si el saldo llega a 0
    }
    $prestamo->save();

    return redirect("/movimientos/prestamos/{$id_prestamo}/abonos");
}
}
