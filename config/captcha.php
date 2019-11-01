<?php

return [
    'key_max_times' => env('CAPTCHA_KEY_MAX_TIMES',3),
    'cache_ttl'     => env('CAPTCHA_CACHE_TTL',60),
];
