<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verify extends Model
{
    use HasFactory;
    protected $table = "verify";
    protected $primaryKey = "id_verify";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'email','code','link','description','send','id_user'
    ];
}