<?php

use Illuminate\Database\Eloquent\Model;

class Parceiros extends Model
{

    protected $table = 'parceiro';
    protected $primaryKey = 'idparceiro';
    protected $fillable = [
        'idparceiro',
        'nomerazao',
        'cpfcnpj',
        'dddfonefixoparceiro',
        'fonefixoparceiro',
        'loginwservicesap',
        'senhawservicesap',
        'ctocomercial',
        'dddfonecomercial',
        'fonecomercial',
        'contatotecnico',
        'dddfonetecnico',
        'fonetecnico',
        'loginplataforma',
        'senhaplataforma',
    ];

    public $timestamps = FALSE;

    public function corretor()
    {
        $this->hasMany(Corretores::class, 'idparceiro', 'idparceiro');
    }


    public function keys()
    {
        return $this->hasOne(ApiKey::class,'user_id','idparceiro');
    }






}
