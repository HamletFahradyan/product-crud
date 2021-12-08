<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Product extends Model
{
    use HasFactory, Sortable;

    /**
     * @var array
     */
    protected $fillable = ['name', 'user_id'];

    /**
     * @var array
     */
    public $sortable = ['name'];
}
