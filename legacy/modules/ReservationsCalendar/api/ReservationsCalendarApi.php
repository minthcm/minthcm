<?php

class ReservationsCalendarApi
{
    public function getResourceReservations($args)
    {
        $resource_id = $args['resource_id'];
        $resource = BeanFactory::getBean('Resources', $resource_id);
        if (empty($resource->id)) {
            throw new Exception('Resource not found');
        }

        $resource = array(
            'id' => $resource->id,
            'name' => $resource->name,
        );

        $controller = ControllerFactory::getController('ReservationsCalendar');
        return $controller->getResourceReservations($resource, $args['calendar_date']);
    }
}

