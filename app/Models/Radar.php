<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Radar extends Model
{
    use HasFactory;

    protected $table = 'radar';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'moderator_id',
        'description'
    ];

    // returns all users working on the radar
    public function contributors()
    {
      return $this->belongsToMany(Users::class, 'working_on', 'radar_id', 'user_id');
    }

    // returns all entries associated with the radar
    public function entries()
    {
      return $this->hasMany(RadarEntry::class);
    }
}
