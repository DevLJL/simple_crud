<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
  use HasFactory;

  protected $table = 'cursos';
  protected $fillable = [
    'name',
    'duration',
  ];

  public function scopeName($query, $name)
  {
    return $query->where('cursos.name', 'LIKE', '%'.$name.'%');
  }

  public function scopeDuration($query, $duration)
  {
    return $query->where('cursos.duration', $duration);
  }
}
