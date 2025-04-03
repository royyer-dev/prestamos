<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abono extends Model
{
    use HasFactory;
    protected $table = 'abono';
    protected $primaryKey = 'id_abono';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $id_prestamo;
    protected $num_abono;
    protected $fecha;
    protected $monto_capital;
    protected $monto_interes;
    protected $monto_cobrado;
    protected $saldo_actual;
    protected $fillable = ['id_prestamo', 'num_abono', 'fecha', 'monto_capital', 'monto_interes', 'monto_cobrado', 'saldo_actual'];
    public $timestamps = false;
}
