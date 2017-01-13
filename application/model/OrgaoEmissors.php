<?php


use Illuminate\Database\Eloquent\Model;

class OrgaoEmissors extends Model
{

    protected $table = 'orgao_emissor';
    protected $primaryKey = 'cd_oe';
    protected $fillable = ['cd_oe', 'desc_oe','sigla'];
    public $timestamps = FALSE;

}
