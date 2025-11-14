<?php

namespace App\Http\Controllers\Lti;

use App\Http\Controllers\Controller;
use App\Services\Lti\Lti13Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LtiLoginController extends Controller
{
    public function login(Request $request, Lti13Service $ltiService): RedirectResponse
    {
        return $ltiService->login(
            $request->all(),
            route('lti.launch')
        );
    }
}
