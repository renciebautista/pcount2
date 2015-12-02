<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'api/uploadpcount',
        'api/uploadimage',
        'api/clientlist',
        'api/channellist',
        'api/distributorlist',
        'api/enrollmentlist',
        'api/regionlist',
        'api/storelist',
        'api/categorylist',
        'api/subcategorylist',
        'api/brandlist'
    ];
}
