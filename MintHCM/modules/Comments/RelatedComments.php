<?php
function display_comments($bean)
{
    return (new DisplayCommentsClass)->display_comments_for_record($bean, $_GET['record']);
}
function display_comments_for_record($bean, $record_id, $force = false)
{
    return (new DisplayCommentsClass)->display_comments_for_record($bean, $record_id, $force);
}
class DisplayCommentsClass
{
    public function display_comments_for_record($bean, $record_id, $force = false)
    {
        if (empty($bean->id)) {
            $GLOBALS['log']->fatal(basename(__FILE__) . ':' . __LINE__ . print_r([], 1));
            return '';
        }
        $module_name = $bean->object_name; //get_class($bean); // Customs beans

        global $mod_strings, $current_user;

        $html = $this->commentsHead($record_id, $module_name, $current_user->id);

        $comments = $bean->get_linked_beans('comments', 'Comments');
        if ($comments) {
            $html .= '<div>';
            $main_comments = [];
            foreach ($comments as $comment) {
                if (!$comment->reply_to_id) {
                    $main_comments[] = $comment;
                }
            }
            $this->usort($main_comments);

            foreach ($main_comments as $comment) {
                $html .= $this->display_single_comment($comment);
            }
            $html .= '</div>';
        }
        $html .= $this->quick_edit_comments($record_id, $force);
        return $html;
    }

    public function display_single_comment($comment, $reply = false)
    {
        $html = '';
        /*if assigned user*/
        if ($comment->assigned_user_id) {
            $replies = $comment->get_linked_beans('replies', 'Comments');
            if (!empty($replies)) {
                $this->usort($replies);
            }
            $name = $comment->getAuthorFullName();
            $content = nl2br(html_entity_decode($comment->description));

            if (!empty($comment->getAuthorPhoto())) {
                $img = $this->imageBox($comment->assigned_user_id);
            } else {
                $img = $this->imageBox();
            }

            $header = $name . ': ' . $comment->date_entered;

            if (!$reply) {
                $html .= $this->commentBox($comment->id, $img, $header, $content);
            } else {
                $html .= $this->replyCommentBox($img, $header, $content);
            }
            if (!empty($replies)) {
                foreach ($replies as $reply) {
                    $html .= $this->display_single_comment($reply, true);
                }
            }
            return $html;
        }
    }

    protected function usort(&$sortTable)
    {
        usort(
            $sortTable,
            function ($a, $b) {
                $aDate = $a->fetched_row['date_entered'];
                $bDate = $b->fetched_row['date_entered'];
                if ($aDate < $bDate) {
                    return -1;
                } elseif ($aDate > $bDate) {
                    return 1;
                }
                return 0;
            }
        );
    }

    public function quick_edit_comments($record_id, $force = false)
    {
        global $action;
        global $currentModule;
        global $current_language;
        $mod_strings = return_module_language($current_language, 'News');

        //on DetailView only
        if ($action !== 'DetailView' && !$force) {
            return;
        }

        //current record id
        //$record = $_GET['record'];

        //Get Users roles
        require_once 'modules/ACLRoles/ACLRole.php';
        $user = $GLOBALS['current_user'];
        $id = $user->id;
        $acl = new ACLRole();
        $roles = $acl->getUserRoles($id);

        $sendLbl = translate('LBL_SEND_BUTTON_LABEL');
        $your_comment_str = translate('LBL_YOUR_COMMENT');

        return $this->commentForm($record_id, $your_comment_str, $sendLbl);
    }
    protected function imageBox($assigned_user_id = false)
    {

        if ($assigned_user_id === false) {
            return $this->noPhotoImageBox();
        } else {
            return <<<HTML
            <span style="width: 3vw; height: 3vw; position: relative; border-radius: 50%; overflow: hidden; border:2px solid #aaaaaa; display: block;">
                <img src="index.php?entryPoint=download&id={$assigned_user_id}_photo&type=Users"
                style="max-height: 100%; vertical-align: middle; position: absolute; top: 50%; left: 50%; transform: translateX(-50%) translateY(-50%);" >
                </span>
HTML;
        }
    }
    protected function noPhotoImageBox()
    {
        return <<<HTML
        <span style="width: 3vw; height: 3vw; position: relative; border-radius: 50%; overflow: hidden; border:2px solid #aaaaaa; display: block;">
            <img src="themes/SuiteP/images/no_photo.png" style="max-height: 100%; vertical-align: middle; position: absolute; top: 50%; left: 50%; transform: translateX(-50%) translateY(-50%);" >
        </span>
HTML;
    }
    protected function replyCommentBox($img, $header, $content)
    {
        return <<<HTML
        <div class="content-container"
            style="display: grid; grid-template-columns: repeat(10, 1fr); margin: 10px auto; grid-auto-rows: minmax(3vw, auto); grid-gap: 10px; background: #cccccc; border-radius: 4px; padding: 10px;">
            <div class="" style=" grid-column: 2; justify-items: center; align-items: center;">{$img}</div>
            <div class="comment" style="grid-column: 3/11;">
                <div class="header" style="border-bottom: 1px solid #aaaaaa;">$header</div>
                <div class="comment-text">$content</div>
            </div>
        </div>
HTML;
    }
    protected function commentBox($comment_id, $img, $header, $content)
    {
        $replay_text = translate('LBL_REPLY', 'Comments');
        return <<<HTML
        <div class="comment-container" data-comment-id="{$comment_id}"
        style="display: grid; grid-template-columns: repeat(10, 1fr); margin: 10px auto; grid-auto-rows: minmax(3vw, auto); grid-gap: 10px; background: #cccccc; border-radius: 4px; padding: 10px;">
            <div class="" style=" grid-column: 1; justify-items: center; align-items: center;">{$img}</div>
            <div class="comment" style="grid-column: 2/11;">
                <div class="header" style="border-bottom: 1px solid #aaaaaa;">
                    $header
                    <span class="action-menu" style="float: right;"><a class="reply" style="cursor: pointer;">{$replay_text}</a></span>
                </div>
                <div class="comment-text">$content</div>
            </div>
        </div>
HTML;
    }
    protected function commentForm($record_id, $your_comment_str, $sendLbl)
    {
        return <<<HTML
        <div class="comment-form" style="margin: 10px auto; width: 100%;">
            <form id='comments' name='comments' enctype="multipart/form-data">
                <div><label for="comment_text">{$your_comment_str}</label></div>
                <div style="margin: 5px auto; width: 100%;"><textarea id="comment_text" name="comment_text" cols="80" rows="4" style="max-width: 400px; max-height: 150px"></textarea></div>
                <input type='button' value='$sendLbl' onclick="addComment.call(this, '$record_id')" title="$sendLbl" name="button" />
            </form>
        </div>
HTML;
    }
    protected function commentsHead($record_id, $module_name, $current_user_id)
    {
        return <<<HTML
        <script>
            $(document).on('click', "a.reply", function() {
                $('div.reply-form').remove();
                var parent_id = $(this).parents("div.comment-container").attr("data-comment-id");
                var reply_form = '<div class="reply-form" style="margin: 10px auto; width: 100%;"><form id="comments_replay" name="comments_replay" enctype="multipart/form-data">'
                + '<div><label for="comment_text">'+SUGAR.language.get('app_strings', 'LBL_YOUR_REPLY')+'</label></div>'
                + '<div style="margin: 5px auto; width: 100%;"><textarea id="comment_text" name="comment_text" cols="80" rows="4" style="max-width: 400px; max-height: 150px"></textarea></div>'
                + '<input type="button" value="'+SUGAR.language.get('app_strings', 'LBL_SEND_BUTTON_LABEL')+'" onclick="addComment.call(this, \'{$record_id}\',\''+ parent_id +'\')" title="'+SUGAR.language.get('app_strings', 'LBL_SEND_BUTTON_LABEL')+'" name="button"> </input>'
                + '</br></form></div>';

                if ($(this).parents("div.comment-container").nextAll().filter("div.reply-form").length == 0) {
                    $(this).parents("div.comment-container").after(reply_form);
                }
            });

            function addComment(record, parent_id = null){
                viewTools.GUI.fieldErrorUnmark();
                var comment_text = encodeURIComponent(this.form.comment_text.value);
                if(!comment_text.length) {
                    viewTools.GUI.fieldErrorMark($(this.form.comment_text), viewTools.language.get('app_strings', 'ERR_MISSING_REQUIRED_FIELDS') + ' ' + viewTools.language.get('Comments', 'LBL_DESCRIPTION'));
                    return;
                }
                loadingMessgPanl = new YAHOO.widget.SimpleDialog('loading', {
                    width: '200px',
                    close: true,
                    modal: true,
                    visible: true,
                    fixedcenter: true,
                    constraintoviewport: true,
                    draggable: false
                });
                loadingMessgPanl.setHeader(SUGAR.language.get('app_strings', 'LBL_EMAIL_PERFORMING_TASK'));
                loadingMessgPanl.setBody(SUGAR.language.get('app_strings', 'LBL_EMAIL_ONE_MOMENT'));
                loadingMessgPanl.render(document.body);
                loadingMessgPanl.show();
                var params = "record="+record+"&module=Comments&return_module={$module_name}&action=Save&return_id="+record+"&return_action=DetailView&relate_to={$module_name}&relate_id="+record+"&offset=1&description="
                + comment_text + "&parent_id=" + record + "&parent_type={$module_name}&name=" + comment_text.substring(0,255) + "&assigned_user_id={$current_user_id}";
                if (parent_id != null) {
                    params += '&reply_to_id=' + parent_id;
                }
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.open("POST", "index.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                //xmlhttp.setRequestHeader("Content-length", params.length);
                //xmlhttp.setRequestHeader("Connection", "close");
                //When button is clicked
                xmlhttp.onreadystatechange = function() {
                    if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        if($('[comment-for-id='+record+']').length==1){
                            viewTools.api.callCustomApi({
                                module: 'Comments',
                                action: 'getRelatedNewsHTML',
                                dataType:"html",
                                dataPOST: {
                                    comments_record_id: record,
                                    comments_module: '{$module_name}',
                                },
                                callback: function (data) {
                                    $('[comment-for-id='+record+']').html(data);
                                    loadingMessgPanl.hide();
                                }
                            });
                        }
                        else{
                            $("[data-id=LBL_PANEL_COMMENTS]").load("index.php?module={$module_name}&action=DetailView&record="+record + " [data-id=LBL_PANEL_COMMENTS]", function(){
                                loadingMessgPanl.hide();
                            });
                        }
                    }
                }
                xmlhttp.send(params);
            }
        </script>
HTML;
    }

}
