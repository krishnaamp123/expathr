<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormHrjob extends Model
{
    use HasFactory;

    protected $table = 'form_hrjobs';

    protected $fillable = [
        'id_job',
        'id_form',
    ];

    public function hrjob()
    {
        return $this->belongsTo(Hrjob::class, 'id_job');
    }

    public function form()
    {
        return $this->belongsTo(Form::class, 'id_form');
    }
}
