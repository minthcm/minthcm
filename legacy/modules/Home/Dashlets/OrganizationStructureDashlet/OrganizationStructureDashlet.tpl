<div id='full-structure-dashlet' style="height:{$height}px;">
<link rel="stylesheet" href="modules/Home/Dashlets/OrganizationStructureDashlet/css/organizational-structure.css?v=3">
{if $fullscreen}
    <link rel="stylesheet" href="cache/themes/SuiteP/css/Mint/style.css">
{else}
    <a name="structure_full_screen" id="structure_full_screen" onclick="var myWindow = window.open('index.php?entryPoint=OrganizationalStructure&id={$id}', '_blank');">
        <img src="./modules/Home/Dashlets/OrganizationStructureDashlet/images/fullscreen.png?v=1" alt="{$DASHLET_STRINGS.LBL_FULLSCREEN_ALT|default:'Fullscreen mode'}">
    </a>
{/if}

<div id='org-chart-{$id}' class="os-chart-container" style="height:calc({$height}px - 20px);"></div>

{literal}
<script>
/*
 * OrganizationStructure — d3-org-chart renderer
 *
 * WHY dynamic loading:
 *   SugarCRM injects dashlet HTML via AJAX and inserts it with innerHTML (or
 *   jQuery's .html()). Browsers do NOT execute <script src="..."> tags found
 *   in innerHTML; only inline script blocks run. Loading CDN libraries via
 *   static <script> tags therefore silently fails in the dashlet context,
 *   leaving `d3` undefined and the chart blank.
 *
 *   We work around this by loading each library programmatically via
 *   document.createElement('script'), which always works regardless of how
 *   the surrounding HTML arrived in the DOM.
 */
(function () {
    var containerId = '#org-chart-{/literal}{$id|escape:'javascript'}{literal}';
    var orgData     = {/literal}{$jsonTree nofilter}{literal};

    // Libraries are served locally — no CDN dependency, no CSP issues.
    var BASE = 'modules/Home/Dashlets/OrganizationStructureDashlet/js/';
    var CDN_D3       = BASE + 'd3.v7.min.js';
    var CDN_FLEX     = BASE + 'd3-flextree.js';
    var CDN_ORGCHART = BASE + 'd3-org-chart.min.js';

    var orgLogoUrl    = '{/literal}{$logoUrl|escape:'javascript'}{literal}';
    var orgSystemName = '{/literal}{$systemName|escape:'javascript'}{literal}';

    // ── Helpers ───────────────────────────────────────────────────────────
    function escHtml(str) {
        if (!str) { return ''; }
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function buildInitials(name) {
        return (name || '').split(' ')
            .filter(function (n) { return n.length > 0; })
            .map(function (n) { return n.charAt(0).toUpperCase(); })
            .slice(0, 2).join('');
    }

    function renderNode(d) {
        var p = d.data;
        var w = d.width;
        var h = d.height;

        // Virtual root node: branded company header with logo + system name.
        if (p.isVirtualRoot) {
            var logoHtml = '';
            if (orgLogoUrl) {
                logoHtml = '<div class="os-root-logo-wrap">'
                    + '<img class="os-root-logo" src="' + escHtml(orgLogoUrl) + '" alt=""'
                    + ' onerror="this.parentNode.style.display=\'none\'">'
                    + '</div>';
            }
            var displayName = escHtml(orgSystemName || p.name || '');
            var nameHtml = displayName ? '<div class="os-root-name">' + displayName + '</div>' : '';
            return '<div class="os-node-card os-node-card--root" style="width:' + w + 'px;height:' + h + 'px;">'
                + '<div class="os-root-inner">' + logoHtml + nameHtml + '</div>'
                + '</div>';
        }

        var avatarInner = p.photoUrl
            ? '<img class="os-avatar" src="' + escHtml(p.photoUrl) + '" alt="">'
            : '<div class="os-avatar-fallback">' + escHtml(buildInitials(p.name)) + '</div>';

        var posHtml = p.position
            ? '<div class="os-position">' + escHtml(p.position) + '</div>'
            : '';

        var badgeClass = 'os-badge'
            + (p.departmentType ? ' os-badge--' + escHtml(p.departmentType) : '');
        var deptHtml = p.department
            ? '<span class="' + badgeClass + '">' + escHtml(p.department) + '</span>'
            : '';

        // Wrap avatar
        var avatarHtml = '<div class="os-avatar-wrap">'
            + avatarInner
            + '</div>';

        // Content column
        var contentHtml = '<div class="os-content">'
            + '<div class="os-name">' + escHtml(p.name || '') + '</div>'
            + posHtml
            + deptHtml
            + '</div>';

        // Use <a> as card root when a detail URL is available so the whole
        // card is clickable without nested interactive elements.
        var cardTag  = p.detailUrl ? 'a' : 'div';
        var hrefAttr = p.detailUrl
            ? ' href="' + escHtml(p.detailUrl) + '" target="_blank"'
            : '';

        return '<' + cardTag + ' class="os-node-card"' + hrefAttr
            + ' style="width:' + w + 'px;height:' + h + 'px;">'
            + avatarHtml
            + contentHtml
            + '</' + cardTag + '>';
    }

    // ── Chart init ────────────────────────────────────────────────────────
    function buildChart() {
        var el = document.querySelector(containerId);
        if (!el) {
            console.warn('[OrgChart] Container not found: ' + containerId);
            return;
        }
        if (!orgData || orgData.length === 0) {
            el.innerHTML = '<p style="padding:20px;color:#888;text-align:center;">{/literal}{$DASHLET_STRINGS.LBL_NO_EMPLOYEES_FOUND|default:'No employees found.'}{literal}</p>';
            return;
        }

        var chart = new d3.OrgChart()
            .container(containerId)
            .data(orgData)
            .nodeId(function (d) { return d.id; })
            .parentNodeId(function (d) { return d.parentId; })
            .nodeWidth(function (d) { return d.data.isVirtualRoot ? 240 : 230; })
            .nodeHeight(function (d) { return d.data.isVirtualRoot ? 96 : 88; })
            .childrenMargin(function () { return 60; })
            .siblingsMargin(function () { return 24; })
            .initialExpandLevel(3)
            .nodeContent(renderNode)
            .render();

        chart.fit();
    }

    // ── Script loader ─────────────────────────────────────────────────────
    // requireScript loads a URL once and calls cb when ready.
    // Uses a data-os-loaded attribute so multiple dashlets on the same page
    // don't re-fetch libraries that are already loaded or loading.
    function requireScript(url, cb) {
        var marker = 'data-os-chart';
        var existing = document.querySelector('script[' + marker + '="' + url + '"]');
        if (existing) {
            if (existing.getAttribute('data-os-loaded') === '1') {
                cb();           // already fully loaded
            } else {
                existing.addEventListener('load', cb);  // in progress
            }
            return;
        }
        var s = document.createElement('script');
        s.setAttribute(marker, url);
        s.src = url;
        s.onload = function () {
            s.setAttribute('data-os-loaded', '1');
            cb();
        };
        s.onerror = function () {
            console.error('[OrgChart] Failed to load ' + url);
        };
        document.head.appendChild(s);
    }

    function loadAndBuild() {
        // Fast path: all libraries already present (e.g. second dashlet, page reload)
        if (typeof window.d3 !== 'undefined' && typeof d3.OrgChart === 'function') {
            buildChart();
            return;
        }
        requireScript(CDN_D3, function () {
            requireScript(CDN_FLEX, function () {
                requireScript(CDN_ORGCHART, buildChart);
            });
        });
    }

    // Tiny setTimeout(0) lets the dashlet container finish being inserted into
    // the DOM and get its final dimensions before d3 measures it.
    setTimeout(loadAndBuild, 0);
}());
</script>
{/literal}

</div>
</div>
