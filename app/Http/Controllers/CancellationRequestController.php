<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\User;

use Illuminate\Http\Request;
use App\Models\CancellationRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CancellationRequestStatusNotification;

class CancellationRequestController extends Controller
{
    //
    public function store(Request $request) {

        $request->validate(['holidayId' => 'integer|exists:holidays,id']);

            $user = Auth::user();


            if(CancellationRequest::where('holiday_id', $request->holidayId)->exists()){
                return response()->json('You have already sent a request to cancel this holiday.', 400);
            }
            else {
                $request = CancellationRequest::create(['user_id' => $user->id, 'holiday_id' => $request->holidayId])->save();
            }
    }

    public function update(Request $request) {
        $request->validate(['id' => 'integer|exists:cancellation_requests,id', 'status' => 'string'
    ]);

        $cancellationRequest = CancellationRequest::find($request->id);
        $cancellationRequest->status = $request->status;
        $cancellationRequest->save();

        $notificationData = ['adminUser' => auth()->user()->name, 'status' => $request->status, 'startDate' => $cancellationRequest->holiday->start_date, 'endDate' => $cancellationRequest->holiday->end_date];

        User::find($cancellationRequest->user_id)->notify(new CancellationRequestStatusNotification($notificationData));

        if($request->status == 'approved'){
            $cancellationRequest->holiday()->delete();
        }

    }


}
