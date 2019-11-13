<?php
namespace App\Http\Controllers\Api\Auth;

use App\Services\Captcha;
use Illuminate\Http\Request;
use App\Http\Utilities\Constant;
use App\Http\Controllers\Controller;

class CaptchaController extends Controller
{
    public function getCode(Request $request, Captcha $captcha)
    {
        $key                 = $request->input('key', '');
        list($key, $content) = $captcha->get($key);

        return $this->suc(['captcha' => Constant::BASE64_PREFIX_PNG . base64_encode($content), 'key' => $key], 201);
    }
}
