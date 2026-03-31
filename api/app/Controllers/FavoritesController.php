<?php
namespace MintHCM\Api\Controllers;

use BeanFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class FavoritesController
{
    public function add(Request $request, Response $response, array $args): Response
    {
        $module = $request->getAttribute('module');
        $id = $request->getAttribute('id');
        chdir('../legacy/');
        /** @var Favorites $favorites */
        $favorites = BeanFactory::newBean('Favorites');
        $favorites->addFavorite($module, $id);
        chdir('../api/');
        $response = $response->withStatus(200);
        return $response;
    }

    public function remove(Request $request, Response $response, array $args): Response
    {
        $module = $request->getAttribute('module');
        $id = $request->getAttribute('id');
        chdir('../legacy/');
        /** @var Favorites $favorites */
        $favorites = BeanFactory::newBean('Favorites');
        $favorite_id = $favorites->getFavoriteID($module, $id);
        if (empty($favorite_id)) {
            chdir('../api/');
            return $response->withStatus(404);
        }

        $favorites->deleteFavorite($favorite_id);
        chdir('../api/');
        $response = $response->withStatus(200);
        return $response;
    }
}
