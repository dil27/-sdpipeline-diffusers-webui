<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkpoint extends Model
{
    use HasFactory;

    protected $table = 'checkpoints';

    protected $fillable = [
        'checkpoint',
        'checkpoint_type'
    ];

    public $timestamps = true;
}