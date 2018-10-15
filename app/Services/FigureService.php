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


}