<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entitlements extends Model
{
  protected $table = 'entitlements';
  protected $primaryKey = 'id';

  // returns all users associated with this entitlement
  public function users()
  {
    return $this->belongsToMany(Users::class, 'user_entitlements', 'entitlement_id', 'user_id');
  }
}
