<?php

class AreOnboardingOffboardingActivitiesHeld
{
    public function areTrainingsHeld($offboarding)
    {
        $offboarding->load_relationship('trainings');
        $training_ids = $offboarding->trainings->get();
        foreach ($training_ids as $training_id) {
            $training = BeanFactory::getBean('Trainings', $training_id);
            if ($training->status != 'Held') {
                return false;
            }
        }
        return true;
    }

    public function areExitInterviewsHeld($offboarding)
    {
        $offboarding->load_relationship('exitinterviews');
        $exitinterview_ids = $offboarding->exitinterviews->get();
        foreach ($exitinterview_ids as $exitinterview_id) {
            $exitinterview = BeanFactory::getBean('ExitInterviews', $exitinterview_id);
            if ($exitinterview->status != 'Held') {
                return false;
            }
        }
        return true;
    }

    public function areTasksHeld($offboarding)
    {
        $offboarding->load_relationship('tasks');
        $task_ids = $offboarding->tasks->get();
        foreach ($task_ids as $task_id) {
            $task = BeanFactory::getBean('Tasks', $task_id);
            if ($task->status != 'Completed') {
                return false;
            }
        }
        return true;
    }
}
