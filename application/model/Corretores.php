<?php


use Illuminate\Database\Eloquent\Model;

class Corretores extends Model
{

    protected $table = 'corretor';
    protected $primaryKey = 'idcorretor';
    protected $fillable = [
        'corresusep',
        'corrcpfcnpj',
        'corrnomerazao',
        'corrdtnasc',
        'corrcdsexo',
        'corrcdestadocivil',
        'corrcdprofiramoatividade',
        'corremail',
        'corrdddcel',
        'corrnmcel',
        'corrdddfone',
        'corrnmfone',
        'corrnmend',
        'corrnumero',
        'correndcomplet',
        'corrcep',
        'corrnmcidade',
        'corrcduf',
        'idstatus',
        'corrcomissaopadrao',
        'idcorretor',
        'idparceiro',
        'corrcomissaomin'
    ];
    public $timestamps = FALSE;

    public function cotacoes()
    {
        return $this->hasMany(Cotacoes::class, 'idcorretor','idcorretor');
    }
    public function parceiro()
    {
        return $this->belongsTo(Parceiros::class, 'idparceiro','idparceiro');
    }


    public function estadocivil()
    {
        return $this->belongsTo(EstadosCivis::class, 'corrcdestadocivil', 'idestadocivil');
    }


    public function uf()
    {
        return $this->belongsTo(Uf::class, 'corrcduf', 'cd_uf');

    }
}

