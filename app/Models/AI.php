<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Http;

class AI extends Model
{
    public $gpt_api_key;

    public function __construct(){
        $this->gpt_api_key = env('GPT_API_KEY');
    }

    public function generate_image($prompt){
        try {
            $request = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->gpt_api_key
            ])->post('https://api.openai.com/v1/images/generations', [
                'model' => 'dall-e-3',
                'prompt' => $prompt,
                'n' => 1,
                'size' => '1024x1024'
            ]);

            $status = $request->status();
            $message = $request->successful() ? $request->json() : 'Something went wrong!';

            return ['status' => $status, 'message' => $message];
        } catch(\Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }
}
