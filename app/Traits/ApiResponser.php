<?php

namespace App\Traits;

use App\Transaction;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

trait ApiResponser
{

    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function showAll(Collection $collection, $code = 200)
    {
        if ($collection->isEmpty()
		) {
            return $this->successResponse(['data' => $collection], $code);
        }

        // $transformer = $collection->first()->transformer;
        // FIltering data
        // $collection = $this->filterData($collection, $transformer);

        //sorting data
        // $collection = $this->sortData($collection, $transformer);

        //Paginate data
        // $collection = $this->paginate($collection);

        //Transform data
//	    $collection = $this->transformData($collection, $transformer);

        //Cacheing data from laravel cache
//	    $collection = $this->cacheData($collection);

        return $this->successResponse($collection, $code);
    }

    protected function showOne(Model $instance, $code = 200)
    {
        $transformer = $instance->transformer;
        // $instance = $this->transformData($instance, $transformer);
        return $this->successResponse($instance, $code);
    }

    protected function showMessage($message, $code = 200)
    {
        return $this->successResponse(['data' => $message], $code);
    }

    /*
     * Sort collection data by passing parameeter in url sort_by
     */
    protected function sortData(Collection $collection, $transformer)
    {
        if (request()->has('sort_by')) {
            $attribute = $transformer::originalAttribute(request()->sort_by);
            $collection = $collection->sortBy->{$attribute};
        }
        return $collection;
    }

    /*
     * Filtering collection data
     */

    protected function filterData(Collection $collection, $transformer)
    {
        foreach (request()->query() as $query => $value) {
            $attribute = $transformer::originalAttribute($query);

            if (isset($attribute, $value)) {
                $collection = $collection->where($attribute, $value);
            }
        }
        return $collection;
    }

    /*
     * Paginage method
     */
    protected function paginate(Collection $collection)
    {
        $rules = [
            'per_page' => 'integer|min:5|max:50'
        ];

        Validator::validate(request()->all(), $rules);

        $page = LengthAwarePaginator::resolveCurrentPage();

        $perpage = 15;
        if (request()->has('per_page')) {
            $perpage = (int)request()->per_page;
        }

        $result = $collection->slice(($page - 1) * $perpage, $perpage)->values();

        $paginate = new LengthAwarePaginator($result, $collection->count(), $perpage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        $paginate->appends(request()->all());

        return $paginate;
    }

    /*
     * Transform data to use fractal plugin
     * It will change the database filed name to another attribute
     */
    protected function transformData($data, $transformer)
    {
        $transformation = fractal($data, new $transformer);
        return $transformation->toArray();
    }


    /*
     *
     * Cacheing data from server if it is exists
     *
     */

    protected function cacheData($data)
    {
        $url = request()->url();

        $queryparms = request()->query();
        ksort($queryparms);

        $queryString = http_build_query($queryparms);
        $fullUrl = "{$url}?{$queryString}";

        return Cache::remember($fullUrl, 2, function () use ($data) {
            return $data;
        });
    }


    protected function getUniqueId()
    {
        $unique_id = '';
        while (true) {
            $unique_id = self::generateRandomString(11);
            $unique_id_exists = Transaction::where('invoice_number', '=', $unique_id)->first();
            if ($unique_id_exists) {
                continue;
            } else {
                break;
            }
        }
        return $unique_id;
    }

    protected function generateRandomString($length = 11) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;

    }


    protected function rand_color() {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }

}
