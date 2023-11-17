<?php

class CandidaturesApi
{
    public function setStatusHiredAndRejected($args)
    {
        if (empty($args['record_id'])) {
            return false;
        }
        $record_id = $args['record_id'];
        if (empty($record_id)) {
            return;
        }

        $candidature = BeanFactory::getBean('Candidatures', $record_id);
        if (empty($candidature)) {
            return;
        }

        $candidatureBean = BeanFactory::getBean('Candidatures');
        $candidaturesList = $candidatureBean->get_list(
            'candidate_name',
            "candidate_name = '$candidature->candidate_name'",
        );
        if (
            !empty($candidaturesList)
            && $candidaturesList['row_count'] > 1
        ) {
            foreach ($candidaturesList['list'] as $rejectCandidature) {
                if ($rejectCandidature->id != $candidature->id) {
                    $rejectCandidature->status = 'Rejected';
                    $rejectCandidature->save();
                }
            }
        }

        $candidature->status = 'Hired';
        $candidature->save();

    }
}
