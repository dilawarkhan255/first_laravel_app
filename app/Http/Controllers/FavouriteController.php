<?php

namespace App\Http\Controllers;

use App\Models\Favourite;
use Illuminate\Http\Request;
use App\Models\JobListing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FavouriteController extends Controller
{
    public function toggleFavourite(Request $request)
{
    $user = Auth::user();
    $jobId = $request->input('job_id');

    $favourite = DB::table('favourite_joblisting')
        ->where('user_id', $user->id)
        ->where('job_listing_id', $jobId)
        ->first();

    if ($favourite) {
        $isFavourited = $favourite->favourite;
        DB::table('favourite_joblisting')
            ->where('user_id', $user->id)
            ->where('job_listing_id', $jobId)
            ->update(['favourite' => !$isFavourited]);

        $message = !$isFavourited ? 'added to' : 'removed from';
        return response()->json(['status' => !$isFavourited ? 'added' : 'removed', 'message' => "Job $message favourites"]);
    } else {
        DB::table('favourite_joblisting')->insert([
            'user_id' => $user->id,
            'job_listing_id' => $jobId,
            'favourite' => true
        ]);
        return response()->json(['status' => 'added', 'message' => 'Job added to favourites']);
    }
}

}
