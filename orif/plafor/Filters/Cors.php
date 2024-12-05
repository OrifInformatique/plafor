<?php

namespace Plafor\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Cors implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        header("Access-Control-Allow-Origin: http://localhost:4000");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        if ($request->getMethod() === 'options')
            return service('response')->setStatusCode(200);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }
}
