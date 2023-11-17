<?php

$surveyName = !empty($_REQUEST['name']) ? $_REQUEST['name'] : 'Survey';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $surveyName; ?></title>

    <link href="themes/SuiteP/css/bootstrap.min.css" rel="stylesheet">
    <link href="custom/include/javascript/rating/rating.min.css" rel="stylesheet">
    <link href="custom/include/javascript/datetimepicker/jquery-ui-timepicker-addon.css" rel="stylesheet">
    <link href="include/javascript/jquery/themes/base/jquery.ui.all.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <!-- MintHCM #102681 START -->
    <div class="row">
            <div class="col-md-12" style="text-align:center;margin-bottom: 10px;">
                <div class="text-center">
                    <img src="<?php echo SugarThemeRegistry::current()->getImageURL('company_logo.png'); ?>" style="max-width: 100%; max-height: 100px;"/>
                </div>
            </div>
    </div>
    <div class="row well">
        <div class="text-center col-md-12">
            <h1><?= $surveyName; ?></h1>
            <p style="font-size:16px;"><?php echo translate('LBL_SURVEY_THANKS_FOR_COMPLETING', 'Surveys') ?></p>
            <button type="button" tabindex="0" class="btn btn-success" onclick="window.location.href = 'index.php?module=Home&action=index'">
            <?php echo translate('LBL_SURVEY_RETURN_BUTTON', 'Surveys') ?>
            </button>
        </div>
    </div>
    <!-- MintHCM #102681 END -->
</div>
<script src="include/javascript/jquery/jquery-min.js"></script>
<script src="include/javascript/jquery/jquery-ui-min.js"></script>
</body>
</html>