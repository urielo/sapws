<?php


use Illuminate\Database\Eloquent\Model;

class Certificados extends Model
{
    protected $table = 'certificados';
    protected $fillable = [
        'idproposta',
        'idseguradora',
        'dt_emissao',
        'dt_inicio_virgencia',
        'iof',
        'pdf_base64',
        'status_id',
    ];

    public $timestamps = FALSE;


    public function custos()
    {
        return $this->belongsToMany(CustoProduto::class, 'certificado_custo', 'certificado_id', 'custo_produto_id');
    }

    public function proposta()
    {
        return $this->belongsTo(Propostas::class, 'idproposta', 'idproposta');
    }

    public function custo_mensal()
    {
        return $this->hasMany(CustoCertificado::class,'certificado_id','id');
    }

   
}