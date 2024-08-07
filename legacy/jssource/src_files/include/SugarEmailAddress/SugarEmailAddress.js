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

(function () {
   //Do not double define
   if ( SUGAR.EmailAddressWidget )
      return;

   SUGAR.EmailAddressWidget = function ( module ) {
      if ( !SUGAR.EmailAddressWidget.count[module] )
         SUGAR.EmailAddressWidget.count[module] = 0;
      this.count = SUGAR.EmailAddressWidget.count[module];
      SUGAR.EmailAddressWidget.count[module]++;
      this.module = module;
      this.id = this.count;
      if ( document.getElementById( module + '_email_widget_id' ) ) {
         document.getElementById( module + '_email_widget_id' ).value = this.id;
      }
      SUGAR.EmailAddressWidget.instances[this.id] = this;
   }

   SUGAR.EmailAddressWidget.instances = {};
   SUGAR.EmailAddressWidget.count = {};

   SUGAR.EmailAddressWidget.prototype = {
      totalEmailAddresses: 0,
      replyToFlagObject: new Object(),
      verifying: false,
      enterPressed: false,
      tabPressed: false,
      emailView: "",
      emailIsRequired: false,
      tabIndex: -1,

      isIE: function () {
         var ua = window.navigator.userAgent;
         var msie = ua.indexOf( "MSIE " );

         if ( msie > 0 || !!navigator.userAgent.match( /Trident.*rv\:11\./ ) )  // If Internet Explorer, return version number
         {
            return true;
         }

         return false;
      }, //isIE

      prefillEmailAddresses: function ( tableId, o ) {
         for ( i = 0; i < o.length; i++ ) {
            o[i].email_address = o[i].email_address.replace( '&#039;', "'" );
            this.addEmailAddress(
                    tableId,
                    o[i].email_address,
                    o[i].primary_address,
                    o[i].reply_to_address,
                    o[i].opt_out,
                    o[i].invalid_email,
                    o[i].email_address_id,
                    o[i].confirm_opt_in
                    );
         }
      }, //prefillEmailAddresses

      retrieveEmailAddress: function ( event ) {
         var callbackFunction = function success( data ) {
            var vals = jQuery.parseJSON( data.responseText );
            var target = vals.target;
            event = this.getEvent( event );

            if ( vals.email ) {
               var email = vals.email;
               if ( email != '' && /\d+$/.test( target ) ) {
                  var matches = target.match( /\d+$/ );
                  var targetNumber = matches[0];
                  var optOutEl = $( '#' + this.module + this.id + 'emailAddressOptOutFlag' + targetNumber );
                  if ( optOutEl ) {
                     optOutEl.checked = email['opt_out'] == 1 ? true : false;
                  }
                  var invalidEl = $( '#' + this.module + this.id + 'emailAddressInvalidFlag' + targetNumber );
                  if ( invalidEl ) {
                     invalidEl.checked = email['invalid_email'] == 1 ? true : false;
                  }
               }
            }
            //Set the verified flag to true
            var index = /[a-z]*\d?emailAddress(\d+)/i.exec( target )[1];

            var verifyElementFlag = $( '#' + this.module + this.id + 'emailAddressVerifiedFlag' + index );

            if ( verifyElementFlag.parentNode.childNodes.length > 1 ) {
               verifyElementFlag.parentNode.removeChild( verifyElementFlag.parentNode.lastChild );
            }

            var verifiedTextNode = document.createElement( 'span' );
            verifiedTextNode.innerHTML = '';
            verifyElementFlag.parentNode.appendChild( verifiedTextNode );
            verifyElementFlag.value = "true";
            this.verifyElementValue = $( '#' + this.module + this.id + 'emailAddressVerifiedValue' + index );
            this.verifyElementValue.value = $( '#' + this.module + this.id + 'emailAddress' + index ).value;
            this.verifying = false;

            // If Enter key or Save button was pressed then we proceed to attempt a form submission
            var savePressed = false;
            if ( event ) {
               var elm = document.activeElement || event.explicitOriginalTarget;
               if ( typeof elm.type != 'undefined' && /submit|button/.test( elm.type.toLowerCase() ) ) {
                  //if we are in here, then the element has been recognized as a button or submit type, so check the id
                  //to make sure it is related to a submit button that should lead to a form submit

                  //note that the document.activeElement and explicitOriginalTarget calls do not work consistantly across
                  // all browsers, so we have to include this check after we are sure that the calls returned something as opposed to in the coindition above.
                  // Also, since this function is called on blur of the email widget, we can't rely on a third object as a flag (a var or hidden form input)
                  // since this function will fire off before the click event from a button is executed, which means the 3rd object will not get updated prior to this function running.
                  if ( /save|full|cancel|change/.test( elm.value.toLowerCase() ) ) {
                     //this is coming from either a save, full form, cancel, or view change log button, we should set savePressed = true;
                     savePressed = true;
                  }
               }
            }


            if ( savePressed || this.enterPressed ) {
               setTimeout( "SUGAR.EmailAddressWidget.instances." + this.module + this.id + ".forceSubmit()", 2100 );
            } else if ( this.tabPressed ) {
               $( '#' + this.module + this.id + 'emailAddressPrimaryFlag' ).focus();
            }
         }

         var event = this.getEvent( event );
         var targetEl = this.getEventElement( event );
         var index = /[a-z]*\d?emailAddress(\d+)/i.exec( targetEl.id )[1];
         var verifyElementFlag = $( '#' + this.module + this.id + 'emailAddressVerifiedFlag' + index );

         if ( this.verifyElementValue == null || typeof (this.verifyElementValue) == 'undefined' ) {
            //we can't do anything without this value, so just return
            return false;
         }

         this.verifyElementValue = $( '#' + this.module + this.id + 'emailAddressVerifiedValue' + index );
         verifyElementFlag.value = (trim( targetEl.value ) == '' || targetEl.value == this.verifyElementValue.value) ? "true" : "false"

         //Remove the span element if it is present
         if ( verifyElementFlag.parentNode.childNodes.length > 1 ) {
            verifyElementFlag.parentNode.removeChild( verifyElementFlag.parentNode.lastChild );
         }

         if ( /emailAddress\d+$/.test( targetEl.id ) && isValidEmail( targetEl.value ) && !this.verifying && verifyElementFlag.value == "false" ) {
            verifiedTextNode = document.createElement( 'span' );
            verifyElementFlag.parentNode.appendChild( verifiedTextNode );
            verifiedTextNode.innerHTML = SUGAR.language.get( 'app_strings', 'LBL_VERIFY_EMAIL_ADDRESS' );
            this.verifying = true;
            var cObj = jQuery.get( 'index.php?module=Contacts&action=RetrieveEmail&target=' + targetEl.id + '&email=' + targetEl.value )
                    .done( callbackFunction )
                    .fail( callbackFunction );
         }
      }, //retrieveEmailAddress

      handleKeyDown: function ( event ) {
         var e = this.getEvent( event );
         var eL = this.getEventElement( e );
         if ( (kc = e["keyCode"]) ) {
            this.enterPressed = (kc == 13) ? true : false;
            this.tabPressed = (kc == 9) ? true : false;

            if ( this.enterPressed || this.tabPressed ) {
               this.retrieveEmailAddress( e );
               if ( this.enterPressed )
                  this.freezeEvent( e );
            }
         }
      }, //handleKeyDown()

      getEvent: function ( event ) {
         return (event ? event : window.event);
      }, //getEvent

      getEventElement: function ( e ) {
         return (e.srcElement ? e.srcElement : (e.target ? e.target : e.currentTarget));
      }, //getEventElement

      freezeEvent: function ( e ) {
         if ( e.preventDefault )
            e.preventDefault();
         e.returnValue = false;
         e.cancelBubble = true;
         if ( e.stopPropagation )
            e.stopPropagation();
         return false;
      }, //freezeEvent

      addEmailAddress: function ( tableId, address, primaryFlag, replyToFlag, optOutFlag, invalidFlag, emailId, optInFlag ) {
         _eaw = this;
//viewTools start
         var primary_radio_button = $( '.template.email-address-line-container' ).parent().find( 'input[name$="PrimaryFlag"]:checked' );
//viewTools end
         if ( _eaw.addInProgress ) {
            return;
         }

         _eaw.addInProgress = true;

         if ( !address ) {
            address = "";
         }

         // Clone from hidden template on the page
         var lineContainer = $( '.template.email-address-line-container' ).clone();
         lineContainer.removeClass( 'template' );
         lineContainer.removeClass( 'hidden' );
         lineContainer.attr( 'id', this.module + _eaw.id + 'emailAddressRow' + _eaw.totalEmailAddresses );
         lineContainer.attr( 'name', this.module + _eaw.id + 'emailAddressRow' + _eaw.totalEmailAddresses );
         // Add line item to lines container
         $( lineContainer ).appendTo( '.email-address-lines-container' );

         // Set up line item
         // use the value if the tabindex value for email has been passed in from metadata (defined in include/EditView/EditView.tpl
         // else default to 0
         var tabIndexCount = 0;
         if ( typeof (SUGAR.TabFields) != 'undefined' && typeof (SUGAR.TabFields['email1']) != 'undefined' ) {
            tabIndexCount = SUGAR.TabFields['email1'];
         }


         // Email Field
         var emailField = lineContainer.find( 'input[type=email]' );
         emailField.attr( 'name', this.module + _eaw.id + 'emailAddress' + _eaw.totalEmailAddresses );
         emailField.attr( 'id', this.module + _eaw.id + 'emailAddress' + _eaw.totalEmailAddresses );
         emailField.attr( 'tabindex', tabIndexCount );
         emailField.attr( 'enabled', "true" );
         emailField.attr( 'value', address );
         emailField.eaw = _eaw;
         emailField.on( 'blur', function ( e ) {
            emailField.eaw.retrieveEmailAddress( e );
         } );
         emailField.on( 'keydown', function ( e ) {
            emailField.eaw.handleKeyDown( e );
         } );

         // Remove button
         var removeButton = lineContainer.find( 'button#email-address-remove-button' );
         removeButton.attr( 'name', _eaw.totalEmailAddresses );
         removeButton.attr( 'id', this.module + _eaw.id + "removeButton" + _eaw.totalEmailAddresses );
         removeButton.attr( 'tabindex', tabIndexCount );
         removeButton.attr( 'enabled', "true" );
         removeButton.attr( 'data-row', this.module + _eaw.id + 'emailAddressRow' + _eaw.totalEmailAddresses );
         removeButton.attr( 'module-id', _eaw.id );
         removeButton.attr( 'module-email-id', _eaw.totalEmailAddresses );
         removeButton.attr( 'module', this.module );
         removeButton.click( _eaw.removeEmailAddress );

         if(_eaw.totalEmailAddresses < 1 && $("input[name='merge_module']").length > 0){
             viewTools.form.hideRemoveButtonsEmail();
         }else{
             viewTools.form.showRemoveButtonsEmail();
         }
 
         // Record id
         var recordId = lineContainer.find( 'input#record-id' );
         recordId.attr( 'name', this.module + _eaw.id + "emailAddressId" + _eaw.totalEmailAddresses );
         recordId.attr( 'id', this.module + _eaw.id + 'emailAddressId' + _eaw.totalEmailAddresses );
         recordId.attr( 'value', typeof (emailId) != 'undefined' ? emailId : '' );
         recordId.attr( 'enabled', "true" );

         // Fix #9271 - Keeping record of primary email, after adding secondary
         var primaryPreviousValue = $("input[name='"+ _eaw.module + "0emailAddressPrimaryFlag']:checked").val();
 

         // Primary checkbox
         var primaryCheckbox = lineContainer.find( 'input#email-address-primary-flag' );
         primaryCheckbox.attr( 'name', _eaw.module + '0emailAddressPrimaryFlag' );
         primaryCheckbox.attr( 'id', this.module + _eaw.id + 'emailAddressPrimaryFlag' );
         primaryCheckbox.attr( 'value', this.module + _eaw.id + 'emailAddress' );
         primaryCheckbox.attr( 'tabindex', tabIndexCount );
         primaryCheckbox.attr( 'enabled', "true" );
         primaryCheckbox.attr( "checked", (primaryFlag == '1') );

         if ( _eaw.totalEmailAddresses == 0 && primaryFlag != '1' ) {
            primaryCheckbox.prop( "checked", true );
         }
         //viewTools start
         else {
            primary_radio_button.prop( "checked", true );
         }//viewTools end


         // Prevent users from removing their primary email address
         if ( this.module == 'Users' && primaryCheckbox.attr( "checked" ) ) {
            removeButton.prop( 'disabled', true );
         }
 
         // Fix #9271 - Keeping record of primary email, after adding secondary
         if (!primaryFlag && primaryPreviousValue) {
            $('input[value="'+primaryPreviousValue+'"].email-address-primary-flag').prop("checked", true);
         }
         
         // Reply to checkbox
         var replyToCheckbox = lineContainer.find( 'input#email-address-reply-to-flag' );
         if ( replyToCheckbox.length == 1 ) {
            replyToCheckbox.attr( 'name', this.module + _eaw.id + 'emailAddressReplyToFlag' );
            replyToCheckbox.attr( 'id', this.module + _eaw.id + 'emailAddressReplyToFlag' + _eaw.totalEmailAddresses );
            replyToCheckbox.attr( 'value', this.module + _eaw.id + 'emailAddress' + _eaw.totalEmailAddresses );
            replyToCheckbox.attr( 'tabindex', tabIndexCount );
            replyToCheckbox.attr( 'enabled', "true" );
            replyToCheckbox.eaw = _eaw;
            replyToCheckbox.prop( "checked", (replyToFlag == '1') );
            _eaw.replyToFlagObject[replyToCheckbox.attr( 'id' )] = (replyToFlag == '1');
         }


         // Opt Out checkbox
         var optOutCheckbox = lineContainer.find( 'input#email-address-opt-out-flag' );
         if ( optOutCheckbox.length == 1 ) {
            optOutCheckbox.attr( 'name', this.module + _eaw.id + 'emailAddressOptOutFlag[]' );
            optOutCheckbox.attr( 'id', this.module + _eaw.id + 'emailAddressOptOutFlag' + _eaw.totalEmailAddresses );
            optOutCheckbox.attr( 'value', this.module + _eaw.id + 'emailAddress' + _eaw.totalEmailAddresses );
            optOutCheckbox.attr( 'tabindex', tabIndexCount );
            optOutCheckbox.attr( 'enabled', "true" );
            optOutCheckbox.eaw = _eaw;
            optOutCheckbox.prop( "checked", (optOutFlag == '1') );
         }


         // Invalid checkbox
         var invalidCheckbox = lineContainer.find( 'input#email-address-invalid-flag' );
         if ( invalidCheckbox.length == 1 ) {
            invalidCheckbox.attr( 'name', this.module + _eaw.id + 'emailAddressInvalidFlag[]' );
            invalidCheckbox.attr( 'id', this.module + _eaw.id + 'emailAddressInvalidFlag' + _eaw.totalEmailAddresses );
            invalidCheckbox.attr( 'value', this.module + _eaw.id + 'emailAddress' + _eaw.totalEmailAddresses );
            invalidCheckbox.attr( 'tabindex', tabIndexCount );
            invalidCheckbox.attr( 'enabled', "true" );
            invalidCheckbox.eaw = _eaw;
            invalidCheckbox.prop( "checked", (invalidFlag == '1') );
         }

         // OptIn checkbox
         var optInCheckbox = lineContainer.find( 'input#email-address-opted-in-flag' );
         if ( optInCheckbox.length == 1 ) {
            optInCheckbox.attr( 'name', this.module + _eaw.id + 'emailAddressOptInFlag[]' );
            optInCheckbox.attr( 'id', this.module + _eaw.id + 'emailAddressOptInFlag' + _eaw.totalEmailAddresses );
            optInCheckbox.attr( 'value', this.module + _eaw.id + 'emailAddress' + _eaw.totalEmailAddresses );
            optInCheckbox.attr( 'tabindex', tabIndexCount );
            optInCheckbox.attr( 'enabled', "true" );
            optInCheckbox.eaw = _eaw;
            optInCheckbox.prop( "checked", (optInFlag == 'opt-in' || optInFlag == 'confirmed-opt-in') );
         }

         // Verified flag
         var verifiedField = lineContainer.find( 'input#verified-flag' );
         verifiedField.attr( 'name', this.module + _eaw.id + 'emailAddressVerifiedFlag' );
         verifiedField.attr( 'id', this.module + _eaw.id + 'emailAddressVerifiedFlag' + _eaw.totalEmailAddresses );
         verifiedField.attr( 'value', 'true' );


         //  Verified email value
         var verifiedEmailValueField = lineContainer.find( 'input#verified-email-value' );
         verifiedEmailValueField.attr( 'name', this.module + _eaw.id + 'emailAddressVerifiedEmailValue' );
         verifiedEmailValueField
                 .attr( 'id', this.module + _eaw.id + 'emailAddressVerifiedEmailValue' + _eaw.totalEmailAddresses );
         verifiedEmailValueField.attr( 'value', 'true' );

         // Change id of these elements to avoid duplicate ids
         lineContainer.find( 'input#Users_email_widget_id' )
                 .attr( 'id', 'Users_email_widget_id' + _eaw.totalEmailAddresses );
         lineContainer.find( 'input#emailAddressWidget' )
                 .attr( 'id', 'emailAddressWidget' + _eaw.totalEmailAddresses );

         // Add validation to field
         _eaw.EmailAddressValidation(
                 _eaw.emailView,
                 this.module + _eaw.id + 'emailAddress' + _eaw.totalEmailAddresses,
                 _eaw.emailIsRequired,
                 SUGAR.language.get( 'app_strings', 'LBL_EMAIL_ADDRESS_BOOK_EMAIL_ADDR' )
                 );
         _eaw.totalEmailAddresses += 1;
         _eaw.numberEmailAddresses = _eaw.totalEmailAddresses;
         _eaw.addInProgress = false;

         _eaw.fixPrimaryRadioCheckboxValue();


      }, //addEmailAddress

      EmailAddressValidation: function ( ev, fn, r, stR ) {
         $( document ).ready( function () {
            addToValidate( ev, fn, 'email', r, stR );
         } );
      }, //EmailAddressValidation

      removeEmailAddress: function () {
         var module = $( this ).attr( 'module' );
         var id = $( this ).attr( 'module-id' );
         var email_id = $( this ).attr( 'module-email-id' );
         var rowId = $( this ).attr( 'data-row' );
         var index = this.name;

         removeFromValidate( $( this ).parents( 'form' ).attr( 'name' ), module + id + 'emailAddress' + email_id );
         $( '#' + rowId ).remove();

         var form = $( this ).closest( "form" );
         var removedIndex = parseInt( index );
         // If we are not deleting the last email address, we need to shift the numbering to fill the gap

         _eaw.totalEmailAddresses = $( '.email-address-line-container:not(.template)' ).length;

         if(_eaw.totalEmailAddresses < 2 && $("input[name='merge_module']").length > 0) {
             viewTools.form.hideRemoveButtonsEmail();
         } else {
             viewTools.form.showRemoveButtonsEmail();
         }

         //var primaryFound = ($('[name='+ module + '0emailAddressPrimaryFlag]:checked').length != 0);
         if ( $( '[name=' + module + id + 'emailAddressPrimaryFlag]:checked' ).length == 0 ) {
            //$('.email-address-remove-button').bind( "click", _eaw.removeEmailAddress);
            $( '[name=' + module + id + 'emailAddressPrimaryFlag]' ).first().prop( 'checked', true );
            var emailId = $( '[name=' + module + id + 'emailAddressPrimaryFlag]' ).first().closest( '.email-address-line-container' ).find( '.email-address-remove-button' ).attr( 'module-email-id' );
            $( '[name=' + module + id + 'emailAddressPrimaryFlag]:checked' ).val( module + id + 'emailAddress' + emailId );

         }


         _eaw.fixPrimaryRadioCheckboxValue();

         var elemName = '';
         var counter = 0;
         $( '.email-address-line-container' ).each( function ( index, value ) {
            // skip template
            if ( !$( value ).hasClass( 'template' ) ) {

               // email input
               $( value ).find( 'input[type=email]' ).first().prop( 'name', module + id + "emailAddress" + counter );
               $( value ).find( 'input[type=email]' ).first().prop( 'id', module + id + "emailAddress" + counter );

               // primary flag
               elemName = '';
               if ( $( value ).find( 'input.email-address-primary-flag' ).first().prop( 'checked' ) == true ) {
                  elemName = module + id + "emailAddressPrimaryFlag";
               }
               $( value ).find( 'input.email-address-primary-flag' ).first().prop( 'name', elemName );
               $( value ).find( 'input.email-address-primary-flag' ).first().prop( 'id', module + id + "emailAddressPrimaryFlag" + counter );
               $( value ).find( 'input.email-address-primary-flag' ).first().prop( 'value', module + id + 'emailAddress' + counter );

               // invalid
               $( value ).find( 'input.email-address-invalid-flag' ).first().prop( 'name', module + id + "emailAddressInvalidFlag[]" );
               $( value ).find( 'input.email-address-invalid-flag' ).first().prop( 'id', module + id + "emailAddressInvalidFlag" + counter );
               $( value ).find( 'input.email-address-invalid-flag' ).first().prop( 'value', module + id + 'emailAddress' + counter );

               // opt-out flag
               $( value ).find( 'input.email-address-opt-out-flag' ).first().prop( 'name', module + id + "emailAddressOptOutFlag[]" );
               $( value ).find( 'input.email-address-opt-out-flag' ).first().prop( 'id', module + id + "emailAddressOptOutFlag" + counter );
               $( value ).find( 'input.email-address-opt-out-flag' ).first().prop( 'value', module + id + 'emailAddress' + counter );

               // opt-in flag
               $( value ).find( 'input.email-address-opted-in-flag' ).first().prop( 'name', module + id + "emailAddressOptInFlag[]" );
               $( value ).find( 'input.email-address-opted-in-flag' ).first().prop( 'id', module + id + "emailAddressOptInFlag" + counter );
               $( value ).find( 'input.email-address-opted-in-flag' ).first().prop( 'value', module + id + 'emailAddress' + counter );

               // remove button
               $( value ).find( '.email-address-remove-button' ).first().prop( 'name', counter );
               $( value ).find( '.email-address-remove-button' ).first().prop( 'data-row', module + id + "emailAddressRow" + counter );

               $( value ).find( '.verified-flag' ).prop( 'id', module + id + "emailAddressVerifiedFlag" + counter );
               $( value ).find( '.verified-flag' ).prop( 'name', module + id + "emailAddressVerifiedFlag" + counter );
               //$(value).find('.verified-flag').prop('value', 'true');

               $( value ).find( '.verified-email-value' ).prop( 'id', module + id + "emailAddressVerifiedValue" + counter );
               $( value ).find( '.verified-email-value' ).prop( 'name', module + id + "emailAddressVerifiedValue" + counter );
               //$(value).find('.verified-email-value').prop('value', $(value).find('input[type=email]').first().prop('value'));

               counter++;
            }

         } );


         return false;
      }, //removeEmailAddress

      //private
      fixPrimaryRadioCheckboxValue: function () {
         $( '.email-address-line-container' ).find( 'input[type="email"]' ).each( function () {
            if ( !$( this ).hasClass( 'template' ) ) {
               var thisValueId = $( this ).attr( 'name' );
               $( this ).closest( '.email-address-line-container' ).find( '.email-address-primary-flag' ).val( thisValueId );
            }
         } );
         // bind click on primary radio checkbox
         $( '.email-address-lines-container .email-address-line-container:not(.template) input[type="radio"].email-address-primary-flag' ).each( function ( i, e ) {
            if ( typeof $._data( $( e ), 'events' ) == 'undefined' ) {
               $( e ).click( function () {
                  $( '.email-address-lines-container .email-address-line-container:not(.template) input[type="radio"].email-address-primary-flag' ).prop( 'checked', false );
                  $( this ).prop( 'checked', true );
               } );
            }
         } );

      },

      forceSubmit: function () {
         var theForm = $( '#' + this.emailView );
         if ( theForm ) {
            theForm.action.value = 'Save';
            if ( !check_form( this.emailView ) ) {
               return false;
            }
            if ( this.emailView == 'EditView' ) {
               //this is coming from regular edit view form
               theForm.submit();
            } else if ( this.emailView.indexOf( 'DCQuickCreate' ) > 0 ) {
               //this is coming from the DC Quick Create Tool Bar, so call save on form
               DCMenu.save( theForm.id );
            } else if ( this.emailView.indexOf( 'QuickCreate' ) >= 0 ) {
               //this is a subpanel create or edit form
               SUGAR.subpanelUtils.inlineSave( theForm.id, theForm.module.value + '_subpanel_save_button' );
            }
         }
      } //forceSubmit
   };
   emailAddressWidgetLoaded = true;
})();
$( document ).ready( function () {
   $( '.email-address-primary-flag[checked="checked"]' ).click();
} );