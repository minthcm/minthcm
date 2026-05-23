<link rel="stylesheet" type="text/css" href="modules/Home/Dashlets/TimerDashlet/TimerDashlet.css" />
<div class="TimerDashlet">
   <table border="0" style="width:100%">
      <tr>
         <td style="width:20%">
            <div class="timer-button button-play">&nbsp;</div>
            <div class="timer-button button-pause">&nbsp;</div>
            <div class="timer-button button-stop">&nbsp;</div>
         </td>
         <td style="width:30%"><div class="timer-output"></div></td>
         <td style="width:50%"><input class="timer-input" style="width:100%" /></td>
      </tr>
   </table>
</div>
<script type="text/javascript" src="modules/Home/Dashlets/TimerDashlet/TimerDashlet.js"></script>
<script type="text/javascript">
   (function (){ldelim}
         var d = document.querySelectorAll('.TimerDashlet');
         if (d.length)
            new TimerDashlet(d[d.length - 1]);
   {rdelim})();
</script>
