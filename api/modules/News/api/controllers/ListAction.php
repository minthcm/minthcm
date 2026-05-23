<?php

namespace MintHCM\Modules\News\api\controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class ListAction
{

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $response->getBody()->write(json_encode($this->getListData()));
        return $response;
    }

    public function getListData()
    {
        $response = array();

        chdir('../legacy/');
        $db = \DBManagerFactory::getInstance();
        $result = $db->query($this->getNewsQuery());
        while (($row = $db->fetchByAssoc($result, false)) != null) {
            $row['photo'] = (!empty($row['photo']) ? "legacy/index.php?entryPoint=download&type=News&id=" . $row['id'] . "_photo" : '');
            $row['author'] = [
                'id' => $row['author_id'],
                'name' => $row['author_name'],
                'photo' => (!empty($row['author_photo']) ? "legacy/index.php?entryPoint=download&type=Users&id=" . $row['author_id'] . "_photo" : ''),
            ];
            unset($row['author_id']);
            unset($row['author_name']);
            unset($row['author_photo']);
            $row['reactions'] = json_decode(html_entity_decode($row['reactions_json']), true);
            unset($row['reactions_json']);
            $response[] = $row;
        }
        chdir('../api/');

        return $response;
    }

    private function getNewsQuery()
    {
        global $current_user;
        return "SELECT
                n.id,
                n.name,
                n.content_of_announcement,
                n.publication_date,
                n.created_by,
                n.photo,
                u.id author_id,
                concat_ws(' ', u.first_name, u.last_name) author_name,
                u.photo author_photo,
                IF(r.id IS NULL, NULL, JSON_ARRAYAGG(JSON_OBJECT(
                    'type', r.reaction_type,
                    'user', JSON_OBJECT(
                        'id', r.assigned_user_id,
                        'name', CONCAT_WS(' ', ru.first_name, ru.last_name)
                    )
                ))) reactions_json
            FROM news n
            LEFT JOIN users u
				ON u.deleted = 0 AND u.id = n.created_by
                LEFT JOIN reactions r
                    ON r.parent_type = 'News'
                    AND r.parent_id = n.id
                    AND r.deleted = 0
                LEFT JOIN users ru
                    ON ru.id = r.assigned_user_id
                    AND ru.deleted = 0
            WHERE
                n.deleted = 0
                AND n.id IN (SELECT news_id FROM usersnews WHERE assigned_user_id = '{$current_user->id}' AND deleted = 0)
                AND n.news_type='announcement'
                AND n.news_status = 'published'
                AND n.publication_date <= CURDATE()
            GROUP BY n.id
            ORDER BY n.publication_date DESC";
    }

}
