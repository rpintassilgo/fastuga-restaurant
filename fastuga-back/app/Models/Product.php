<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'products';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'type',
        'description',
        'photo_url',
        'price',
        'custom',
        'name',
        'type',
    ];

    protected $nullable = [ // será que todos os campos são mandatórios ??
        'description' // a descrição é obrigatória ou pode ser null ??
    ];
}