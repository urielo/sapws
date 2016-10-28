<?php


use Illuminate\Database\Eloquent\Model;

class Condutor extends Model
{

    protected $table = 'condutor';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'condcpfcnpj',
        'condnomerazao',
        'conddtnasc',
        'condcdsexo',
        'condcdestadocivil',
        'condcdprofiramoatividade',
        'dtcreate',
        'dtupdate',
        'idtipocliente',
    ];
    public $timestamps = FALSE;


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
