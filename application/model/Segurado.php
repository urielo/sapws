<?php


use Illuminate\Database\Eloquent\Model;

class Segurado extends Model
{

    protected $table = 'segurado';
    protected $primaryKey = 'clicpfcnpj';
    protected $fillable = [
        "clicpfcnpj",
        "clinomerazao",
        "clidtnasc",
        "clicdsexo",
        "clicdestadocivil",
        "clicdprofiramoatividade",
        "cliemail",
        "clidddcel",
        "clinmcel",
        "clidddfone",
        "clinmfone",
        "clinumero",
        "cliendcomplet",
        "clicep",
        'clinmend',
        "clinmcidade",
        "clicduf",
        "idstatus",
        "dtcreate",
        "dtupdate",
        'clinumrg',
        'clidtemissaorg',
        'clicdufemissaorg',
        'cliemissorrg',
        'bairro',
    ];
    public $timestamps = FALSE;
    public $incrementing = false;

    public function veiculo()
    {
        return $this->hasMany(Veiculos::class);
    }

    /**
     * @return string
     */
    public function estadocivil()
    {
        return $this->belongsTo(EstadosCivis::class, 'clicdestadocivil', 'idestadocivil');
    }

    public function profissao()
    {
        return $this->belongsTo(Profissoes::class, 'clicdprofiramoatividade' , 'id_ocupacao');

    }


    public function ramosatividade()
    {
        return $this->belongsTo(RamoAtividades::class, 'clicdprofiramoatividade', 'id_ramo_atividade');

    }
    public function uf()
    {
        return $this->belongsTo(Uf::class, 'clicduf', 'cd_uf');

    }
    
    public function rguf()
    {
        return $this->belongsTo(Uf::class, 'clicdufemissaorg', 'cd_uf');

    }

}
