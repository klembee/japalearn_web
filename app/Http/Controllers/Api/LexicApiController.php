<?php
/**
 * Created by PhpStorm.
 * User: cleme
 * Date: 2019-11-30
 * Time: 4:09 PM
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LexicApiController extends Controller
{
    public function sync(Request $request){
        return response()->json([
           'success' => true,
           'message' => "hello"
        ]);
    }
}