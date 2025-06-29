<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;


abstract class Controller
{
    public static function success($data = null, $status = 200)
    {
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], $status);
    }

    public static function error($data = null, $message = '', $status = 400)
    {
        return response()->json([
            'status' => 'error',
            'message' => "$message failed!",
            'data' => $data,
        ], $status);
    }

  public static function paginated(LengthAwarePaginator $paginator, $resourceClass = null, $message = '', $status = 200)
{
    $items = $resourceClass
        ? $resourceClass::collection($paginator->items())
        : $paginator->items(); 

    return response()->json([
        'status' => 'success',
        'message' => $message,
        'data' => $items,
        'pagination' => [
            'total' => $paginator->total(),
            'count' => $paginator->count(),
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'total_pages' => $paginator->lastPage(),
        ],
    ], $status);
}

}
