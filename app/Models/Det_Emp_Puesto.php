<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Det_Emp_Puesto extends Model
{
    use HasFactory;
    protected $table = 'detalle_empleado_puesto';
    protected $primaryKey = 'id_detalle';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $id_empleado;
    protected $id_puesto;
    protected $fecha_inicio;
    protected $fecha_fin;
    protected $fillable = ['id_empleado','id_puesto', 'fecha_inicio', 'fecha_fin'];
    public $timestamps = false;
}
