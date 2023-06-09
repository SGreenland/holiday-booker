<?php

namespace App\Http\Controllers;

use Error;
use Exception;
use Throwable;
use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Holiday;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreHolidayRequest;
use App\Http\Requests\UpdateHolidayRequest;
use App\Notifications\HolidayStatusNotification;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $allHoliday = Holiday::get();

        foreach($allHoliday as $holiday) {
            $holiday['team'] = User::find($holiday->user_id)->team->name;
        }
        return Inertia::render("CalendarPage", ['allHoliday' => $allHoliday]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return Inertia::render("RequestForm");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreHolidayRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate request
        $request->validate([
            'start_date' => 'date|required',
            'end_date' => 'date|required',
            'total_days' => 'numeric|required'
        ]);

        //check if user has exceeded 30 days
        if(auth()->user()->holidays()->where('status', 'approved')->sum('total_days') + $request->total_days > 30 || $request->total_days > 30){
            return response()->json(['message' => 'You have exceeded your allowance!'], 400);
        }

        //check if holiday clashes with existing holiday or already booked
        $dates = CarbonPeriod::create($request->start_date, $request->end_date);
        $existingHoliday = Holiday::where('user_id', auth()->user()->id)->get(['start_date', 'end_date']);
        $already_booked = [];


        foreach($existingHoliday as $holiday){
            $date_range = CarbonPeriod::create($holiday->start_date, $holiday->end_date);
            foreach($date_range as $date){
                if($dates->contains($date)){
                    array_push($already_booked, $date);
                }
            }
        }
        if(count($already_booked)){
            return response()->json(['message' => 'Holiday clashes with current holiday!'], 400);
        }



        else {
            Holiday::create([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'total_days' => $request->total_days,
                'user_id' => Auth::user()->id,
                'name' => Auth::user()->name,
                'status' => 'pending'
            ]);
        }
        return response('Request Sent', 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateHolidayRequest  $request
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = auth()->user();
        $holiday =  Holiday::find($id);
        if($user->can('update', $holiday)){
            $holiday->status = $request->status;
            $holiday->rejection_reason = $request->rejection_reason;
            $holiday->save();

            $data = ['adminUser' => $user->name, 'status' => $request->status, 'rejectionReason' => $request->rejection_reason, 'startDate' => $holiday->start_date, 'endDate' => $holiday->end_date];

            User::find($holiday->user_id)->notify(New HolidayStatusNotification($data));
        }
        else abort(403);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $holiday = Holiday::findOrFail($id);
        if(auth()->user()->can('forceDelete', $holiday)){
            $holiday->destroy($id);
        }
        else abort(403, 'Permission denied');


    }
}
