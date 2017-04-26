<?php
namespace App\Http\Controllers;

use App\Flickr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;


Class WelcomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */

    public function ajaxLoadContent(Request $request)
    {
        $ids = $request->input('user_id');
        $tags = $request->input('tags');
        $tagmode = $request->input('tagmode');
        $language = $request->input('language');
        Cookie::forever('language', $language);

        $jsonRequest = Flickr::getResponse($ids, $tags, $tagmode, $language);

        return response()->json($jsonRequest);
    }

    public function index(Request $request)
    {
        return view('pages.flickertask');
    }


}