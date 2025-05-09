<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobCategory extends Model
{
    use HasFactory;
    protected $table = 'job_categories';

    protected $primaryKey = 'category_id';
    protected $fillable = ['category_name', 'description'];
}
