<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //

    public function show() {
        $userHoliday = Holiday::where("user_id", Auth::user()->id)->get();

        return Inertia::render("Dashboard", ["userHoliday" => $userHoliday, "totalDaysTaken" => $userHoliday->where('status', 'approved')->sum('total_days')]);
    }

    public function edit()
    {
        //
        return Inertia::render("MyAccount", ["user" => User::find(Auth::user()->id)]);

    }
}
