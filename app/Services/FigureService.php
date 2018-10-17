<?php
/**
 * Created by PhpStorm.
 * User: nikolaygolub
 * Date: 15.10.2018
 * Time: 15:55
 */

namespace App\Services;

use App\Models\Figure;
use App\Models\Point;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class FigureService
 * @package App\Services
 */
class FigureService
{
    const EARTH_RADIUS = 6372795;

    /**
     * Create the new Figure
     *
     * @param array $attributes
     * @param array $points
     *
     * @return Figure
     */
    public function create(array $attributes, array $points) : Figure
    {
        $attributes['perimeter'] = $this->measureFigureDistance($points);
        $figure = Figure::create($attributes);
        $this->replaceFigurePoints($points, $figure->id);
        return Figure::with(['points' => function (HasMany $query) {
            $query->orderBy('order');
        }])->find($figure->id);
    }

    /**
     * Attach points to figure. All figure points will be removed before attaching
     *
     * @param array $points
     * @param int   $figureId
     */
    protected function replaceFigurePoints(array $points, int $figureId)
    {
        foreach ($points as $key => $point) {
            $point['order'] = $key;
            $point['figure_id'] = $figureId;
            Point::create($point);
        }
    }

    /**
     * Update the Figure
     *
     * @param array $attributes
     * @param array $points
     * @param int   $figureId
     *
     * @return bool
     */
    public function update(array $attributes, array $points, int $figureId) : bool
    {
        $isUpdated = Figure::find($figureId)->update($attributes);
        $this->replaceFigurePoints($points, $figureId);
        return $isUpdated;
    }

    /**
     * @param int    $userId
     * @param string $sortType
     * @param string $orderBy
     *
     * @param int    $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getFiguresWithSorting(int $userId, $sortType = null, $orderBy = null, int $perPage = 10) : LengthAwarePaginator
    {
        $sortType = $sortType ?? 'asc';
        $orderBy = $orderBy ?? 'id';
        return Figure::with(['points' => function (HasMany $query) {
            $query->orderBy('order');
        }])->orderBy($orderBy, $sortType)
            ->whereUserId($userId)
            ->paginate($perPage);
    }

    /**
     * Delete figure from database
     *
     * @param int $figureId
     *
     * @return bool|null
     */
    public function delete(int $figureId)
    {
        return Figure::find($figureId)->delete();
    }

    /**
     * Measure the distance between all provided points.
     *
     * @param array $points
     *
     * @return float - sum of all distances (meters)
     */
    public function measureFigureDistance(array $points) : float
    {
        $distances = [];
        $previousPoint = null;
        $points[] = array_first($points);
        foreach ($points as $key => $point) {
            if ($previousPoint) {
                $distances[] =  $this->calculateTheDistance($previousPoint, $point);
            }
            $previousPoint = $point;
        }
        return array_sum($distances);
    }

    /**
     * calculate the distance between two points
     *
     *
     * @param $firstPoint [
     *       'latitude' => float value latitude,
     *       'longitude' => float value longitude,
     * ]
     * @param $secondPoint[
     *       'latitude' => float value latitude,
     *       'longitude' => float value longitude,
     * ]
     *
     * @return float - distance between two points (meters)
     */
    public function calculateTheDistance(array $firstPoint, array $secondPoint) : float
    {
        $lat1 = $firstPoint['latitude'] * M_PI / 180;
        $lat2 = $secondPoint['latitude'] * M_PI / 180;
        $long1 = $firstPoint['longitude'] * M_PI / 180;
        $long2 = $secondPoint['longitude'] * M_PI / 180;

        $cl1 = cos($lat1);
        $cl2 = cos($lat2);
        $sl1 = sin($lat1);
        $sl2 = sin($lat2);
        $delta = $long2 - $long1;
        $cdelta = cos($delta);
        $sdelta = sin($delta);

        $y = sqrt(pow($cl2 * $sdelta, 2) + pow($cl1 * $sl2 - $sl1 * $cl2 * $cdelta, 2));
        $x = $sl1 * $sl2 + $cl1 * $cl2 * $cdelta;

        $ad = atan2($y, $x);
        $dist = $ad * self::EARTH_RADIUS;
        return $dist;
    }

    /**
     * Validate the point
     *
     * @param array $points
     * @param array $newPoint
     *
     * @return bool
     */
    public function validatePoint(array $points, array $newPoint) : bool
    {
        $firstPoint = array_first($points);
        $firstNewLine = $this->prepareLineFormat($firstPoint, $newPoint);
        $fromStart = $this->getPotentialIntersectLines($points);
        foreach ($fromStart as $line) {
            if ($this->hasIntersection($firstNewLine, $line)) {
                return false;
            }
        }

        $lastPoint = array_last($points);
        $lastNewLine = $this->prepareLineFormat($lastPoint, $newPoint);


        $fromEnd = $this->getPotentialIntersectLines(array_reverse($points));
        foreach ($fromEnd as $line) {
            if ($this->hasIntersection($lastNewLine, $line)) {
                return false;
            }
        }

        return true;
    }

    protected function getPotentialIntersectLines(array $points) : array
    {
        $firstPoint = null;
        $linesToCheck = [];
        foreach ($points as $key => $secondPoint) {
            if (!$key) {
                continue;
            }
            if ($firstPoint) {
                $linesToCheck[] = $this->prepareLineFormat($firstPoint, $secondPoint);
            }
            $firstPoint = $secondPoint;
        }
        return $linesToCheck;
    }


    /**
     * Check are crossing two lines
     *
     * @param $newLine [
     *      'first' => [
     *          'x' => float,
     *          'y' => float
     *      ],
     *      'second' => [
     *          'x' => float,
     *          'y' => float
     *      ]
     *  ]
     * @param $line [
     *      'first' => [
     *          'x' => float,
     *          'y' => float
     *      ],
     *      'second' => [
     *          'x' => float,
     *          'y' => float
     *      ]
     *  ]
     *
     * @return bool
     */
    public function hasIntersection($newLine, $line) : bool
    {
        $x1 = $newLine['first']['x'];
        $x2 = $newLine['second']['x'];
        $x3 = $line['first']['x'];
        $x4 = $line['second']['x'];

        $y1 = $newLine['first']['y'];
        $y2 = $newLine['second']['y'];
        $y3 = $line['first']['y'];
        $y4 = $line['second']['y'];

        $v1 = $this->vectorMult($x4 - $x3, $y4 - $y3, $x1 - $x3, $y1 - $y3);
        $v2 = $this->vectorMult($x4 - $x3, $y4 - $y3, $x2 - $x3, $y2 - $y3);
        $v3 = $this->vectorMult($x2 - $x1, $y2 - $y1, $x3 - $x1, $y3 - $y1);
        $v4 = $this->vectorMult($x2 - $x1, $y2 - $y1, $x4 - $x1, $y4 - $y1);

        return (bool) (($v1*$v2) < 0 && ($v3 * $v4) < 0);
    }

    private function vectorMult($ax, $ay, $bx, $by)
    {
        return $ax * $by - $bx * $ay;
    }

    /**
     * Convert points to line format
     *
     *
     * @param $firstPoint [
     *       'latitude' => float value latitude,
     *       'longitude' => float value longitude,
     * ]
     * @param $secondPoint[
     *       'latitude' => float value latitude,
     *       'longitude' => float value longitude,
     * ]
     *
     * @return array [
     *      'first' => [
     *          'x' => float,
     *          'y' => float
     *      ],
     *      'second' => [
     *          'x' => float,
     *          'y' => float
     *      ]
     *  ]
     */
    private function prepareLineFormat(array $firstPoint, array $secondPoint) : array
    {
        [
            'first' => [
                'x' => $firstPoint['latitude'],
                'y' => $firstPoint['longitude']
            ],
            'second' => [
                'x' => $secondPoint['latitude'],
                'y' => $secondPoint['longitude']
            ]
        ];
    }
}