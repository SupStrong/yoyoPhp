<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;
    protected $guarded = [];
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
}
