<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    use HasFactory;

    protected $fillable = [
        'nameEmp',
        'surnameEmp',
        'email',
        'birthday',
        'sexe',
        'Tel',
        'poste',
        'salaire',
    ]; 
}
