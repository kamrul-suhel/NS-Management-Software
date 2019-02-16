<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $transformer)
    {
        $transformInput = array();

        foreach($request->request->all() as $input => $value){
            $transformInput[$transformer::originalAttribute($input)] = $value;
        }

        $request->replace($transformInput);
        $response = $next($request);

        if(isset($response->exception) && $response->exception instanceof ValidationException){
            $data = $response->getData();

            $transformError = [];

            foreach($data->error as $input => $value){
                $transformField = $transformer::transformedAttribute($input);

                $transformError[$transformField] = str_replace($input, $transformField, $value);
            }

            $data->error = $transformError;

            $response->setData($data);
        }
        return $response;
    }
}
