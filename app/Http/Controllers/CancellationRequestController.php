<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use App\Models\CancellationRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CancellationRequestController extends Controller
{
    //
    public function store(Request $request) {

        $request->validate(['holidayId' => 'integer|exists:holidays,id']);

            $user = Auth::user();

            Log::debug($user);

            if(CancellationRequest::where('holiday_id', $request->holidayId)->get()){
                return response()->json('You have already sent a request to cancel this holiday.', 400);
            }
            else {
                $request = CancellationRequest::create(['user_id' => $user->id, 'holiday_id' => $request->holidayId])->save();
            }



    }
}
