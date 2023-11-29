<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\AI;
use App\Models\Alboms;
use Inertia\Inertia;
use Inertia\Response;
use App\Jobs\SavingImage;

class MainController extends Controller
{
    public function users() {
        try {
            if(Cache::has('users')) {
                $users = Cache::get('users');
            } else {
                $users = User::all();
                Cache::put('users', $users);
            }

            dd($users);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function dashboard() {
        return Inertia::render('Dashboard', [
           'generate_image_url' => env('APP_URL') . 'ai/generate_image'
        ]);
    }

    public function album(Alboms $album) {
        $user_id = \Auth::user()->id;
        $images = $album->select($user_id);

        return Inertia::render('Album', [
            'delete_image_url' => env('APP_URL') . 'album',
            'images' => $images
        ]);
    }

    public function delete_image(Alboms $album, $index) {
        try {
            $user_id = \Auth::user()->id;
            return $album->delete_image($user_id, $index);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function generate_image(AI $ai, Alboms $alboms, $prompt) {
        try {
            $user_id = \Auth::user()->id;
            $without_spaces = str_replace(' ', '', $prompt);
            if(strlen($without_spaces) !== 0) {
                $generate_image = $ai->generate_image($prompt);
                $response = $generate_image['message'];
                $status = $generate_image['status'];

                if($status === 200) {
                    $image = $response['data'][0]['url'];
                    SavingImage::dispatch($user_id, $image);
                    return response()->json(['status' => $status, 'message' => $image]);
                }

                return response()->json(['status' => $status, 'message' => $response]);
            }

            return response()->json(['status' => 400, 'message' => 'You have to write prompt before generate image!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }
}
