<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface{

    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        // TODO: Implement handle() method.
        return new JsonResponse(array("message"=>"Vous n'avez pas acces a cette ressource",
                                      "code"=>403), 403);

    }
}
