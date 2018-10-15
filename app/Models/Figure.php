<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Figure
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property float $perimeter
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Point[] $points
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Figure whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Figure whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Figure whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Figure wherePerimeter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Figure whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Figure whereUserId($value)
 */
class Figure extends Model
{
    protected $table = 'figures';
    protected $fillable = ['user_id', 'name', 'perimeter'];

    /**
     * Get all related points
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function points()
    {
        return $this->hasMany(Point::class);
    }
}
