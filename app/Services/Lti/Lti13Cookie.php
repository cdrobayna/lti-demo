<?php

namespace App\Services\Lti;

use Illuminate\Support\Facades\Cookie;
use Packback\Lti1p3\Interfaces\ICookie;

class Lti13Cookie implements ICookie
{
    public function getCookie(string $name): ?string
    {
        return Cookie::get($name);
    }

    public function setCookie(string $name, string $value, int $exp = 3600, array $options = []): void
    {
        Cookie::queue($name, $value, $exp / 60); // Laravel uses minutes, not seconds
    }
}
