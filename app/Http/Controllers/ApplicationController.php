<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\listings;
use Exception;
use Illuminate\Http\Request;

class ApplicationController extends BaseController
{
    //
    public function createApplication($jobId, Request $request)
    {
        $user = auth()->user();
        $job = listings::where('id', $jobId)->first();
        $userJob = $job->user;


        if ($jobId === $userJob->id) return $this->sendResponse('You cannot apply for a job you posted', null, 400);
        $existingApplication = Application::where('user_id', $user->id)
            ->where('listings_id', $jobId)
            ->first();

        if ($existingApplication) {
            return $this->sendResponse('You already have an application for this job', null, 400);
        } else
            try {
                Application::create([
                    'user_id' => $user->id,
                    'listings_id' => $jobId,
                    'cover_letter' => 'i promise to be good'
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'message' => $e->getMessage()
                ], 200);
            }
        return $this->sendResponse('Application Successful', null, 201);
    }

    public function fetchJobApplications($jobId)
    {
        $job = listings::find($jobId)->get();
        $applications = $job->applicants;
        return $this->sendResponse('returned', $applications, 200);
    }
}
