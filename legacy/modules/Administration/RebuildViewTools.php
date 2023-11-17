<?php

/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM,
 * Copyright (C) 2018-2023 MintHCM
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by SugarCRM"
 * logo and "Supercharged by SuiteCRM" logo and "Reinvented by MintHCM" logo.
 * If the display of the logos is not reasonably feasible for technical reasons, the
 * Appropriate Legal Notices must display the words "Powered by SugarCRM" and
 * "Supercharged by SuiteCRM" and "Reinvented by MintHCM".
 */
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $beanList, $current_user, $db, $mod_strings;
$validBeans = array();
if (!is_admin($current_user)) {
    sugar_die($GLOBALS['app_strings']['ERR_NOT_ADMIN']);
}
$admin_mod_strings = return_module_language($current_language, 'Administration');
ob_start();
?>
<style>
    .vt_rebuild_response h1 {
        font-size: x-large;
    }
</style>
<div style="border-bottom: 1px solid #cccccc; border-top: 1px solid #cccccc; margin-bottom:5px; margin-top:5px;">
    <a class="vt_rebuild" style="cursor:pointer;"><?php echo $mod_strings['LBL_CONFIGURE_REBUILD_VIEWTOOLS_TITLE']; ?></a> |



    <a href="index.php?module=Administration&action=Upgrade"><?php echo $admin_mod_strings['LBL_RETURN']; ?></a>

</div>
<div class="vt_rebuild_response">Loading </div>

<script type="text/javascript">
   $( document ).ready( function () {
      //Load actual Expression documentation
      animateLoading();
      getExpressionsDocumentation();
      //rebuild documentation
      $( '.vt_rebuild' ).on( 'click', function () {
         $( '.vt_rebuild_response' ).fadeOut( 500, function () {
            $( '.vt_rebuild_response' ).removeClass( 'loaded' ).html( '<div  id="rebuildMessage">Rebuilding</div>' ).fadeIn( 500 );
            animateLoading();
            viewTools.api.callCustomApi( {
               module: 'Home',
               action: 'rebuildLock',
               callback: function ( data ) {
                  if ( data.status == 'ok' ) {
                     checkRebuildFinish();
                  }
               }
            } );
         } );
      } );
   } );
   //Get generated html documentation based on class descriptions
   function getExpressionsDocumentation() {
      //Check if View Tools tools are already defined
      if ( window.viewTools !== undefined ) {
         viewTools.api.callCustomApi( {
            module: 'Home',
            action: 'getExpressionsDocumentation',
            callback: function ( data ) {
               if ( !$( '.vt_rebuild_response' ).hasClass( 'loaded' ) ) {
                  $( '.vt_rebuild_response' ).addClass( 'loaded' );
               }
               $( '.vt_rebuild_response' ).fadeOut( 500, function () {
                  $( '.vt_rebuild_response' ).html( data.documentation );
                  $( '.vt_rebuild_response' ).fadeIn( 500 );
                  //By default show all formulas
                  setFilter( 'vt_formula' );
               } );
            }
         } );
      } else {
         if ( !$( '.vt_rebuild_response' ).hasClass( 'loaded' ) ) {
            $( '.vt_rebuild_response' ).addClass( 'loaded' );
         }
         $( '.vt_rebuild_response' ).fadeOut( 500, function () {
            $( '.vt_rebuild_response' ).html( '' );
            $( '.vt_rebuild_response' ).fadeIn( 500 );
         } );
      }
   }
   //
   function checkRebuildFinish( rebuildLimit ) {
      if ( rebuildLimit === undefined ) {
         var rebuildLimit = 60 * 10;
      }
      if ( rebuildLimit > 0 ) {
         setTimeout( function () {
            viewTools.api.callCustomApi( {
               module: 'Home',
               action: 'rebuildCheckLock',
               callback: function ( data ) {
                  if ( data.lock !== undefined && data.lock != '' ) {
                     getExpressionsDocumentation();
                  } else {
                     checkRebuildFinish( rebuildLimit - 1 );
                  }
               }
            } );
         }, 1000 );
      } else {
         getExpressionsDocumentation();
      }
   }
   //Dispaly animated loading message
   function animateLoading() {
      if ( !$( '.vt_rebuild_response' ).hasClass( 'loaded' ) ) {
         $( '.vt_rebuild_response' ).append( '<span class="vt_dotAnimate"> &nbsp;.&nbsp; </span>' );
         $( '.vt_dotAnimate' ).each( function () {
            $( this ).removeClass( 'vt_dotAnimate' );
            $( this ).css( {'background-color': '#000'} ).animate( {'background-color': '#fff'}, 2000 );
         } );
         setTimeout( function () {
            animateLoading();
         }, 500 );
      }
   }
   //Set formula filter
   function setFilter( filter_name ) {
      $( 'div.vt_formula' ).each( function () {
         if ( $( this ).hasClass( filter_name ) ) {
            $( this ).fadeIn( 300 );
         } else {
            $( this ).fadeOut( 300 );
         }
      } );
      //Display filter selection
      $( 'a.filterButton' ).each( function () {
         if ( $( this ).hasClass( filter_name ) ) {
            $( this ).css( {'font-weight': 'bold', 'border-bottom': 'solid'} );
         } else {
            $( this ).css( {'font-weight': 'normal', 'border-bottom': 'none'} );
         }
      } );
   }
</script>
<?php
echo ob_get_clean();
