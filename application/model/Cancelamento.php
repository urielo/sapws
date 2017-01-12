<?php

use Illuminate\Database\Eloquent\Model;

class Cancelamento extends Model
{
    protected $table = 'cancelamentos';
    protected $fillable = [

        'cancelado_id',
        'cancelado_desc',
        'motivo_id',
        'created_at',
        'updated_at',

    ];
    public $incrementing = false;

    public function motivo()
    {
        return $this->belongsTo(MotivosCancelamentoCertificado::class,'motivo_id','id');
    }

    
}

