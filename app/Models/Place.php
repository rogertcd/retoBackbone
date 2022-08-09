<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

class Place extends Model
{
    use HasFactory, HybridRelations;

    protected $connection = 'mongodb';
    protected $collection = 'zip-codes';
    protected $primaryKey = '_id';
}
