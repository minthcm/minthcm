{if empty($defs)}
    <p class="error">{$APP.ERR_ESLIST_COL_ERROR}</p>
{else}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Catamaran:wght@400;700&family=Montserrat:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <script type="text/javascript" src='{sugar_getjspath file="include/ESListView/eslist-view.min.js"}'></script>

    <div id="es-list-slot"></div>
    
    <script type="text/javascript" defer>
        const config = {$config};
        const defs = {$defs};
        const module = '{$module}';
        const preferences = {$preferences};
        const webComponent = document.createElement('es-list');
        {literal}
        webComponent.data = { config, defs, module, preferences };
        {/literal}
        document.querySelector('#es-list-slot').replaceWith(webComponent);
    </script>
{/if}