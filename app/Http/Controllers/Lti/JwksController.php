<?php

namespace App\Http\Controllers\Lti;

use App\Http\Controllers\Controller;
use App\Services\Lti\Lti13Service;
use Illuminate\Http\JsonResponse;

class JwksController extends Controller
{
    public function keys(Lti13Service $ltiService): JsonResponse
    {
        return response()->json($ltiService->getPublicJwks());
    }
}
