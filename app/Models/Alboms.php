<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Alboms extends Model
{
    protected $fillable = [
        'user_id',
        'images',
    ];

    public function insert($user_id, $link) {
        try {
            $album = Alboms::where('user_id', $user_id)->first();

            if ($album) {
                $images = json_decode($album->images, true);
                $images['links'][] = $link;
            } else {
                $images = [
                    'links' => [$link]
                ];
            }

            Alboms::updateOrCreate(['user_id' => $user_id], [
                'images' => json_encode($images)
            ]);

            return ['status' => 200, 'message' => 'Link saved'];
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function select($user_id) {
        try {
            $album = Alboms::where('user_id', $user_id)->first();
            if($album) {
                $images = json_decode($album->images, true);
                return $images['links'];
            }

            return [];
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return [];
        }
    }

    public function delete_image($user_id, $index) {
        try {
            $album = Alboms::where('user_id', $user_id)->first();

            if ($album) {
                $images = json_decode($album->images, true);
                unset($images['links'][$index]);

                $new_list = [];
                foreach ($images['links'] as $image) {
                    $new_list[] = $image;
                }

                Alboms::updateOrCreate(['user_id' => $user_id], [
                    'images' => json_encode([
                        'links' => $new_list
                    ])
                ]);

                return ['status' => 200, 'message' => 'Image deleted!'];
            }

            return ['status' => 500, 'message' => 'Album not found!'];
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }
}
