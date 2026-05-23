<?php

function display_comments($bean)
{
    return <<<HTML
        <iframe id='comments-widget' src='../#/comments/{$bean->module_name}/{$bean->id}' style='border:none;height: 1500px; width: 1000px' scrolling="no"></iframe>
        <script>
            function resizeCommentsWidget() {
                const commentsWidget = document.querySelector('#comments-widget')
                if (commentsWidget) {
                    commentsWidget.style.height = commentsWidget.contentDocument.body.scrollHeight + 'px'
                }
            }
            document.querySelector('#comments-widget')?.addEventListener('load', () => {
                resizeCommentsWidget()
                setInterval(resizeCommentsWidget, 100)
            })
        </script>
    HTML;
}
