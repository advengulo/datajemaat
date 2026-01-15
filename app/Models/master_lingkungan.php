<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_lingkungan extends Model
{
    use HasFactory;
    protected $fillable = [
        'nomor_lingkungan', 'nama_lingkungan','nama_snk'
    ];
    public $timestamps = true;
}
