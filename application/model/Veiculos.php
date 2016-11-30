<?php


use Illuminate\Database\Eloquent\Model;

class Veiculos extends Model
{

    protected $table = 'veiculosegurado';
    protected $primaryKey = 'veicid';
    protected $fillable = [
        "veiccodfipe",
        "veicano",
        "veictipocombus",
        "veicplaca",
        "clicpfcnpj",
        "veicautozero",
        "veiccdutilizaco",
        "veiccdveitipo",
        "veicmunicplaca",
        "veiccdufplaca",
        "veicrenavam",
        "veicanorenavam",
        "veicchassi",
        "veicchassiremar",
        "veicleilao",
        "veicalienado",
        "veicacidentado",
        "propcpfcnpj",
        "condcpfcnpj",
        "idstatus",
        "dtcreate",
        "dtupdate",
        "veicor",
        "veianofab",
    ];
    public $timestamps = FALSE;

    public function segurado()
    {
        return $this->belongsTo(Segurado::class, 'clicpfcnpj', 'clicpfcnpj');
    }

    public function fipe()
    {
        return $this->belongsTo(Fipes::class, 'veiccodfipe', 'codefipe');
    }

    /**
     * @return array
     */
    
    public function fipe_ano_valor()
    {
        return $this->hasMany(FipeAnoValor::class, 'codefipe', 'veiccodfipe');
    }

    public function combustivel()
    {
        return $this->belongsTo(Combustivel::class, 'veictipocombus', 'id_auto_comb');
    }

    public function proprietario()
    {
        return $this->belongsTo(Proprietario::class, 'proprcpfcnpj','id');
    }
    public function condutor()
    {
        return $this->hasOne(Condutor::class, 'condcpfcnpj','condcpfcnpj');
    }


}
