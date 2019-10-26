<?php
/**
 * Created by PhpStorm.
 * User: cleme
 * Date: 2019-10-25
 * Time: 8:03 PM
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FeedbackController extends Controller
{
    /**
     * Create a new feedback entry
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request){
        try {
            $this->validate($request, [
                'is_bug' => 'required|boolean',
                'section' => 'required',
                'description' => 'required'
            ]);
        }catch(ValidationException $e){
            return response()->json([
                'success' => false,
                'message' => 'Validation of query failed.'
            ]);
        }

        $feedback = new Feedback([
            'user_id' => null,
            'is_bug' => $request->is_bug == 'true' ? 1 : 0,
            'section' => $request->section,
            'description' => $request->description
        ]);
        $feedback->save();

        return response()->json([
            'success' => true,
            'message' => 'Added feedback successfully',
        ]);
    }
}