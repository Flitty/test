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
        $data = Cache::remember('global_data', 60, function() {
            return [
                'config' => [
                    'url'               => config('app.url'),
                    'env'               => config('app.env'),
                    'debug'             => config('app.debug'),
                    'api' => [
                        'pusher' => [
                            'key'       => env('PUSHER_APP_KEY', ''),
                            'cluster'   => env('PUSHER_CLUSTER', ''),
                            'auth_url'  => env('PUSHER_AUTH_URL', ''),
                        ],
                        'sentry' => [
                            'dns'       => env('SENTRY_FRONTEND_DSN', '')
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