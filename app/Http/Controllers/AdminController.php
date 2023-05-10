<?php

namespace App\Http\Controllers;

use App\Models\CancellationRequest;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;


class AdminController extends Controller
{
    //
    public function index()
    {
        if (Gate::allows("is-admin")) {
            $teamUsers = User::where('team_id', auth()->user()->team_id)->get()->pluck('id');
            $cancellationRequests = CancellationRequest::whereIn('user_id', $teamUsers)->where('status', null)->with('holiday')->get();
            return Inertia::render("AdminPage", ['requests' => Holiday::whereIn('user_id', $teamUsers)->where('status', 'pending')->get(), 'cancellationRequests' => $cancellationRequests]);
        } else {
            return redirect("/");
        }
    }
}
