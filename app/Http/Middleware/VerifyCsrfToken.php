<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Closure;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];
    private $openRoutes = [
        'admin/modellistdata',
        'admin/isospeceficationlistdata',
        'admin/customerlistdata',
        'admin/customertypelistdata',
        'admin/frequencylistdata',
        'admin/devicemodellistdata',
        'admin/productlistdata',
        'admin/manufacturerlistdata',
        'admin/brandlistdata',
        'admin/channelnumberslistdata',
        'admin/channelpointslistdata',
        'admin/workorderlistdata',
        'admin/servicerequestlistdata',
        'admin/samplelistdata',
        'admin/devicelistdata',
        'admin/servicelistdata',
        'admin/listData',
        'admin/viewlistdata',
        'admin/viewlistdata/*',
        'admin/paymentlist',
        'admin/buyservicelistdata',
        'admin/orderrequestslistdata',
        'admin/customerorderslistdata',
        'admin/userlistdata',
        'admin/qualitychecklistdata',
        'admin/technicianlistdata',
        'admin/duelistdata',
        'admin/permissionlistdata',
        'admin/menulistdata',
        'admin/permissionsettings',
        'admin/serviceplantypelistdata',
        'admin/statelistdata',
        'admin/citylistdata',
        'admin/testpointlistdata',
        'admin/contactuslist',
        'admin/operationlistdata',
        'admin/pipettetypelistdata',
        'admin/customerspecifictionlistdata',
        'admin/calspeceficationlistdata',
        'admin/shippinglistdata',

    ];

    public function handle($request, Closure $next)
    {
        //add this condition
        foreach($this->openRoutes as $route) {

            if ($request->is($route)) {
                return $next($request);
            }
        }

        return parent::handle($request, $next);
    }
}
