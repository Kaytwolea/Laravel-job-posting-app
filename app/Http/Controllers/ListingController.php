<?php

namespace App\Http\Controllers;

use App\Models\listings;
use Exception;
// use Illuminate\Http\Client\Request;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    //Show all Listings
    public function getjobs()
    {
        $jobs = listings::orderBy('id', 'asc')->get();

        return response()->json([
            'data' => $jobs,
        ], 200);
    }

    //Show a Listing
    public function getonejob($id)
    {
        return $single = listings::find($id);
    }

    //Create a Listing
    public function postjob(Request $request)
    {
        try {
            $newpost = $request->validate([
                'title' => 'required',
                'tags' => 'required',
                'company' => 'required',
                'location' => 'required',
                'email' => 'required',
                'website' => 'required',
                'description' => 'required',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'data' => null,
                'error' => true,
            ], 400);
        }
        $newpost['user_id'] = auth()->id();
        $saveListing = listings::create($newpost);

        return response()->json([
            'messsage' => 'New Job have been posted successfully',
            'error' => false,
            'data' => $saveListing,
        ], 201);
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
