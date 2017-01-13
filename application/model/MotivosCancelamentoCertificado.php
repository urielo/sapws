<?php

use Illuminate\Database\Eloquent\Model;

class MotivosCancelamentoCertificado extends Model
{
    protected $table = 'motivos_cancelamento_certificado';
    protected $fillable = ['descricao','cod_motivo','tipo'];
}
