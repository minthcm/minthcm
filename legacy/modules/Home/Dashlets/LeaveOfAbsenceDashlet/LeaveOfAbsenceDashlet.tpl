<div id='calendar'></div>



<link rel="stylesheet" href="modules/Home/Dashlets/LeaveOfAbsenceDashlet/css/monthly.css">
{literal}

    <link href='modules/Home/Dashlets/LeaveOfAbsenceDashlet/fc/fullcalendar.css' rel='stylesheet' />

    <link href='modules/Home/Dashlets/LeaveOfAbsenceDashlet/fc/fullcalendar.print.css' rel='stylesheet' media='print' />
    <script src='modules/Home/Dashlets/LeaveOfAbsenceDashlet/fc/lib/moment.min.js'></script>
    <script src='modules/Home/Dashlets/LeaveOfAbsenceDashlet/fc/fullcalendar.js'></script>
    <script src='modules/Home/Dashlets/LeaveOfAbsenceDashlet/fc/lang-all.js'></script>

    <script src='modules/Home/Dashlets/LeaveOfAbsenceDashlet/fc/qtip/jquery.qtip.min.js'></script>
    <link href='modules/Home/Dashlets/LeaveOfAbsenceDashlet/fc/qtip/jquery.qtip.min.css' rel='stylesheet' />
    <script>

        $( document ).ready( function () {

           var hiddenDays = [ ];
    {/literal}
    {assign var="show_days_of_week_i" value=0}
    {foreach from=$show_days_of_week key=day item=showed}
        {if $showed == false}
            {literal}hiddenDays.push({/literal} {$show_days_of_week_i} {literal});{/literal}
        {/if}
        {assign var="show_days_of_week_i" value=$show_days_of_week_i+1}
    {/foreach}
    {literal}
           $( '#calendar' ).fullCalendar( {
              header: {
                 left: 'prev,next today',
                 center: 'title',
                 right: 'month,basicWeek'
              },
              weekends: true,
              eventTextColor: '#000',
              lang: '{/literal}{$lang}{literal}',
              aspectRatio: 2,
              editable: false,
              eventLimit: false, // allow "more" link when too many events
              events: {
                 url: 'index.php?module=Home&action=LeaveOfAbsence&sugar_body_only=1',
                 error: function () {
                    $( '#script-warning' ).show();
                 }
              },
              eventRender: function ( event, element ) {
                 element.qtip( {
                    content: event.title
                 } );
              },
              firstDay: {/literal}{$first_day_of_week}{literal},
              hiddenDays: hiddenDays

           } );
           if ( $( '#dashlet_entire_{/literal}{$id}{literal}' ).find( '.fc-body' ).length < 1 ) {
              setTimeout( function () {
                 SUGAR.mySugar.retrieveDashlet( '{/literal}{$id}{literal}' )
              }, 300 );
           }
        } );

    </script>
    <style>
        #calendar {

            margin: 40px auto;
            padding: 0 10px;
        }

        div.fc.fc-ltr.fc-unthemed {
            max-width: 100%;
        }

        td.fc-day-number {
            background-color: #d8dbda;
            border-left-width: 1px;
            border-right-width: 1px;
            border-style: solid;
            border-top-width: 1px;
            border-width: 0;
            font-size: 12px;
        }

        .fc-unthemed .fc-today {
            background-color: rgba(0,0,0,0);
            color: #009976;
            font-weight: bold;
        }

        div#dashlet_entire_{/literal}{$id}{literal} div.fc-center h2 {
            font-size: 123.1%;
            font-weight: bold;
        }
    </style>


{/literal}