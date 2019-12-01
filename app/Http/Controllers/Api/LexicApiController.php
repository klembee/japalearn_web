<?php
/**
 * Created by PhpStorm.
 * User: cleme
 * Date: 2019-11-30
 * Time: 4:09 PM
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Lexic;
use App\Models\LexicMeaning;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LexicApiController extends Controller
{
    public function sync(Request $request){
        $unsynced = json_decode($request->input('lexics'));
        $user = Auth::user();

        //Add all the unsynced data to the cloud database
        foreach($unsynced as $lexic){
            //If the lexics was marked as deleted on the mobile application
            if($lexic->deleted){
                $lexic = Lexic::query()->where('id', $lexic->id)->first();
                if($lexic){
                    $lexic->delete();
                }
            }else {
                $synced = $user->lexics()->where('word', $lexic->word)->first();

                if(!$synced){
                    $synced = new Lexic;
                    $synced->user_id = $user->id;
                }

                $synced->word = $lexic->word;
                $synced->level = $lexic->level;
                if(isset($lexic->last_time_studied)){
                    $synced->last_time_studied = Carbon::createFromTimestamp($lexic->last_time_studied / 1000);
                }else{
                    $synced->last_time_studied = null;
                }
                $synced->save();

                //Create the meanings
                $meanings = [];
                foreach ($lexic->meanings as $meaning) {
                    $meaningModel = new LexicMeaning;
                    $meaningModel->lexic_id = $synced->id;
                    $meaningModel->meaning = $meaning;
                    $meanings[] = $meaningModel;
                }

                //Assign the meanings to the lexic
                $synced->meanings()->delete();
                $synced->meanings()->saveMany($meanings);

                $synced->save();
            }
        }

        //Get all lexics that were modified after the lastRequest date
        $lastRequestDate = Carbon::createFromTimestamp($request->input('last_request_date'));
        $newLexics = Lexic::query()->where('updated_at', '>', $lastRequestDate)
            ->orWhereHas('meanings', function($query) use($lastRequestDate){
                return $query->where('updated_at', '>', $lastRequestDate);
            })->get();

        $newLexics = $newLexics->map(function ($lexic){
            $lexic['meanings'] = $lexic->meanings()->get()->pluck('meaning')->all();
            return $lexic;
        });

        return response()->json([
            'success' => true,
            'message' => "SUCCESS: " . $newLexics->count(),
            'models' => $newLexics
        ]);
    }
}