{if empty($columns)}<p class="error">{$APP.ERR_KANBAN_COL_ERROR}</p>
    {else}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css">
        <script type="text/javascript" src='{sugar_getjspath file="include/KanbanView/kanban-view.min.js"}'></script>
        <script type="text/javascript" src='{sugar_getjspath file="include/KanbanView/Kanban.js"}'></script>
        {literal}
            <style>
                kanban-view {
                    box-sizing: border-box;
                    -webkit-font-smoothing: antialiased;
                }
                .moduleTitle .module-title-text {
                    display:none;
                }
            </style>
        {/literal}
        <kanban-view />
        <script type="text/javascript">
            window.kanban = new Kanban({$json},'{$module}');
            window.kanban.init();
        </script>
    {/if}