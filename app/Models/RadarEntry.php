<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadarEntry extends Model
{
    use HasFactory;

    protected $table = 'radar_entry';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'radar_id',
        'slice_id',
        'slice_position',
        'ring_position',
        'value'
    ];

    // returns the radar associated with the entries
    public function radar()
    {
      return $this->belongsTo(RadarEntry::class);
    }
}
