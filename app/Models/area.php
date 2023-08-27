<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class area extends Model
{
    protected $table = 'areas';
    protected $primaryKey = 'idArea';
    protected $fillable = ['idArea', 'namaArea', 'vArea'];
}
