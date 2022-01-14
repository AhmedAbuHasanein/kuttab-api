<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyRecord;
use Illuminate\Http\Request;
use Validator;

class StudentController extends Controller
{
    public function getLastRecordsByLimit($id,Request $request){
        $lastRecordsByLimit = DailyRecord::where('student_id',$id)
                                        ->orderBy('id','DESC')
                                        ->take($request->limit)
                                        ->with(['quraan'])
                                        ->get();
        return $lastRecordsByLimit;
    }

    public function getLastRecordsByDate($id,Request $request){

        $validator = Validator::make($request->all(),[
            'from' => 'required|date',
            'to' => 'required|date',
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ],422);
        }
        $from = $request->from;
        $to = $request->to;

        $lastRecordsByDate = DailyRecord::whereBetween('created_at',[$from,$to])
                                        ->with(['quraan'])
                                        ->get();
        return $lastRecordsByDate;
    }

}
