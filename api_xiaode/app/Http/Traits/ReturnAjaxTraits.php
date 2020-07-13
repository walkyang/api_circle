<?php
namespace App\Http\Traits;

trait ReturnAjaxTraits {
    
    //$code = 1000 成功
    
    public static function returnJson(string $code, string $message, $data): Object {
        return response()->json([
            'code' => $code,
            'msg'  => $message,
            'data' => $data
        ]);
    }
}
