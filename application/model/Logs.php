<?php
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{

    protected $table = 'logs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'uri',
        'method',
        'params',
        'api_key',
        'ip_address',
        'time',
        'rtime',
        'authorized',
        'response_code',
        'dtcreate',
    ];
    
    public $timestamps = FALSE;

    

    


}
