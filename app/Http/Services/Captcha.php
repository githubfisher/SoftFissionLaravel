<?php
namespace App\Http\Services;

use Gregwar\Captcha\CaptchaBuilder;

class Captcha
{
    protected $captcha = null;

    public function __construct()
    {
        $this->captcha = (new CaptchaBuilder())->build();
    }

    public function getPhrase()
    {
        return $this->captcha->getPhrase();
    }

    public function get()
    {
        return $this->captcha->get();
    }
}
