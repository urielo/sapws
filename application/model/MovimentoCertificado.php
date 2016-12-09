<?php


use Illuminate\Database\Eloquent\Model;

class MovimentoCertificado extends Model
{

    protected $table = 'movimento_certificado';
    protected $primaryKey = 'id';
    protected $fillable = [

        "datas_enviadas",
        "datas_recebidas",
        "dt_envio",
        "dt_retorno",
        "tipo_envio",
        "status_id",

    ];

    public $timestamps = FALSE;



    public function certificado()
    {
        $this->belongsTo(Certificados::class, 'certificado_id', 'id');
    }




}
