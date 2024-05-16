<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Input\Input;

class MethodsController extends Controller
{
    public static function uploadImageOtherServer($file)
    {
        $response = Http::withHeaders([
            'image-uploader-api-key' => 'zkBsVjwJs3iR4iZPW4HYrdCkb06fMRtIoJvvAuBjxHkPFeoerJqOZaPmnu6s5OCS'
        ])
            ->send('POST', 'https://image-upload.wahdi.org/api/v1/uploads/send-image', [
                'form_params' => [
                    'file' => $file,
                ]
            ]);

        return $response;

    }

}


