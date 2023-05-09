<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class NotificationController extends Controller
{
    //
    public function index() {
        return Inertia::render('Notifications');
    }

    public function markAsRead($id){
        try{
            auth()->user()->notifications()->where('id', $id)->first()->delete();
        }
        catch(Throwable $e){
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    public function markAllAsRead() {
        try {
            auth()->user()->notifications()->delete();
        }
        catch (Throwable $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

    }
}
