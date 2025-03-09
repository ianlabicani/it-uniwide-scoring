<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    /** @use HasFactory<\Database\Factories\CriteriaFactory> */
    use HasFactory;

    protected $fillable = ['competition_id', 'name', 'percentage'];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

}
