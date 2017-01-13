<?php

use Illuminate\Database\Eloquent\Model;

class FipeAnoValor extends Model
{
    protected $table = 'fipeanovalor';
    protected $primaryKey  = 'codefipe';
    protected $fillable = ["ano","codefipe","idcombustivel","valor"];
    public $timestamps = FALSE;
    public $incrementing = false;
    
    public function fipe()
    {
        return $this->belongsTo(Fipes::class,  'codefipe', 'codefipe');
    }
}
