<?php

namespace App\Http\Controllers\Lti;

use App\Http\Controllers\Controller;
use App\Services\Lti\Lti13Service;
use Illuminate\Http\JsonResponse;

class LtiErrorController extends Controller
{
    public function index(Lti13Service $ltiService): string
    {
        return "Error";
    }
}
