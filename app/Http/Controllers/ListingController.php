<?php

namespace App\Http\Controllers;

use App\Models\listings;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// use Illuminate\Http\Client\Request;

class ListingController extends BaseController
{
    //Show all Listings
    public function getjobs()
    {
        $jobs = listings::orderBy('id', 'asc')->get();

        return response()->json([
            'message' => 'Listing returned successfully',
            'data' => $jobs,
        ], 200);
    }

    //Show a Listing
    public function getonejob($id)
    {
        return $single = listings::find($id);
    }

    //Create a Listing
    public function postJob(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'tags' => 'required|array',
            'company' => 'required|string',
            'location' => 'required|string',
            'email' => 'required|email',
            'website' => 'required|string',
            'description' => 'required|string',
            'skills' => 'required|array',
            'job_type' => 'required|string|in:remote,hybrid,onsite',
            'job_mode' => 'required|string|in:internship,contract,full-time'
        ]);
        if ($validator->fails()) return $this->sendResponse(implode(',', $validator->errors()->all()), null, 404);

        $user_id = auth()->id();
        try {
            $saveListing = listings::create([
                'title' => $request->title,
                'tags' => $request->tags,
                "company_name" => $request->company,
                'location' => $request->location,
                'email' => $request->email,
                'website' => $request->website,
                'description' => $request->description,
                'job_type' => $request->job_type,
                'job_mode' => $request->job_mode,
                'user_id' => $user_id,
                'skills' => $request->skills
            ]);
        } catch (Exception $e) {
            return $this->sendResponse($e->getMessage(), null, 400);
        }

        return $this->sendResponse('Job created successfully', null, 201);
    }

    public function Deletejob($id)
    {
        $job = listings::find($id);
        $job->delete();

        return response()->json([
            'message' => 'Job deleted successfully',
            'error' => false,
        ], 200);
    }

    public function Undodelete($id)
    {
        return $job = listings::onlyTrashed()->find($id);
    }
}
