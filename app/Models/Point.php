<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Point
 *
 * @package App\Models
 * @property int $id
 * @property int $figure_id
 * @property int $order
 * @property string $latitude
 * @property string $longitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Figure $figure
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Point whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Point whereFigureId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Point whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Point whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Point whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Point whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Point whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Point extends Model
{
    protected $fillable = ['figure_id', 'order', 'latitude', 'longitude'];
    protected $table = 'points';

    /**
     * Get figure the point belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function figure()
    {
        return $this->belongsTo(Figure::class);
    }
}
