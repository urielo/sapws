<?php


use Illuminate\Database\Eloquent\Model;

class Proprietario extends Model
{

    protected $table = 'proprietario';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'proprcpfcnpj',
        'proprnomerazao',
        'proprdtnasc',
        'proprcdsexo',
        'proprcdestadocivil',
        'proprcdprofiramoatividade',
        'propremail',
        'proprdddcel',
        'proprnmcel',
        'proprdddfone',
        'proprnmfone',
        'proprnmend',
        'proprnumero',
        'proprendcomplet',
        'proprcep',
        'proprnmcidade',
        'proprcduf',
        'idstatus',
        'dtcreate',
        'dtupdate',
        'cdreldepsegurado',
        'descreldepsegurado',
        'idtipocliente',
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
