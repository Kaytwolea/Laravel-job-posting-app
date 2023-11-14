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

    // Approve Job
    public function approveJob($id)
    {
        $currentJob = listings::find($id);
        if ($currentJob->isconfirmed) {
            return response()->json([
                'message' => 'Job has been approved already.',
            ]);
        }
        $currentJob->isconfirmed = 1;
        $currentJob->save();

        return response()->json([
            'message' => 'Job approved successfully',
            'data' => $currentJob,
        ], 200);
    }

    // Decline Job
    public function declineJob($id)
    {
        $currentjob = listings::find($id);
        $currentjob->delete();

        return response()->json([
            'message' => 'Job declined successfully',
            'data' => $currentjob,
        ], 200);
    }

    //Block User
    public function BlockUser($id)
    {
        $user = User::find($id);
        $user->delete();
    }
}