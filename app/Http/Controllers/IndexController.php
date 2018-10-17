<?php
/**
 * Created by PhpStorm.
 * User: nikolaygolub
 * Date: 15.10.2018
 * Time: 17:53
 */

namespace App\Http\Controllers;

use App\Http\Responses\DatasetResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;


/**
 * Class IndexController
 * @package App\Http\Controllers
 */
class IndexController extends Controller
{
    /**
     * @return Response
     */
    public function index() : Response
    {
        return response()->view('welcome');
    }

    /**
     * @return Response
     */
    public function dataset() : Response
    {
        Cache::forget('global_data');
        $data = Cache::remember('global_data', 60, function() {
            return [
                'config' => [
                    'url'               => config('app.url'),
                    'env'               => config('app.env'),
                    'debug'             => config('app.debug'),
                    'api' => [
                        'googleMaps' => [
                            'apiKey' => env('GOOGLE_MAPS_API_KEY')
                        ]
                    ]
                ],
            ];
        });
        return response()
            ->view('dataset', ['data' => $data])
            ->header('Content-Type', 'application/javascript');
    }
}