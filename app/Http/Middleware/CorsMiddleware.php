<?php
namespace App\Http\Middleware;

use Log;
use Closure;

class CorsMiddleware
{
    private $allow_origins;
    private $headers;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $origin              = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
        $this->allow_origins = config('allow_origins');
        if ( ! in_array($origin, $this->allow_origins)) {
            Log::debug('Not Allowed Origin: [' . $origin . ']');

            return response('Forbidden!', 403);
        }

        $this->headers = [
            'Cache-Control'                    => 'no-cache',
            'Content-type'                     => 'application/json;charset=utf-8',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Methods'     => 'HEAD, GET, POST, PUT, PATCH, DELETE',
            'Access-Control-Allow-Headers'     => 'Content-Type, X-Requested-With, Origin, Accept, Authorization',
        ];

        if ($request->isMethod('options')) {
            return response('', 200, array_merge($this->headers, ['Access-Control-Allow-Origin' => $origin]));
        }

        $response = $next($request);

        /**
         * 这个判断是因为在开启session全局中间件之后，频繁的报header方法不存在
         * 所以加上这个判断，存在header方法时才进行header的设置
         */
        $methodAvailable = [$response, 'header'];
        if (is_callable($methodAvailable, false, $callable_name)) {
            return $this->setCorsHeaders($response, $origin);
        }

        return $response;
    }

    /**
     * @param object $response
     * @param string $origin
     *
     * @return mixed
     */
    public function setCorsHeaders(object $response, string $origin)
    {
        foreach ($this->headers as $key => $value) {
            $response->header($key, $value);
        }

        if (in_array($origin, $this->allow_origins)) {
            $response->header('Access-Control-Allow-Origin', $origin);
        } else {
            $response->header('Access-Control-Allow-Origin', '*');
        }

        return $response;
    }
}
