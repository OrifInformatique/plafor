<?php

namespace Plafor\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Cors implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Gérer les en-têtes CORS
        header("Access-Control-Allow-Origin: http://localhost:3000");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        // Si la méthode est OPTIONS, répondre directement avec un statut 200
        if ($request->getMethod() === 'options')
            return service('response')->setStatusCode(200);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Pas besoin de gérer quoi que ce soit ici pour CORS
    }
}
