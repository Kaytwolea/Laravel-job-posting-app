<?php

namespace App\Http\Controllers;

use App\Models\listings;
use App\Models\User;

class AdminController extends Controller
{
    //
    public function getJobs()
    {
        $jobs = listings::all();
        $users = User::all();

        return view('pages.admin', compact('jobs', 'users'));
    }
}
