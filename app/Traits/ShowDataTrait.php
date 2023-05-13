<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ShowDataTrait
{

    public function succes($mesage,$data)
    :JsonResponse
    {
        return response()->json([
            'statues' => true,
            'code' => 200,
            'message' => $mesage,
            'data' => $data
            ]);
    }

    public function errorValidation($mesage,$error)
    :JsonResponse
    {
        return response()->json([
            'statues' => false,
            'code' => 422,
            'message' => $mesage,
            'error' => $error
        ] , 422);
    }

    public function succesWithoutData($mesage)
    :JsonResponse
    {

        return response()->json([
            'statues' => true,
            'code' => 200,
            'message' => $mesage,
            ]);
    }

    public function errorFind($mesage)
    :JsonResponse
    {
        return response()->json([
            'statues' => false,
            'code' => 404,
            'message' => $mesage,
        ],404);
    }

}
