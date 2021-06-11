<?php

class ReactionsApi
{
    public function getReactions($args)
    {
        $reactions = [];
        if (!empty($args['module_name']) && !empty($args['record_id'])) {
            $reactions_contoller = ControllerFactory::getController('Reactions');
            $reactions = $reactions_contoller::getReactionsForRecord($args['module_name'], $args['record_id']);
        }
        return $reactions;
    }

    public function addReaction($args)
    {
        $result = false;
        if (!empty($args['reaction_type']) && !empty($args['module_name']) && !empty($args['record_id'])) {
            $reactions_contoller = ControllerFactory::getController('Reactions');
            $result = $reactions_contoller::addReaction($args['reaction_type'], $args['module_name'], $args['record_id']);
        }
        return $result;
    }

    public function removeReaction($args)
    {
        $result = false;
        if (!empty($args['reaction_type']) && !empty($args['module_name']) && !empty($args['record_id'])) {
            $reactions_contoller = ControllerFactory::getController('Reactions');
            $result = $reactions_contoller::removeReaction($args['reaction_type'], $args['module_name'], $args['record_id']);
        }
        return $result;
    }
}
