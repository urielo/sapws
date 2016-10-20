<?php


use Illuminate\Database\Eloquent\Model;

class MovimentoCertificado extends Model
{

    protected $table = 'movimento_certificado';
    protected $primaryKey = 'id';
    protected $fillable = [
        
        'certificado_id',
        'dt_carga',
        'dt_retorno',
        'cd_retorno',
        'texto_retrono',
        'status_id',

    ];

    public $timestamps = FALSE;



    public function certificado()
    {
        $this->belongsTo(Certificados::class, 'certificado_id', 'id');
    }




}
