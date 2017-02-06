<?php


use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{

    protected $table = 'keys';
    protected $fillable = ['user_id',
        'key',
        'level',
        'ignore_limits',
        'is_private_key',
        'ip_addresses',
        'date_created'];
    public $timestamps = FALSE;

}
