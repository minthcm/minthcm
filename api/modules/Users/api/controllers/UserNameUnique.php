<?php

namespace MintHCM\Modules\Users\api\controllers;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use MintHCM\Api\Entities\Users;
use Slim\Psr7\Response;
use Slim\Exception\HttpBadRequestException;

class UserNameUnique
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $username = $request->getAttribute('username');
        if(empty($username)) {
            throw new HttpBadRequestException($request, 'Parameter "username" is missing or empty.');
        }
        $user = $this->entityManager->getRepository(Users::class)->findOneBy(['user_name' => $username]);
        $is_unique = empty($user);
        $response->getBody()->write(json_encode($is_unique));
        return $response;
    }


}
