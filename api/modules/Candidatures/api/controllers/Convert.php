<?php

namespace MintHCM\Modules\Candidatures\api\controllers;

use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Utils\LegacyConnector;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Psr7\Response;

class Convert
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
        $usertype = $request->getAttribute('usertype');
        $candidature_id = $request->getAttribute('candidature_id');

        if (empty($usertype)) {
            throw new HttpBadRequestException($request, 'Parameter "usertype" is missing or empty.');
        }
        if (empty($candidature_id)) {
            throw new HttpBadRequestException($request, 'Parameter "candidature_id" is missing or empty.');
        }
        if ('user' === $usertype && empty($username)) {
            throw new HttpBadRequestException($request, 'Parameter "username" is missing or empty.');
        }
        try {
            $converter_candidature = new LegacyConnector('CandidatureConverter', 'modules/Candidatures/CandidatureConverter.php', [$candidature_id, $username]);
            $created_record_id = $converter_candidature->convert();
        } catch (\Exception $e) {
            throw new HttpBadRequestException($request, 'Error converting candidature: ' . $e->getMessage());
        }

        $response->getBody()->write(json_encode(['created_record_id' => $created_record_id, 'module_name' => $username ? 'Users' : 'Employees']));
        return $response;
    }

}
