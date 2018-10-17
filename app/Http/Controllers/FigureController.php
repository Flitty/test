<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFigureRequest;
use App\Http\Requests\FigureSortingRequest;
use App\Http\Requests\UpdateFigureRequest;
use App\Http\Requests\ValidatePointRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class FigureController
 * @package App\Http\Controllers
 */
class FigureController extends Controller
{

    /**
     * Get paginated user figures.
     *
     * @param FigureSortingRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(FigureSortingRequest $request)
    {
        $figures = app('figure-service')->getFiguresWithSorting(
            Auth::id(),
            $request->get('sortType'),
            $request->get('orderBy'),
            $request->get('perPage')
        );
        return response()->json(['message' => 'Sorted figures', 'figures' => $figures]);
    }

    /**
     * Save new figure
     *
     * @param CreateFigureRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateFigureRequest $request)
    {
        $attributes = $request->only(['name', 'perimeter']);
        $attributes['user_id'] = \Auth::id();
        $createdFigure = app('figure-service')->create($attributes, $request->get('points'));
        return response()->json(['message' => 'Figure has been created successfully', 'figure' => $createdFigure]);
    }

    /**
     * Validate the point user has been set
     *
     * @param ValidatePointRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function validatePoint(ValidatePointRequest $request)
    {
        $points = $request->get('points');
        $newPoint = array_pop($points);
        $validPoints = $points;

        $alreadyExist = array_filter(
            $validPoints,
            function($item) use ($newPoint) {
                return $newPoint['latitude'] == $item['latitude'] && $newPoint['longitude'] == $item['longitude'];
            }
        );
        if (!count($alreadyExist)) {
            $canAddThePoint = count($points) > 3 ? app('figure-service')->validatePoint($validPoints, $newPoint) : true;
            $not = !$canAddThePoint ? 'not ' : '';
            $message = 'The point has ' . $not . 'been added successfully';
        } else {
            $canAddThePoint = false;
            $message = 'The point already exists';
        }
        if ($canAddThePoint) {
            $points = array_map(function ($item) {
                $item['validated'] = true;
                return $item;
            }, $request->get('points'));
        }
        return response()->json(
            [
                'message' => $message,
                'isValid' => $canAddThePoint,
                'points' => $points,
                'perimeter' => app('figure-service')->measureFigureDistance($validPoints)
            ]
        );

    }

    /**
     * Delete user figure
     *
     * @param $figureId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($figureId)
    {
        //FIXME: need to add policy

        app('figure-service')->delete($figureId);
        return response()->json(['message' => 'Figure has been deleted successfully']);
    }

    /**
     * Update existing user figure
     *
     * @param UpdateFigureRequest $request
     * @param                     $figureId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateFigureRequest $request, $figureId)
    {
        //FIXME: need to add policy

        $attributes = $request->only(['name', 'perimeter']);
        $createdFigure = app('figure-service')->update($attributes, $request->get('points'), $figureId);
        return response()->json(['message' => 'Figure has been updated successfully', 'figure' => $createdFigure]);
    }
}
