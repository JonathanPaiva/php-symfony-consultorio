<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use symfony\Component\Routing\Annotation\Route;

class OlaMundoController 
{

    /**
     * @Route("/ola")
     */

    public function OlaMundoAction(Request $request): Response
    {
        /*
        echo "Olá Mundo! - Teste de Rota";
        exit();
        */
        $pathInfo = $request->getPathInfo();
        $query = $request->query->all();

        return new JsonResponse([
            'mensagem'=> 'Olá Mundo!',
            'pathInfo'=> $pathInfo,
            'query'=> $query            
        ]);

    }

}


