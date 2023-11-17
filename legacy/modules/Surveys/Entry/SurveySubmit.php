<?php
if (empty($_REQUEST['id'])) {
    do404();
}
$surveyId = $_REQUEST['id'];
$survey = BeanFactory::getBean('Surveys', $surveyId);
if (empty($survey->id)) {
    do404();
}
//MintHCM #74241 START
//if ($survey->status != 'Public') {
if ($survey->status != 'Active') {
    //MintHCM #74241 END
    do404();
}

$employeeId = !empty($_REQUEST['employee']) ? $_REQUEST['employee'] : '';

$trackerId = !empty($_REQUEST['tracker']) ? $_REQUEST['tracker'] : '';

require_once 'modules/Campaigns/utils.php';
if ($trackerId) {
    log_campaign_activity($trackerId, 'Survey');
}

processSurvey($survey, $trackerId, $employeeId, $_REQUEST);

function getCampaignIdFromTracker($trackerId)
{
    $db = DBManagerFactory::getInstance();
    $trackerId = $db->quote($trackerId);
    $sql = <<<EOF
            SELECT campaign_id
            FROM campaign_log WHERE target_tracker_key = "$trackerId"
EOF;

    $row = $db->fetchOne($sql);
    if ($row) {
        return $row['campaign_id'];
    }

    return false;
}

function processSurvey(Surveys $survey, $trackerId, $employeeId, $request)
{
// View Tools #52096 START
    // $contactName = 'Unknown Contact';
    // View Tools #52096 END
    $campaignId = '';
    if ($trackerId) {
        $campaignId = getCampaignIdFromTracker($trackerId);
    }

    $response = BeanFactory::newBean('SurveyResponses');
    $response->id = create_guid();
    $response->new_with_id = true;
    $employee = BeanFactory::getBean('Employees', $employeeId);
    $response->name = $survey->name . ' - ' . $employee->name;
    $response->employee_id = $employeeId;
    $response->survey_id = $survey->id;
    $response->campaign_id = $campaignId;
    $response->happiness = -1;
    $response->happiness_text = '';

    foreach ($survey->get_linked_beans('surveys_surveyquestions', 'SurveyQuestions', 'sort_order') as $question) {
        $userResponse = $request['question'][$question->id];
        switch ($question->type) {
            case "Checkbox":
                $qr = BeanFactory::newBean('SurveyQuestionResponses');
                $qr->surveyresponse_id = $response->id;
                $qr->surveyquestion_id = $question->id;
                $qr->answer_bool = $userResponse;
                $qr->save();
                break;
            case "Radio":
            case "Dropdown":
                $qr = BeanFactory::newBean('SurveyQuestionResponses');
                $qr->surveyresponse_id = $response->id;
                $qr->surveyquestion_id = $question->id;
                $qr->save();
                $qr->load_relationship('surveyquestionoptions_surveyquestionresponses');
                $qr->surveyquestionoptions_surveyquestionresponses->add($userResponse);
                break;
            case "Multiselect":
                foreach ($userResponse as $selected) {
                    $qr = BeanFactory::newBean('SurveyQuestionResponses');
                    $qr->surveyresponse_id = $response->id;
                    $qr->surveyquestion_id = $question->id;
                    $qr->save();
                    $qr->load_relationship('surveyquestionoptions_surveyquestionresponses');
                    $qr->surveyquestionoptions_surveyquestionresponses->add($selected);
                }
                break;
            case "Matrix":
                foreach ($userResponse as $key => $val) {
                    $qo = BeanFactory::getBean('SurveyQuestionOptions', $key);
                    $qr = BeanFactory::newBean('SurveyQuestionResponses');
                    $qr->surveyresponse_id = $response->id;
                    $qr->surveyquestion_id = $question->id;
                    $qr->answer = $val;
                    if ($val == 2) { //Dissatisfied
                        $response->happiness = 0;
                        $response->happiness_text .= $qo->name . " - Dissatisfied<br>";
                    }
                    $qr->save();
                    $qr->load_relationship('surveyquestionoptions_surveyquestionresponses');
                    $qr->surveyquestionoptions_surveyquestionresponses->add($key);
                }
                break;
            case "DateTime":
                $qr = BeanFactory::newBean('SurveyQuestionResponses');
                $qr->surveyresponse_id = $response->id;
                $qr->surveyquestion_id = $question->id;
                $qr->answer_datetime = $userResponse . ':00';
                $qr->save();
                break;
            case "Date":
                //TODO: Convert from user format
                $qr = BeanFactory::newBean('SurveyQuestionResponses');
                $qr->surveyresponse_id = $response->id;
                $qr->surveyquestion_id = $question->id;
                $qr->answer_datetime = $userResponse . ' 00:00:00';
                $qr->save();
                break;
            case "Textbox":
                if ($userResponse) {
                    $response->happiness = 0;
                    $response->happiness_text .= $question->name . " - " . $userResponse . "<br>";
                }
            // no break
            case "Rating":
            case "Scale":
            case "Text":
            default:
                $qr = BeanFactory::newBean('SurveyQuestionResponses');
                $qr->surveyresponse_id = $response->id;
                $qr->surveyquestion_id = $question->id;
                $qr->answer = $userResponse;
                $qr->save();
                break;
        }
    }
    $response->save();
    header('Location: index.php?entryPoint=surveyThanks&name=' . $survey->name);
}

function do404()
{
    header("HTTP/1.0 404 Not Found");
    ?>
   <html>
       <head></head>
       <body><h1>Page not found</h1></body>
   </html>
   <?php
exit();
}
