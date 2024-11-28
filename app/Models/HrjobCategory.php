<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrjobCategory extends Model
{
    use HasFactory;

    protected $table = 'hrjob_categories';

    protected $fillable = [
        'category_name'
    ];
}
