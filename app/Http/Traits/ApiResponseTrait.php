<?php

namespace App\Http\Traits;

trait ApiResponseTrait
{
     function sendResponse($data , $status = 'success' , $code = 200){
        return response()->json([
            'data' => $data,
            'status' => $status,
            'code' => $code
        ],$code);
    }
}
