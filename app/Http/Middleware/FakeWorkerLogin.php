<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;

class FakeWorkerLogin
{
    /**
     * Fakes the login of a worker
     *
     * @param [type] $request
     * @param Closure $next
     * @param [type] $guard
     * @return void
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!($workerId = session()->get('worker'))) {
            session()->put('worker', ($workerId = optional(\App\Models\Worker::first())->id));
        }

        View::composer('html', function($view) use ($workerId) {
            $view->with('worker', \App\Models\Worker::find($workerId));
        });

        return $next($request);
    }
}
