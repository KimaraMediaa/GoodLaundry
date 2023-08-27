<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class outlet extends Model
{
    protected $table = 'outlets';
    protected $primaryKey = 'idOutlet';
    protected $fillable = [
        'namaOutlet',
        'kontakOutlet',
        'alamatOutlet',
        'latOutlet',
        'longOutlet',
        'vOutlet',
        'idArea',
    ];
}
