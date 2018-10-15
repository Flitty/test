<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFigureRequest;
use App\Http\Requests\FigureSortingRequest;
use App\Http\Requests\UpdateFigureRequest;
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
