<?php
function display_comments($bean)
{
    return (new DisplayCommentsClass)->display_comments_for_record($bean, $_REQUEST['record']);
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

        $img = '';
        if (!empty($current_user->photo)) {
            $img = $this->imageBox($current_user->id);
        } else {
            $img = $this->imageBox(false);
        }

        $html = $this->commentsHead($record_id, $module_name, $current_user->id, $img);

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
    //Mint #75551, #116961 START
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
                $img = $this->imageBox(false);
            }

            $header = $name;
            $footer = $comment->date_entered;

            if (!$reply) {
                $html .= $this->commentBox($comment->id, $img, $header, $footer, $content, $comment->assigned_user_id);
            } else {
                $html .= $this->replyCommentBox($img, $header, $footer, $content, 'secondary');
            }
            if (!empty($replies)) {
                foreach ($replies as $reply) {
                    $html .= $this->display_single_comment($reply, true);
                }
            }
            return $html;
        }
    }
    //Mint #75551, #116961 END

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
        global $current_user;
        global $current_language;
        $mod_strings = return_module_language($current_language, 'News');
        //Mint #75551, #116961 START
        //on DetailView only
        // if ($action !== 'DetailView' && !$force) {
        //     return;
        // }
        //Mint #75551, #116961 END
        //current record id
        //$record = $_GET['record'];

        //Get Users roles
        require_once 'modules/ACLRoles/ACLRole.php';
        $user = $GLOBALS['current_user'];
        $id = $user->id;
        $acl = new ACLRole();
        $roles = $acl->getUserRoles($id);
        if (!empty($current_user->photo)) {
            $img = $this->imageBox($current_user->id);
        } else {
            $img = $this->imageBox(false);
        }
        $sendLbl = translate('LBL_SEND_BUTTON_LABEL');
        $your_comment_str = translate('LBL_YOUR_COMMENT');

        return $this->commentForm($record_id, $your_comment_str, $sendLbl, $img);
    }

    //Mint #75551, #116961 START
    protected function imageBox($assigned_user_id = false)
    {

        if (false === $assigned_user_id) {
            return $this->noPhotoImageBox();
        } else {
            return <<<HTML
                <span style="width: 2.5vw; height: 2.5vw; position: relative; border-radius: 50%; overflow: hidden; display: block;">
                <img src="index.php?entryPoint=download&id={$assigned_user_id}_photo&type=Users"
                style="max-height: 100%; vertical-align: middle; position: absolute; top: 50%; left: 50%; transform: translateX(-50%) translateY(-50%);" >
                </span>
HTML;
        }
    }

    protected function noPhotoImageBox()
    {
        return <<<HTML
            <span style="width: 32px; height: 32px; position: relative; border-radius: 50%; overflow: hidden; display: block;">
                <img src="themes/SuiteP/images/AMK_avatar_male.svg" style="max-height: 100%; vertical-align: middle; position: absolute; top: 50%; left: 50%; transform: translateX(-50%) translateY(-50%);" >
            </span>
HTML;
    }
    //Mint #75551, #116961 END
    protected function replyCommentBox($img, $header, $footer, $content, $comment_class)
    {
        return <<<HTML
        <div class="comment comment__reply">
            <div class="comment__avatar">{$img}</div>
            <div class="comment__header comment__header--${comment_class}">
                <div class="header">
                    $header
                </div>
                <div class="comment__text" style="padding: 5px;">$content</div>
                <div class="comment__footer comment__footer--${comment_class}">$footer</div>
            </div>
        </div>
HTML;
    }
    //Mint #75551, #116961 START
    protected function commentBox($comment_id, $img, $header, $footer, $content, $comment_assigned_user_id)
    {
        global $current_user;
        $replay_text = translate('LBL_REPLY', 'Comments');
        $comment_class = 'primary';
        if ($current_user->id == $comment_assigned_user_id) {
            $comment_class = 'secondary';
        }

        return <<<HTML
        <div class="comment" data-comment-id="{$comment_id}">
            <div class="comment__avatar">{$img}</div>
            <div class="comment__header comment__header--${comment_class}">
                <div class="header">
                    $header
                    <span class="action-menu" style="float: right;"><a class="comment__reply reply" style="cursor: pointer;">{$replay_text}</a></span>
                </div>
                <div class="comment__text" style="padding: 5px;">$content</div>
                <div class="comment__footer comment__footer--${comment_class}">$footer</div>
            </div>
        </div>
HTML;
    }

    protected function commentForm($record_id, $your_comment_str, $sendLbl, $img)
    {
        return <<<HTML
        <div class="comment">
        <div class="comment__avatar">{$img}</div>
            <form id='comments' name='comments' enctype="multipart/form-data">
                <div class="comment__input">
                    <textarea class="comment__textarea" id="comment_text" name="comment_text" cols="80" rows="2"></textarea>
                    <input class="comment__button" type='button' value='$sendLbl' onclick="addComment.call(this, '$record_id');" title="$sendLbl" name="button" />
                </div>
            </form>
        </div>
        <script>
        $( document ).ready(function() {
            $("label[for='comments_widget']").parent().hide();
        });
        </script>
HTML;
    }
    //Mint #75551, #116961 END
    protected function commentsHead($record_id, $module_name, $current_user_id, $img)
    {
        $sendLbl = translate('LBL_SEND_BUTTON_LABEL', 'app_strings');
        return <<<HTML
        <script>
            $(document).on('click', "a.reply", function() {
                $('div.reply-form').remove();
                var parent_id = $(this).parents("div.comment").attr("data-comment-id");
                var reply_form = 
                `<div class="comment reply-form">
                    <div class="comment__avatar">{$img}</div>
                        <form id='comments' name='comments' enctype="multipart/form-data">
                            <div class="comment__input">
                                <textarea class="comment__textarea" id="comment_text" name="comment_text" cols="80" rows="2"></textarea>
                                <input class="comment__button" type='button' value='$sendLbl' onclick="addComment.call(this, \'{$record_id}\','` + parent_id + `')" title="$sendLbl" name="button" />
                            </div>
                        </form>
                    </div>`;

                if ($(this).parents("div.comment").nextAll().filter("div.reply-form").length == 0) {
                    $(this).parents("div.comment").after(reply_form);
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
                                    data = data.slice(0, -2);
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
