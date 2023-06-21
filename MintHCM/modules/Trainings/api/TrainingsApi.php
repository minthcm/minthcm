<?php

class TrainingsApi
{
    const MODULE_NAME = "Trainings";

    public function closeTraining($args)
    {
        try{
            $training = BeanFactory::getBean(static::MODULE_NAME,$args['id']);
            $training->status = 'held';
            $training->save(false);
            return true;
        }catch(Exception $e){
            $GLOBALS['log']->fatal($e);
            return false;
        }
    }
}
