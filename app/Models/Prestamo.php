<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }

    use HasFactory;
    protected $table = 'prestamo';
    protected $primaryKey = 'id_prestamo';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $id_empleado;
    protected $fecha_solicitud;
    protected $monto;
    protected $plazo;
    protected $fecha_aprobacion;
    protected $tasa_mensual;
    protected $pago_fijo;
    protected $fecha_inicio_descuento;
    protected $fecha_fin_descuento;
    protected $saldo;
    protected $estado;
    protected $fillable = ['id_empleado', 'fecha_solicitud', 'monto', 'plazo', 'fecha_aprobacion', 'tasa_mensual', 'pago_fijo', 'fecha_inicio_descuento', 'fecha_fin_descuento', 'saldo', 'estado'];
    public $timestamps = false;
}
