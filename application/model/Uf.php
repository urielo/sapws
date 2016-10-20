<?php


use Illuminate\Database\Eloquent\Model;

class Uf extends Model
{

    protected $table = 'uf';
    protected $primaryKey = 'cd_uf';
    protected $fillable = ['cd_uf', 'nm_uf'];
    public $timestamps = FALSE;

}
