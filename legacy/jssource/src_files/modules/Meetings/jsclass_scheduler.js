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


//////////////////////////////////////////////////
// class: SugarWidgetListView
// widget to display a list view
//
//////////////////////////////////////////////////

SugarClass.inherit( "SugarWidgetListView", "SugarClass" );

function SugarWidgetListView() {
   this.init();
}

SugarWidgetListView.prototype.init = function () {

}

SugarWidgetListView.prototype.load = function ( parentNode ) {
   this.parentNode = parentNode;
   this.display();
}

SugarWidgetListView.prototype.display = function () {

   if ( typeof GLOBAL_REGISTRY['result_list'] == 'undefined' ) {
      this.display_loading();
      return;
   }

   var div = document.getElementById( 'list_div_win' );
   div.style.display = 'block';
   //div.style.height='125px';
   var html = '<table width="100%" cellpadding="0" cellspacing="0" border="0" class="list view">';
   html += '<tr>';
   html += '<th width="2%" nowrap="nowrap">&nbsp;</th>';
   html += '<th width="20%" nowrap="nowrap">' + GLOBAL_REGISTRY['meeting_strings']['LBL_NAME'] + '</th>';
   html += '<th width="20%" nowrap="nowrap">' + GLOBAL_REGISTRY['meeting_strings']['LBL_EMAIL'] + '</th>';
   html += '<th width="20%" nowrap="nowrap">' + GLOBAL_REGISTRY['meeting_strings']['LBL_PHONE'] + '</th>';
   html += '<th width="18%" nowrap="nowrap">&nbsp;</th>';
   html += '</tr>';
   //var html = '<table width="100%" cellpadding="0" cellspacing="0">';
   for ( var i = 0; i < GLOBAL_REGISTRY['result_list'].length; i++ ) {
      var bean = GLOBAL_REGISTRY['result_list'][i];
      var disabled = false;
      var className = 'evenListRowS1';

      if ( typeof (GLOBAL_REGISTRY.focus.users_arr_hash[ bean.fields.id]) != 'undefined' ) {
         disabled = true;
      }
      if ( (i % 2) == 0 ) {
         className = 'oddListRowS1';
      } else {
         className = 'evenListRowS1';
      }
      if ( typeof (bean.fields.first_name) == 'undefined' ) {
         bean.fields.first_name = '';
      }
      if ( typeof (bean.fields.email1) == 'undefined' || bean.fields.email1 == "" ) {
         bean.fields.email1 = '&nbsp;';
      }
      if ( typeof (bean.fields.phone_work) == 'undefined' || bean.fields.phone_work == "" ) {
         bean.fields.phone_work = '&nbsp;';
      }

      html += '<tr class="' + className + '">';
      html += '<td><span class="suitepicon suitepicon-module-' + bean.module.toLowerCase().replace( '_', '-' ) + '"></span></td>';
        // MintHCM #54195 #59793  #43484 Start
        if (bean.module == 'Resources' || bean.module == "SecurityGroup") {
        // MintHCM #43484 End
         html += '<td>' + bean.fields.name + '</td><td></td><td></td>';
      } else {
         html += '<td>' + bean.fields.full_name + '</td>';
         html += '<td>' + bean.fields.email1 + '</td>';
         if ( bean.module == 'Candidates' ) {
            html += '<td>' + bean.fields.phone_mobile + '</td>';
         } else {
            html += '<td>' + bean.fields.phone_work + '</td>';
         }
      }
        // MintHCM #54195 #59793 
      html += '<td align="right">';
      //	hidden = 'hidden';
      hidden = 'visible';
      if ( !disabled ) {
         //	hidden = 'visible';
      }
      html += '<input type="button" id="invitees_add_' + (i + 1) + '" class="button" onclick="this.disabled=true;SugarWidgetSchedulerAttendees.form_add_attendee(' + i + ');" value="' + GLOBAL_REGISTRY['meeting_strings']['LBL_ADD_BUTTON'] + '"/ style="visibility: ' + hidden + '"/>';
      html += '</td>';

      html += '</tr>';
   }
   html += '</table>';
   //this.parentNode.innerHTML = html;

   div.innerHTML = html;
}
// MintHCM #43484 Start
SugarWidgetSchedulerAttendees.get_users = function (list_row) {

    if (typeof (GLOBAL_REGISTRY.result_list[list_row]) != 'undefined') {
        viewTools.api.callCustomApi( {
            module: 'Meetings',
            action: 'getUsers',
            dataPOST: {
               id: GLOBAL_REGISTRY.result_list[list_row].fields.id
            },
            callback: function ( data ) {
                data.forEach((person) => {
                if(!SugarWidgetSchedulerAttendees.userOnList(person.id)){
                    item = {
                        fields:person,
                        module:"User"
                    }
                    GLOBAL_REGISTRY.focus.users_arr.push(item)
                }

              });
              
            }
         } );
    }
}

SugarWidgetSchedulerAttendees.userOnList = function (id){
    var user_on_list = false;
    for(var i = 0; i<GLOBAL_REGISTRY.focus.users_arr.length; i++){
        if(GLOBAL_REGISTRY.focus.users_arr[i].fields.id == id){
            user_on_list = true;
            break;
        }
    }
    return user_on_list;
}

// MintHCM #43484 End
SugarWidgetListView.prototype.display_loading = function () {

}

//////////////////////////////////////////////////
// class: SugarWidgetSchedulerSearch
// widget to display the meeting scheduler search box
//
//////////////////////////////////////////////////

SugarClass.inherit( "SugarWidgetSchedulerSearch", "SugarClass" );

function SugarWidgetSchedulerSearch() {
   this.init();
}

SugarWidgetSchedulerSearch.prototype.init = function () {
   this.form_id = 'scheduler_search';
   GLOBAL_REGISTRY['widget_element_map'] = new Object();
   GLOBAL_REGISTRY['widget_element_map'][this.form_id] = this;
   GLOBAL_REGISTRY.scheduler_search_obj = this;
}

SugarWidgetSchedulerSearch.prototype.load = function ( parentNode ) {
   this.parentNode = parentNode;
   this.display();
}

SugarWidgetSchedulerSearch.submit = function ( form ) {

   //construct query obj:
   var conditions = new Array();

   if ( form.search_first_name.value != '' ) {
      conditions[conditions.length] = {"name": "first_name", "op": "starts_with", "value": form.search_first_name.value}
   }
   if ( form.search_last_name.value != '' ) {
      conditions[conditions.length] = {"name": "last_name", "op": "starts_with", "value": form.search_last_name.value}
   }
   if ( form.search_email.value != '' ) {
      conditions[conditions.length] = {"name": "email1", "op": "starts_with", "value": form.search_email.value}
   }

   var query = {
        // MintHCM #54195 #59793 #43484 #117141 Start
        "modules": ["Users", "Candidates", "Resources", "SecurityGroups"],
        "field_list": [ 'id', 'name', 'full_name', 'email1', 'phone_work', 'phone_mobile', 'show_on_employees', 'group_type'],
        // MintHCM #54195 #59793 #43484 #117141 End
        "group": "and",
        "conditions": conditions
   };
   global_request_registry[req_count] = [ this, 'display' ];
   req_id = global_rpcClient.call_method( 'query', query );
   global_request_registry[req_id] = [ GLOBAL_REGISTRY['widget_element_map'][form.id], 'refresh_list' ];
}

SugarWidgetSchedulerSearch.prototype.refresh_list = function ( rslt ) {
   GLOBAL_REGISTRY['result_list'] = rslt['list'];

   if ( rslt['list'].length > 0 ) {
      this.list_view.display();
      document.getElementById( 'empty-search-message' ).style.display = 'none';
   } else {
      document.getElementById( 'list_div_win' ).style.display = 'none';
      document.getElementById( 'empty-search-message' ).style.display = '';
   }

}

SugarWidgetSchedulerSearch.prototype.display = function () {

   // append the list_view as the third row of the outside table
   var html = document.createElement( "div" );
   html.setAttribute( 'class', 'schedulerInvitees' );

   var h3 = document.createElement( "h3" );
   h3.innerHTML = GLOBAL_REGISTRY['meeting_strings']['LBL_ADD_INVITEE'];
   html.appendChild( h3 );

   var table1 = document.createElement( "table" );
   table1.setAttribute( 'class', 'edit view' );
   table1.setAttribute( 'border', '0' );
   table1.setAttribute( 'cellpadding', '0' );
   table1.setAttribute( 'cellspacing', '0' );
   table1.setAttribute( 'width', '100%' );
   var row1 = table1.insertRow( 0 );
   var cell1 = row1.insertCell( 0 );

   var form = document.createElement( "form" );
   form.setAttribute( 'name', 'schedulerwidget' );
   form.setAttribute( 'id', this.form_id );
   form.setAttribute( 'onsubmit', 'SugarWidgetSchedulerSearch.submit(this);return false;' );

   var table2 = document.createElement( "table" );
   table2.setAttribute( 'border', '0' );
   table2.setAttribute( 'cellpadding', '0' );
   table2.setAttribute( 'cellspacing', '0' );
   table2.setAttribute( 'width', '100%' );

   var row2 = table2.insertRow( 0 );
   var cell21 = row2.insertCell( 0 );
   cell21.setAttribute( 'scope', 'col' );
   cell21.setAttribute( 'nowrap', 'nowrap' );

   var label1 = document.createElement( "label" );
   label1.setAttribute( 'for', 'search_first_name' );
   label1.innerHTML = GLOBAL_REGISTRY['meeting_strings']['LBL_FIRST_NAME'] + ':&nbsp;&nbsp;';
   cell21.appendChild( label1 );

   var input1 = document.createElement( "input" );
   input1.setAttribute( 'name', 'search_first_name' );
   input1.setAttribute( 'id', 'search_first_name' );
   input1.setAttribute( 'value', '' );
   input1.setAttribute( 'type', 'text' );
   input1.setAttribute( 'size', '10' );
   cell21.appendChild( input1 );

   var cell22 = row2.insertCell( 1 );
   cell22.setAttribute( 'scope', 'col' );
   cell22.setAttribute( 'nowrap', 'nowrap' );

   var label2 = document.createElement( "label" );
   label2.setAttribute( 'for', 'search_last_name' );
   label2.innerHTML = GLOBAL_REGISTRY['meeting_strings']['LBL_LAST_NAME'] + ':&nbsp;&nbsp;';
   cell22.appendChild( label2 );

   var input2 = document.createElement( "input" );
   input2.setAttribute( 'name', 'search_last_name' );
   input2.setAttribute( 'id', 'search_last_name' );
   input2.setAttribute( 'value', '' );
   input2.setAttribute( 'type', 'text' );
   input2.setAttribute( 'size', '10' );
   cell22.appendChild( input2 );

   var cell23 = row2.insertCell( 2 );
   cell23.setAttribute( 'scope', 'col' );
   cell23.setAttribute( 'nowrap', 'nowrap' );

   var label3 = document.createElement( "label" );
   label3.setAttribute( 'for', 'search_email' );
   label3.innerHTML = GLOBAL_REGISTRY['meeting_strings']['LBL_EMAIL'] + ':&nbsp;&nbsp;';
   cell23.appendChild( label3 );

   var input3 = document.createElement( "input" );
   input3.setAttribute( 'name', 'search_email' );
   input3.setAttribute( 'id', 'search_email' );
   input3.setAttribute( 'value', '' );
   input3.setAttribute( 'type', 'text' );
   input3.setAttribute( 'size', '10' );
   cell23.appendChild( input3 );

   var cell24 = row2.insertCell( 3 );
   cell24.setAttribute( 'valign', 'center' );

   var input3 = document.createElement( "input" );
   input3.setAttribute( 'class', 'button' );
   input3.setAttribute( 'id', 'invitees_search' );
   input3.setAttribute( 'value', GLOBAL_REGISTRY['meeting_strings']['LBL_SEARCH_BUTTON'] );
   input3.setAttribute( 'type', 'submit' );
   cell24.appendChild( input3 );

   form.appendChild( table2 );
   cell1.appendChild( form );
   html.appendChild( table1 );

   this.parentNode.appendChild( html );

   var empty_search_message_div = document.createElement("div");
   empty_search_message_div.setAttribute('id','create-invitees');
   empty_search_message_div.setAttribute('style','margin-bottom: 10px;');

   var empty_search_message = document.createElement("div");
   empty_search_message.setAttribute('id','empty-search-message');
   empty_search_message.setAttribute('style','display: none;');
   empty_search_message.innerHTML = GLOBAL_REGISTRY['meeting_strings']['LBL_EMPTY_SEARCH_RESULT'];
   empty_search_message_div.appendChild(empty_search_message);

   this.parentNode.appendChild(empty_search_message_div);

   var div = document.createElement( 'div' );
   div.setAttribute( 'id', 'list_div_win' );
   div.style.overflow = 'auto';
   div.style.width = '100%';
   div.style.height = '100%';
   div.style.display = 'none';
   this.parentNode.appendChild( div );
   this.list_view = new SugarWidgetListView();
   this.list_view.load( div );
}

SugarWidgetSchedulerSearch.resetSearchForm = function () {
   if ( GLOBAL_REGISTRY.scheduler_search_obj && document.forms[GLOBAL_REGISTRY.scheduler_search_obj.form_id] ) {
      //if search form is initiated, it clears the input fields.
      document.forms[GLOBAL_REGISTRY.scheduler_search_obj.form_id].reset();
   }
}

//////////////////////////////////////////////////
// class: SugarWidgetScheduler
// widget to display the meeting scheduler
//
//////////////////////////////////////////////////

SugarClass.inherit( "SugarWidgetScheduler", "SugarClass" );

SugarWidgetScheduler.popupControl = null;
SugarWidgetScheduler.popupControlDelayTime = 600;
SugarWidgetScheduler.mouseX = 0;
SugarWidgetScheduler.mouseY = 0;
SugarWidgetScheduler.isMouseOverToolTip = false;

function SugarWidgetScheduler() {
   this.init();
}

SugarWidgetScheduler.prototype.init = function () {
   //var row = new	SugarWidgetScheduleAttendees();
   //row.load(this);
}

SugarWidgetScheduler.prototype.load = function ( parentNode ) {
   this.parentNode = parentNode;
   this.display();
}

SugarWidgetScheduler.fill_invitees = function ( form ) {
   for ( var i = 0; i < GLOBAL_REGISTRY.focus.users_arr.length; i++ ) {
      if ( GLOBAL_REGISTRY.focus.users_arr[i].module == 'User' ) {
         form.user_invitees.value += GLOBAL_REGISTRY.focus.users_arr[i].fields.id + ",";
      } else if ( GLOBAL_REGISTRY.focus.users_arr[i].module == 'Contact' ) {
         form.contact_invitees.value += GLOBAL_REGISTRY.focus.users_arr[i].fields.id + ",";
      } else if ( GLOBAL_REGISTRY.focus.users_arr[i].module == 'Lead' ) {
         form.lead_invitees.value += GLOBAL_REGISTRY.focus.users_arr[i].fields.id + ",";
      }
      // MintHCM #54195 #59793 Start 
      else if ( GLOBAL_REGISTRY.focus.users_arr[i].module == 'Candidates' ) {
         form.candidate_invitees.value += GLOBAL_REGISTRY.focus.users_arr[i].fields.id + ",";
      } else if ( GLOBAL_REGISTRY.focus.users_arr[i].module == 'Resources' ) {
         form.resource_invitees.value += GLOBAL_REGISTRY.focus.users_arr[i].fields.id + ",";
      }
      // MintHCM #54195 #59793 End
   }
}

SugarWidgetScheduler.update_time = function () {

   var form_name;
   if ( typeof document.EditView != 'undefined' )
      form_name = "EditView";
   else if ( typeof document.CalendarEditView != 'undefined' )
      form_name = "CalendarEditView";
   else
      return;

   //check for field value, we can't do anything if it doesnt exist.
   if ( typeof document.forms[form_name].date_start == 'undefined' )
      return;

   var date_start = document.forms[form_name].date_start.value;
   if ( date_start.length < 16 ) {
      return;
   }
   var hour_start = parseInt( date_start.substring( 11, 13 ), 10 );
   var minute_start = parseInt( date_start.substring( 14, 16 ), 10 );
   var has_meridiem = /am|pm/i.test( date_start );
   if ( has_meridiem ) {
      var meridiem = trim( date_start.substring( 16 ) );
   }

   GLOBAL_REGISTRY.focus.fields.date_start = date_start;

   if ( has_meridiem ) {
      GLOBAL_REGISTRY.focus.fields.time_start = hour_start + time_separator + minute_start + meridiem;
   } else {
      GLOBAL_REGISTRY.focus.fields.time_start = hour_start + time_separator + minute_start;
   }

   GLOBAL_REGISTRY.focus.fields.duration_hours = document.forms[form_name].duration_hours.value;
   GLOBAL_REGISTRY.focus.fields.duration_minutes = document.forms[form_name].duration_minutes.value;
   GLOBAL_REGISTRY.focus.fields.datetime_start = SugarDateTime.mysql2jsDateTime( GLOBAL_REGISTRY.focus.fields.date_start, GLOBAL_REGISTRY.focus.fields.time_start );
    /* MintHCM #122808 START */
    if(GLOBAL_REGISTRY['users_rows'] != undefined){
        GLOBAL_REGISTRY['users_rows'] = [];
    }
    if(GLOBAL_REGISTRY['users_rows_ids'] != undefined){
        GLOBAL_REGISTRY['users_rows_ids'] = {};
    }
    /* MintHCM #122808 END */
   GLOBAL_REGISTRY.scheduler_attendees_obj.init();
   GLOBAL_REGISTRY.scheduler_attendees_obj.display();
}

SugarWidgetScheduler.prototype.display = function () {
   this.parentNode.innerHTML = '';

   var attendees = new SugarWidgetSchedulerAttendees();
   attendees.load( this.parentNode );

   var search = new SugarWidgetSchedulerSearch();
   search.load( this.parentNode );

   // Create div so that popup can be generated below it
   $( 'div#scheduler' ).append( '<div id="SugarWidgetSchedulerPopup"></div>' );



   // Hold off hiding the tool tip overlay if the mouse is over the tool tip
   // hide the tool tip if the mouse is not over the tool tip
   $( 'div#SugarWidgetSchedulerPopup' ).hover(
           function ( e ) {
              SugarWidgetScheduler.isMouseOverToolTip = true;
           },
           function ( e ) {
              SugarWidgetScheduler.isMouseOverToolTip = false;
              SugarWidgetScheduler.popupControl.hide();
           }
   );
}

// TODO: SortStartDate
SugarWidgetScheduler.sortByStartdate = function ( a, b ) {
   //console.log(a);
   //console.log(b);
   var dateA = new Date( $( '<div></div>' ).append( a ).find( 'span[data-field=DATE_START]' ).attr( 'data-date' ) );
   var dateB = new Date( $( '<div></div>' ).append( b ).find( 'span[data-field=DATE_START]' ).attr( 'data-date' ) );
   //console.log(dateA);
   //console.log(dateB);
   if ( dateA < dateB ) {
      //console.log('progress: A < B');
      return -1;
   } else if ( dateA > dateB ) {
      //console.log('progress: A > B');
      return 1;
   }
   //console.log('progress: A == B');
   return 0;
}

// TODO: SortByType
SugarWidgetScheduler.sortByType = function ( a, b ) {
   //console.log(a);
   //console.log(b);
   var valueA = $( '<div></div>' ).append( a ).find( 'input[id=type]' ).attr( 'value' );
   var valueB = $( '<div></div>' ).append( b ).find( 'input[id=type]' ).attr( 'value' );
   //console.log(valueA);
   //console.log(valueB);
   if ( valueA == valueB ) {
      //console.log('progress: A == B');
      return 0;
   } else if ( valueB == 'Meeting' ) {
      //console.log('progress: B = Meetings');
      return 1;
   }
   //console.log('progress: A == B');
   return 0;
}

/**
 * SugarWidgetScheduler.createDialog
 * @param elementId
 * @param body
 * @param caption
 * @param width
 * @param theme
 * @returns {*|jQuery}
 */

SugarWidgetScheduler.createDialog = function ( elementId, body, caption, width, theme ) {
    if (document.activeElement) {
        document.activeElement.blur();
    }
   caption = caption.replace( SUGAR.language.get( 'app_strings', 'LBL_ADDITIONAL_DETAILS' ), '' );

   $( ".ui-dialog" ).find( ".open" ).dialog( "close" );

   var $dialog = $( '<div class="open"></div>' )
           .html( body )
           .dialog( {
              autoOpen: false,
              title: caption,
              width: width,
              height: 250,
              position: {
                 my: 'right top',
                 at: 'left top',
                 of: $( elementId )
              },
              open: function () {
                 var closeBtn = $( '.ui-dialog-titlebar-close' );
                 closeBtn.append( '<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span><span class="ui-button-text">close</span>' );
              }
           } );
   //$(".ui-dialog").find('.ui-dialog-titlebar-close').css("display","none");
   //$(".ui-dialog").find('.ui-dialog-title').css("width","100%");
   // Remove caption buttons
   $( "a[title='Edit']" ).remove();
   $( "a[title='View']" ).remove();

   var width = $dialog.dialog( "option", "width" );
   var pos = $( elementId ).offset( {top: SugarWidgetScheduler.mouseY, left: SugarWidgetScheduler.mouseX} );
   var ofWidth = $( elementId ).width();

   if ( (pos.left + ofWidth) - 40 < width ) {
      $dialog.dialog( "option", "position", {my: 'left top', at: 'right top', of: $( elementId )} );
   }
   //console.log("Dialog: open");
   $dialog.dialog( 'open' );

   $( ".ui-dialog" ).appendTo( "#content" );

   var timeout = function () {
      setTimeout( function () {
         if ( $( $dialog ).is( ":hover" ) ) {
            timeout();
         } else {
            $dialog.dialog( 'close' );
         }
      }, 3000 )
   };

   timeout();

   return $dialog;
}
/*
 * Derived from SUGAR.utils.getAdditionalDetails.
 * Sets the position of the dialog/popup to the mouse offset position.
 */

SugarWidgetScheduler.getScheduleDetails = function ( beans, ids ) {
   var elementId = '#SugarWidgetSchedulerPopup';
   var show_buttons = true;
   //console.log('getScheduleDetails():');
   //console.log(ids);
   //console.log(beans);

   var caption = '';//SUGAR.language.get('app_strings', 'LBL_EMAIL_DETAILS');
   var body = new Array();
   var width = 300;
   var theme = '';
   var $dialog = SugarWidgetScheduler.createDialog(
           elementId,
           body,
           caption,
           width,
           theme
           );
   // MintHCM #59793 Start
   if ( _.contains( beans, 'Reservationss' ) ) {
        viewTools.api.callCustomApi({
            module: 'Reservations', action: 'getReservations', dataPOST: { reservations_ids: ids }, callback: function (data) {
            if ( !_.isEmpty( data ) ) {
               var innerHTML = "";
               var reservationTemplate = _.template( '<div><div><a href="index.php?module=Reservations&action=DetailView&record=<%= id %>"><%= name %></a></div><div><%= starting_date %></div><div><%= employee %></div></div><br />' );
               data.forEach( function ( reservation ) {
                  innerHTML += reservationTemplate( {id: reservation.id, name: reservation.name, starting_date: reservation.starting_date, employee: reservation.employee} );
               } );
               $dialog.html( innerHTML );
            }
            }
        });
   } else if ( _.contains( beans, 'Candidates' ) || _.contains( beans, 'Resources' ) ) {
      var bean_name = $( '#schedulerTable tr[data-id="' + ids[0] + '"][data-module="' + beans[0] + '"] td:first-child' ).text();
      if ( bean_name ) {
         var participantTemplate = _.template( '<div><h2><%= module_name %>:</h2><div><b><%= name_label %>:</b><%= name %></div></div>' );
         $dialog.html( participantTemplate( {
            module_name: viewTools.language.get( 'app_list_strings', 'moduleList' )[beans[0]],
            name_label: viewTools.language.get( 'app_strings', 'LBL_NAME' ),
            name: bean_name
         } ) );
      }
   } else {
      var getScheduleItems = function () {
         var deffereds = [ ];
         $dialog.html( SUGAR.language.get( 'app_strings', 'LBL_LOADING' ) );
         body = '';
         jQuery.each( ids, function ( index, value ) {
            var url = 'index.php?to_pdf=1&module=Home&action=AdditionalDetailsRetrieve&bean=' + beans[index] + '&id=' + ids[index] + '&show_buttons=true';
            //console.log('url: ' +url);
            deffereds.push(
                    $.ajax( url )
                    .done( function () {
                       //console.log("success");
                    } )
                    .fail( function () {
                       //console.log("error");
                    } )
                    .always( function () {
                       //console.log("complete");
                    } )
                    );
         } );
         return deffereds;
      }
      // get requests
      var requests = getScheduleItems();
      $.when.apply( null, requests ).done( function () {
         //console.log('parsing: getSchedule Items');
         var containers = [ ];
         // build results array
         //console.log('arguments:');
         //console.log(arguments);
         // if just one result
         if ( typeof arguments[0] === "string" ) {
            //console.log('found: single result');
            var oldArgs = arguments;
            arguments = new Array();
            arguments[0] = oldArgs;
         }

         $.each( arguments, function ( index, value ) {
            SUGAR.util.evalScript( '<script>' + value[0] + '</script>' );
            var container = result.body;
            containers.push( container );
         } );
         // Sort
         containers.sort( SugarWidgetScheduler.sortByStartdate );
         containers.sort( SugarWidgetScheduler.sortByType );
         $dialog.html( containers );
      } );
   }
   // MintHCM #59793 End
}
//////////////////////////////////////////////////
// class: SugarWidgetSchedulerAttendees
// widget to display the meeting attendees and availability
//
//////////////////////////////////////////////////

SugarClass.inherit( "SugarWidgetSchedulerAttendees", "SugarClass" );

function SugarWidgetSchedulerAttendees() {
   this.init();
}

SugarWidgetSchedulerAttendees.prototype.init = function () {
   var form_name;
   if ( typeof document.EditView != 'undefined' )
      form_name = "EditView";
   else if ( typeof document.CalendarEditView != 'undefined' )
      form_name = "CalendarEditView";
   else
      return;

   // this.datetime = new SugarDateTime();
   GLOBAL_REGISTRY.scheduler_attendees_obj = this;

   var date_start = document.forms[form_name].date_start.value;
   var hour_start = parseInt( date_start.substring( 11, 13 ), 10 );
   var minute_start = parseInt( date_start.substring( 14, 16 ), 10 );
   var has_meridiem = /am|pm/i.test( date_start );
   if ( has_meridiem ) {
      var meridiem = trim( date_start.substring( 16 ) );
   }

   if ( has_meridiem ) {
      GLOBAL_REGISTRY.focus.fields.time_start = hour_start + time_separator + minute_start + meridiem;
   } else {
      GLOBAL_REGISTRY.focus.fields.time_start = hour_start + time_separator + minute_start;
      //GLOBAL_REGISTRY.focus.fields.time_start = document.forms[form_name].time_hour_start.value+time_separator+minute_start;
   }

   GLOBAL_REGISTRY.focus.fields.date_start = document.forms[form_name].date_start.value;
   GLOBAL_REGISTRY.focus.fields.duration_hours = document.forms[form_name].duration_hours.value;
   GLOBAL_REGISTRY.focus.fields.duration_minutes = document.forms[form_name].duration_minutes.value;
   GLOBAL_REGISTRY.focus.fields.datetime_start = SugarDateTime.mysql2jsDateTime( GLOBAL_REGISTRY.focus.fields.date_start, GLOBAL_REGISTRY.focus.fields.time_start );

   this.timeslots = new Array();
   /* MintHCM #75792 START */
   /* this.hours = 9; */
   this.hours = parseInt(document.forms[form_name].duration_hours.value) + (parseInt(document.forms[form_name].duration_minutes.value) > 0 ? 1 : 0);
   if (this.hours > 12) {
       this.hours = 12;
   }
   /* MintHCM #75792 END */
   this.segments = 4;
   /* MintHCM #75792 START */
   /* this.start_hours_before = 4; */
   this.start_hours_before = 2;
   this.hours += this.start_hours_before * 2;
   /* MintHCM #75792 END */

   var minute_interval = 15;
   var dtstart = GLOBAL_REGISTRY.focus.fields.datetime_start;

   // initialize first date in timeslots
   var curdate = new Date( dtstart.getFullYear(), dtstart.getMonth(), dtstart.getDate(), dtstart.getHours() - this.start_hours_before, 0 );

   if ( typeof (GLOBAL_REGISTRY.focus.fields.duration_minutes) == 'undefined' ) {
      GLOBAL_REGISTRY.focus.fields.duration_minutes = 0;
   }
   GLOBAL_REGISTRY.focus.fields.datetime_end = new Date( dtstart.getFullYear(), dtstart.getMonth(), dtstart.getDate(), dtstart.getHours() + parseInt( GLOBAL_REGISTRY.focus.fields.duration_hours ), dtstart.getMinutes() + parseInt( GLOBAL_REGISTRY.focus.fields.duration_minutes ), 0 );

   var has_start = false;
   var has_end = false;

   for ( i = 0; i < this.hours * this.segments; i++ ) {
      var hash = SugarDateTime.getUTCHash( curdate );
      var obj = {"hash": hash, "date_obj": curdate};
      if ( has_start == false && GLOBAL_REGISTRY.focus.fields.datetime_start.getTime() <= curdate.getTime() ) {
         obj.is_start = true;
         has_start = true;
      }
      if ( has_end == false && GLOBAL_REGISTRY.focus.fields.datetime_end.getTime() <= curdate.getTime() ) {
         obj.is_end = true;
         has_end = true;
      }
      this.timeslots.push( obj );
      curdate = new Date( curdate.getFullYear(), curdate.getMonth(), curdate.getDate(), curdate.getHours(), curdate.getMinutes() + minute_interval );
   }
   //Bug#51357: Reset the search input fields after attandee popup is initiated.
   SugarWidgetSchedulerSearch.resetSearchForm();
}

SugarWidgetSchedulerAttendees.prototype.load = function ( parentNode ) {
   this.parentNode = parentNode;
   this.display();
}

SugarWidgetSchedulerAttendees.prototype.display = function () {
   var form_name;
   if ( typeof document.EditView != 'undefined' )
      form_name = "EditView";
   else if ( typeof document.CalendarEditView != 'undefined' )
      form_name = "CalendarEditView";
   else
      return;

   var dtstart = GLOBAL_REGISTRY.focus.fields.datetime_start;
   var top_date = SugarDateTime.getFormattedDate( dtstart );
   var html = '<h3>' + GLOBAL_REGISTRY['meeting_strings']['LBL_SCHEDULING_FORM_TITLE'] + '</h3><table id ="schedulerTable">';
   html += '<tr class="schedulerTopRow">';
   html += '<th colspan="' + ((this.hours * this.segments) + 2) + '"><h4>' + top_date + '</h4></th>';
   html += '</tr>';
   html += '<tr class="schedulerTimeRow">';
   html += '<td>&nbsp;</td>';

   for ( var i = 0; i < (this.timeslots.length / this.segments); i++ ) {
      var hours = this.timeslots[i * this.segments].date_obj.getHours();
      var am_pm = '';

      if ( time_reg_format.indexOf( 'A' ) >= 0 || time_reg_format.indexOf( 'a' ) >= 0 ) {
         am_pm = "AM";

         if ( hours > 12 ) {
            am_pm = "PM";
            hours -= 12;
         }
         if ( hours == 12 ) {
            am_pm = "PM";
         }
         if ( hours == 0 ) {
            hours = 12;
            am_pm = "AM";
         }
         if ( time_reg_format.indexOf( 'a' ) >= 0 ) {
            am_pm = am_pm.toLowerCase();
         }
         if ( hours != 0 && hours != 12 && i != 0 ) {
            am_pm = "";
         }

      }

      var form_hours = hours + time_separator + "00";
      html += '<th scope="col" colspan="' + this.segments + '">' + form_hours + am_pm + '</th>';
   }

   html += '<td>&nbsp;</td>';
   html += '</tr>';

    /* MintHCM #122808 START */
    if ( typeof (GLOBAL_REGISTRY) == 'undefined' ) {
        return;
    }

    GLOBAL_REGISTRY['schedulerTable'] = html;
    /* MintHCM #122808 START */

   html += '</table>';
   if ( this.parentNode.childNodes.length < 1 )
      this.parentNode.innerHTML += '<div class="schedulerDiv">' + html + '</div>';
   else
      this.parentNode.childNodes[0].innerHTML = html;

   var thetable = "schedulerTable";

    /* MintHCM #122808 START */
    // if ( typeof (GLOBAL_REGISTRY) == 'undefined' ) {
    //     return;
    // }
    /* MintHCM #122808 END */

   //set the current user (as event-coordinator) so that they can be added to invitee list
   //only IF the first removed flag has not been set AND this is a new record
   if ( (typeof (GLOBAL_REGISTRY.focus.users_arr) == 'undefined' || GLOBAL_REGISTRY.focus.users_arr.length == 0)
           && document.forms[form_name].record.value == '' && typeof (GLOBAL_REGISTRY.FIRST_REMOVE) == 'undefined' ) {
      GLOBAL_REGISTRY.focus.users_arr = [ GLOBAL_REGISTRY.current_user ];
   }

   if ( typeof GLOBAL_REGISTRY.focus.users_arr_hash == 'undefined' ) {
      GLOBAL_REGISTRY.focus.users_arr_hash = new Object();
   }

   // append attendee rows
   for ( var i = 0; i < GLOBAL_REGISTRY.focus.users_arr.length; i++ ) {
      var row = new SugarWidgetScheduleRow( this.timeslots );
      row.focus_bean = GLOBAL_REGISTRY.focus.users_arr[i];
      row.data_position = i;
      GLOBAL_REGISTRY.focus.users_arr_hash[ GLOBAL_REGISTRY.focus.users_arr[i]['fields']['id']] = GLOBAL_REGISTRY.focus.users_arr[i];
      // MintHCM #59793 Start
      if ( GLOBAL_REGISTRY.focus.users_arr[i].module === 'Resources' ) {
         row.thetableid = thetable;
         if ( typeof (GLOBAL_REGISTRY['freebusy_adjusted']) == 'undefined' || typeof (GLOBAL_REGISTRY['freebusy_adjusted'][row.focus_bean.fields.id]) == 'undefined' ) {
            global_request_registry[req_count] = [ row, 'display' ];
            req_count++;
         }
         viewTools.api.callCustomApi( {
            module: 'Resources',
            action: 'getBusyTimeSlots',
            async: false,
            dataPOST: {
               resource_id: row.focus_bean.fields.id,
               timeslots: row.timeslots
            },
            callback: function ( data ) {
               if ( !_.isEmpty( data ) ) {
                  if ( typeof GLOBAL_REGISTRY.freebusy_adjusted == 'undefined' ) {
                     GLOBAL_REGISTRY.freebusy_adjusted = new Object();
                  }
                  GLOBAL_REGISTRY.freebusy_adjusted[row.focus_bean.fields.id] = data;
               }
            }
         } );
         row.display();
      } else {
         row.load( thetable );
      }
      // MintHCM #59793 End
   }
}
SugarWidgetSchedulerAttendees.form_add_attendee = function (list_row) {

    if (typeof (GLOBAL_REGISTRY.result_list[list_row]) != 'undefined' && typeof (GLOBAL_REGISTRY.focus.users_arr_hash[GLOBAL_REGISTRY.result_list[list_row].fields.id]) == 'undefined' &&
    // MintHCM #43484 Start
    GLOBAL_REGISTRY.result_list[list_row].module !="SecurityGroup"
    // MintHCM #43484 End
    ) {
      GLOBAL_REGISTRY.focus.users_arr[ GLOBAL_REGISTRY.focus.users_arr.length ] = GLOBAL_REGISTRY.result_list[list_row];
   }


    // MintHCM #43484 Start
    if(GLOBAL_REGISTRY.result_list[list_row].module == 'SecurityGroup'){
        SugarWidgetSchedulerAttendees.get_users(list_row);
    }
        // MintHCM #43484 End
   GLOBAL_REGISTRY.scheduler_attendees_obj.display();
}


//////////////////////////////////////////////////
// class: SugarWidgetScheduleRow
// widget to display each row in the scheduler
//
//////////////////////////////////////////////////
SugarClass.inherit( "SugarWidgetScheduleRow", "SugarClass" );

function SugarWidgetScheduleRow( timeslots ) {
   this.init( timeslots );
}

SugarWidgetScheduleRow.prototype.init = function ( timeslots ) {
   this.timeslots = timeslots;
}

SugarWidgetScheduleRow.prototype.load = function ( thetableid ) {
   this.thetableid = thetableid;
   var self = this;
   vcalClient = new SugarVCalClient();
   if ( typeof (GLOBAL_REGISTRY['freebusy_adjusted']) == 'undefined' || typeof (GLOBAL_REGISTRY['freebusy_adjusted'][this.focus_bean.fields.id]) == 'undefined' ) {
      global_request_registry[req_count] = [ this, 'display' ];
      vcalClient.load( this.focus_bean.fields.id, req_count );
      req_count++;
   } else {
      this.display();
   }
}

SugarWidgetScheduleRow.prototype.display = function () {
    /* MintHCM #122808 START */
    if(GLOBAL_REGISTRY['users_rows'] == undefined){
        GLOBAL_REGISTRY['users_rows'] = [];
    }
    if(GLOBAL_REGISTRY['users_rows_ids'] == undefined){
        GLOBAL_REGISTRY['users_rows_ids'] = {};
    }
    /* MintHCM #122808 END */
   SUGAR.util.doWhen( "document.getElementById('" + this.thetableid + "') != null", function () {
      let tr;
      this.thetable = document.getElementById( this.thetableid );

      if ( typeof (this.element) != 'undefined' ) {
         if ( this.element.parentNode != null )
            /* MintHCM #122808 START */
            //this.thetable.deleteRow( this.element.rowIndex );
            /* MintHCM #122808 END */
         tr = document.createElement( 'tr' );
         /* MintHCM #122808 START */
         //this.thetable.appendChild( tr );
         /* MintHCM #122808 END */
      } else {
        /* MintHCM #122808 START */
        //tr = this.thetable.insertRow( this.thetable.rows.length );
         tr = document.createElement( 'tr' );
         /* MintHCM #122808 END */
      }
      if (tr) {
        $(tr).attr('data-position', this.data_position);
        this.thetable.appendChild( tr );
        tr.className = "schedulerAttendeeRow";
        $( tr ).attr( 'data-id', this.focus_bean.fields.id );
        // MintHCM #54195 #59793 Start
        if ( this.focus_bean.module == 'Candidates' || this.focus_bean.module == 'Resources' ) {
          $( tr ).attr( 'data-module', this.focus_bean.module );
        } else {
          $( tr ).attr( 'data-module', this.focus_bean.module + 's' );
        }
        // MintHCM #54195 #59793 End
        td = document.createElement( 'td' );
        tr.appendChild( td );
        //insertCell(tr.cells.length);

        // icon + full name
        td.scope = 'row';
        var img = '<span class="suitepicon suitepicon-module-' + this.focus_bean.module.toLowerCase().replace( '_', '-' ) + '"></span>';

        td.innerHTML = img;

        td.innerHTML = td.innerHTML;

        if ( this.focus_bean.fields.full_name )
          td.innerHTML += ' ' + this.focus_bean.fields.full_name;
        else
          td.innerHTML += ' ' + this.focus_bean.fields.name;
        // add freebusy tds here:
        this.add_freebusy_nodes( tr );

        // delete button
        var td = document.createElement( 'td' );
        tr.appendChild( td );
        //var td = tr.insertCell(tr.cells.length);
        td.className = 'schedulerAttendeeDeleteCell';
        td.noWrap = true;
        //CCL - Remove check to disallow removal of assigned user or current user
        //if ( GLOBAL_REGISTRY.focus.fields.assigned_user_id != this.focus_bean.fields.id && GLOBAL_REGISTRY.current_user.fields.id != this.focus_bean.fields.id) {
        td.innerHTML = '<a title="' + GLOBAL_REGISTRY['meeting_strings']['LBL_REMOVE']
                + '" class="listViewTdToolsS1" style="text-decoration:none;" '
                + 'href="javascript:SugarWidgetScheduleRow.deleteRow(\'' + this.focus_bean.fields.id + '\');">&nbsp;'
                + '<img src="index.php?entryPoint=getImage&themeName=' + SUGAR.themes.theme_name + '&imageName=delete_inline.gif" '
                + 'align="absmiddle" alt="' + GLOBAL_REGISTRY['meeting_strings']['LBL_REMOVE'] + '" border="0"> '
                + GLOBAL_REGISTRY['meeting_strings']['LBL_REMOVE'] + '</a>';
        //}
        this.element = tr;
        this.element_index = this.thetable.rows.length - 1;

        $('#'+this.thetableid).each(function() {
            let collection = Array.from(this.querySelectorAll('.schedulerAttendeeRow'))
            .sort(function(x,y) {
                let posX = +x.dataset.position;
                let posY = +y.dataset.position;
                return posX > posY ? 1 : -1;
            });

            collection.forEach(element => {
                this.querySelector('tbody').append(element);
            })
        });

        /* MintHCM #122808 START */
        if(GLOBAL_REGISTRY['users_rows_ids'] != undefined && !(this.focus_bean.fields.id in GLOBAL_REGISTRY['users_rows_ids'])){
            GLOBAL_REGISTRY['users_rows_ids'][this.focus_bean.fields.id] = tr.outerHTML;
            GLOBAL_REGISTRY['users_rows'].push(tr);
        }
        /* MintHCM #122808 END */
      }
   }, null, this );
}

SugarWidgetScheduleRow.deleteRow = function ( bean_id ) {
   // can't delete organizer
   /*
    if(GLOBAL_REGISTRY.focus.users_arr.length == 1 || GLOBAL_REGISTRY.focus.fields.assigned_user_id == bean_id) {
    return;
    }
    */
   for ( var i = 0; i < GLOBAL_REGISTRY.focus.users_arr.length; i++ ) {
      if ( GLOBAL_REGISTRY.focus.users_arr[i]['fields']['id'] == bean_id ) {
         delete GLOBAL_REGISTRY.focus.users_arr_hash[GLOBAL_REGISTRY.focus.users_arr[i]['fields']['id']];
         GLOBAL_REGISTRY.focus.users_arr.splice( i, 1 );
         //set first remove flag to true for processing in display() function
         GLOBAL_REGISTRY.FIRST_REMOVE = true;
         /* MintHCM #122808 START */
         if(GLOBAL_REGISTRY['users_rows_ids'][bean_id] != undefined){
            if($.inArray(GLOBAL_REGISTRY['users_rows_ids'][bean_id], GLOBAL_REGISTRY['users_rows']) != -1){
                GLOBAL_REGISTRY['users_rows'].splice($.inArray(GLOBAL_REGISTRY['users_rows_ids'][bean_id], GLOBAL_REGISTRY['users_rows']),1);
                delete GLOBAL_REGISTRY['users_rows_ids'][bean_id];
            }
         }
         /* MintHCM #122808 END */
         GLOBAL_REGISTRY.container.root_widget.display();
      }
   }
}


function DL_GetElementLeft( eElement ) {
   /*
    * ifargument is invalid
    * (not specified, is null or is 0)
    * and function is a method
    * identify the element as the method owner
    */
   if ( !eElement && this ) {
      eElement = this;
   }

   /*
    * initialize var to store calculations
    * identify first offset parent element
    * move up through element hierarchy
    * appending left offset of each parent
    * until no more offset parents exist
    */
   var nLeftPos = eElement.offsetLeft;
   var eParElement = eElement.offsetParent;
   while ( eParElement != null ) {
      nLeftPos += eParElement.offsetLeft;
      eParElement = eParElement.offsetParent;
   }
   return nLeftPos; // return the number calculated
}


function DL_GetElementTop( eElement ) {
   if ( !eElement && this ) {
      eElement = this;
   }

   var nTopPos = eElement.offsetTop;
   var eParElement = eElement.offsetParent;
   while ( eParElement != null ) {
      nTopPos += eParElement.offsetTop;
      eParElement = eParElement.offsetParent;
   }
   return nTopPos;
}


//////////////////////////////////////////
// adds the <td>s for freebusy display within a row
//////////////////////////////////////////
SugarWidgetScheduleRow.prototype.add_freebusy_nodes = function ( tr, attendee ) {
   var hours = 9;
   var segments = 4;
   var html = '';
   var is_loaded = false;

   if ( typeof GLOBAL_REGISTRY['freebusy_adjusted'] != 'undefined' && typeof GLOBAL_REGISTRY['freebusy_adjusted'][this.focus_bean.fields.id] != 'undefined' ) {
      is_loaded = true;
   }

   for ( var i = 0; i < this.timeslots.length; i++ ) {

      let td = document.createElement( 'td' );
      tr.appendChild( td );
      //var td = tr.insertCell(tr.cells.length);
      td.innerHTML = '&nbsp;';

      if ( typeof (this.timeslots[i]['is_start']) != 'undefined' ) {
         td.className = 'schedulerSlotCellStartTime';
      }

      if ( typeof (this.timeslots[i]['is_end']) != 'undefined' ) {
         td.className = 'schedulerSlotCellEndTime';
      }

      if ( is_loaded ) {
         // if there's a freebusy stack in this slice
         if ( typeof (GLOBAL_REGISTRY['freebusy_adjusted'][this.focus_bean.fields.id][this.timeslots[i].hash]) != 'undefined' ) {
            /* MintHCM #75792 START */
            /*  $( td ).addClass( 'free' ); */
            /* MintHCM #75792 START */

            var dataid = '',
                    module = '';
            $.each( GLOBAL_REGISTRY['freebusy_adjusted'][this.focus_bean.fields.id][this.timeslots[i].hash]['records'], function ( index, value ) {
               /* MintHCM #75792 START */
               if (value.startsWith('WorkSchedules')) {
                   var ws_type = value.split('___')[1];
                   if (ws_type != undefined) {
                       $(td).addClass('ws-' + ws_type);
                   }
                   return;
               }
               $( td ).addClass( 'free' );
               /* MintHCM #75792 END */
               if ( dataid == '' )
                  dataid = index;
               else
                  dataid += ',' + index;

               if ( module == '' )
                  module = value + 's';
               else
                  module += ',' + value + 's';
            } );

            $( td ).attr( 'data-id', dataid );
            $( td ).attr( 'data-module', module );

            if ( (dataid.split( ',' ).length) > 1 ) {
               $( td ).addClass( 'busy' );
            }
         }
      }
      // Hover Logic
      $( td ).hover(
              function ( e ) {
                 // On hover in
                 var domElement = $( this );
                 // Only add hover logic to the fields that need it.
                 if ( $( domElement ).hasClass( 'free' ) || domElement.hasClass( 'schedulerSlotCellStartTime' ) ) {
                    //
                    // If the id is in the td:
                    if ( domElement.attr( 'data-id' ) != null ) {
                       var id = domElement.attr( 'data-id' ).split( ',' );
                       var module = domElement.attr( 'data-module' ).split( ',' );
                       if ( module == "undefined" || module == null ) {
                          module = 'Meetings';
                       }
                       // Only show popup if user leaves mouse over the td
                       setTimeout( function () {
                          if ( $( domElement ).is( ":hover" ) ) {
                             SugarWidgetScheduler.getScheduleDetails( module, id );
                          }
                       }, SugarWidgetScheduler.popupControlDelayTime );
                    }
                 }
              },
              function ( e ) {
                 // On hover out
              }
      );
   }

   // Add hover logic to tr title
   $( tr ).find( 'td' ).first().hover( function ( e ) {
      var domElement = $( this );
      var module = domElement.closest( 'tr' ).attr( 'data-module' ).split( ',' ),
              id = domElement.closest( 'tr' ).attr( 'data-id' ).split( ',' );
      //
      // if id is stored in the row:
      if ( id != 'undefined' || id != null ) {
         setTimeout( function () {
            if ( $( domElement ).is( ":hover" ) ) {
               SugarWidgetScheduler.getScheduleDetails( module, id );
            }
         }, SugarWidgetScheduler.popupControlDelayTime );
      }
   }, function ( e ) {} );
}

$().ready( function ( e ) {
   $( document ).on( "mousemove", function ( event ) {
      SugarWidgetScheduler.mouseX = event.pageX;
      SugarWidgetScheduler.mouseY = event.pageY;
   } );
} );

