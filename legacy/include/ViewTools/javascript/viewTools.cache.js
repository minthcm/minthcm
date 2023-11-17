/**
 * Warning!
 * This file is generated automatically.
 * Edit only on your own responsibility
*/

window.viewTools.cache.serversideFrontend=["callCustomApi","duplicate","isSuperior","isUnique","isUserAdmin","isUserInRole","related"];
   window.viewTools.formulaParser = {
   validateREGON:{
      formulaName:'validateREGON',
      params:{
         regon:{order:1},
      }
   },
}

window.viewTools.formula.AEM=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = ["formula","errorMessage"];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;

      var ret = arguments['formula'];
      var error_message = '';

      if(ret==false||ret=='false'){
         var alert_msg = viewTools.language.get( window.viewTools.form.getModuleName($( 'form .vt_formulaSelector' ).last()), arguments['errorMessage'] );
         if( alert_msg!='undefined' && alert_msg!='' ){
            error_message = alert_msg;
         }
         else{
            alert_msg = viewTools.language.get( 'app_strings', arguments['errorMessage'] );
            if( alert_msg!='undefined' && alert_msg!='' ){
               error_message = alert_msg;
            }
            else if( arguments['errorMessage']!='undefined' && arguments['errorMessage']!='' ){
               error_message = arguments['errorMessage'];
            }
         }
         if( error_message!='' ){
            window.viewTools.cache.AEM.push(error_message);
         }
      }
      return ret;
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.abs=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = ["param"];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;

      arguments['param'] = unformatNumber( arguments['param'], num_grp_sep, dec_sep );
      if ( arguments['param'] == "" || isNaN( arguments['param'] ) ) {
         return false;
      }
      return formatNumber( Math.abs( arguments['param'] ), num_grp_sep, dec_sep );
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.add=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      var result = 0.0;
      for ( key in arguments ) {
         arguments[key] = unformatNumber( arguments[key], num_grp_sep, dec_sep );
         if ( arguments[key] === "" || isNaN( arguments[key] ) ) {
            return false;
         }
         result += parseFloat( arguments[key] );
      }
      return formatNumber( result, num_grp_sep, dec_sep );
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.addDays=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = ["date","days"];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;

      var tmpDate = moment( arguments['date'], viewTools.date.getDateFormat() );
      tmpDate.add( 'days', arguments['days'] );
      return (tmpDate.format( viewTools.date.getDateFormat() ));
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.and=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      for ( key in arguments ) {
         var andCheckValue = arguments[key];
         if ( andCheckValue !== true && andCheckValue !== 'true' ) {
            return false;
         }
      }
      return true;
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.arrayEquals=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      var first_array = arguments[0];
      var second_array = [];
      for(var i = 1; i<arguments.length; i++){
         second_array.push(arguments[i]);
      }
      var is_diff = false;
      if(Array.isArray(first_array) && Array.isArray(second_array)){
         first_array.forEach(function(a){
            if($.inArray(a, second_array) < 0){
               is_diff = true;
            }
         });
         if(!is_diff){
            second_array.forEach(function(a){
               if($.inArray(a, first_array) < 0){
                  is_diff = true;
               }
            });
         }
      } else {
         is_diff = true;
      }
      return !is_diff;
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.callCustomApi=function(){
   try {      var values = viewTools.form.getFormValues();
      values['eval_formula'] = arguments[0];
      values['formula_name'] = 'callCustomApi';
      if($('#ConvertLead').length > 0){
         var submodule = '';
         var elem = $('[data-validation*="'+arguments[0]+'"], [data-required*="'+arguments[0]+'"], [data-readonly*="'+arguments[0]+'"], [data-calculated*="'+arguments[0]+'"], [data-dependency*="'+arguments[0]+'"]');
         var module_elem = elem.closest('div[id^="create"]');
         if(!module_elem.length) {
            module_elem = elem.parent();
            var elem_id = module_elem.attr('id');
            if(typeof elem_id === 'string' && elem_id.indexOf('select') === 0) {
               submodule = elem_id.replace('select', '');
            }
         } else {
            submodule = module_elem.attr('id').replace('create', '');
         }

         values['convert_lead'] = true;
         values['convert_lead_module'] = submodule;
      }
      var ret = false;
      viewTools.api.callCustomApi({
         module:'Home',
         action:'evalServersideFrontend',
         async: false,
         dataPOST:values,
         callback:function(response){
            if(response!==undefined){
               ret = response;
            }
            else{
               ret = false;
            }
         }
      });
      return ret;
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.charAt=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = ["string","charpos"];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;

      if ( !$.isNumeric( arguments['charpos'] ) ) {
         return '';
      }
      var charAtPos = String( arguments['string'] ).charAt( parseInt( arguments['charpos'] )-1 );
      if ( charAtPos !== undefined ) {
         return charAtPos;
      }
      return '';
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.concat=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      var ret = '';
      for ( key in arguments ) {
      var tmpValue = arguments[key];
         if ( tmpValue !== undefined ) {
            ret = ret + tmpValue;
         }
      }
      return ret;
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.contains=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = ["string","substring"];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;

      if ( arguments['string'].indexOf( arguments['substring'] ) >= 0 ) {
         return true;
      }
      return false;
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.divide=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = ["mixed_1","mixed_2"];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;

      arguments['mixed_1'] = unformatNumber( arguments['mixed_1'], num_grp_sep, dec_sep );
      arguments['mixed_2'] = unformatNumber( arguments['mixed_2'], num_grp_sep, dec_sep );
      if ( arguments['mixed_1'] == "" || isNaN( arguments['mixed_1'] ) || arguments['mixed_2'] == "" || isNaN( arguments['mixed_2'] || arguments['mixed_2'] == 0 ) ) {
         return false;
      }
      return formatNumber( (parseFloat( arguments['mixed_1'] ) / parseFloat( arguments['mixed_2'] )), num_grp_sep, dec_sep );
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.duplicate=function(){
   try {      var values = viewTools.form.getFormValues();
      values['eval_formula'] = arguments[0];
      values['formula_name'] = 'duplicate';
      if($('#ConvertLead').length > 0){
         var submodule = '';
         var elem = $('[data-validation*="'+arguments[0]+'"], [data-required*="'+arguments[0]+'"], [data-readonly*="'+arguments[0]+'"], [data-calculated*="'+arguments[0]+'"], [data-dependency*="'+arguments[0]+'"]');
         var module_elem = elem.closest('div[id^="create"]');
         if(!module_elem.length) {
            module_elem = elem.parent();
            var elem_id = module_elem.attr('id');
            if(typeof elem_id === 'string' && elem_id.indexOf('select') === 0) {
               submodule = elem_id.replace('select', '');
            }
         } else {
            submodule = module_elem.attr('id').replace('create', '');
         }

         values['convert_lead'] = true;
         values['convert_lead_module'] = submodule;
      }
      var ret = false;
      viewTools.api.callCustomApi({
         module:'Home',
         action:'evalServersideFrontend',
         async: false,
         dataPOST:values,
         callback:function(response){
            if(response!==undefined){
               ret = response;
            }
            else{
               ret = false;
            }
         }
      });
      return ret;
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.empty=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = ["mixed"];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;

        return _.isEmpty(arguments['mixed']);
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.equals=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = ["param1","param2"];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;

      if((arguments['param2'] === '' && trim(arguments['param1']).length > 0) || (arguments['param1'] === '' && trim(arguments['param2']).length > 0)){
         return false;
      }
      if ( arguments['param1'] == arguments['param2'] ) {
         return true;
      }
      return false;
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.floor=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = ["value"];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;

      arguments['value'] = unformatNumber( arguments['value'], num_grp_sep, dec_sep );
      if ( arguments['value'] == "" || isNaN( arguments['value'] ) ) {
         return false;
      }
      return formatNumber( Math.floor( arguments['value'] ), num_grp_sep, dec_sep );
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.formatDate=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = ["date_string","date_format"];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;

      var date = viewTools.date.get(arguments['date_string'],arguments['date_format']);
      return date.format(viewTools.date.getDateFormat());
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.formatDateTime=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = ["date_string","date_format"];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;

      var date = viewTools.date.get(arguments['date_string'],arguments['date_format']);
      return date.format(viewTools.date.getDateTimeFormat());
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.getLabelsForEnum=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = ["field","app_list_strings"];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;

      var lang = SUGAR.language.languages['app_list_strings'][arguments['app_list_strings']];
      var return_array = [];
      if(typeof arguments['field'] === "string"){
         return_array.push(lang[arguments['field']]);
      } else {
         for(var i = 0; i < arguments['field'].length; i++){
            return_array.push(lang[arguments['field'][i]]);
         }
      }
      return return_array;
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.getTranslatedLabelsForEnum=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = ["field","app_list_strings","language_key"];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;

      var list = SUGAR.language.languages['app_list_strings'][arguments['app_list_strings']];
      var return_array = [];
      if(typeof arguments['field'] === "string"){
         return_array.push(list[arguments['field']]);
      } else {
         for(var i = 0; i < arguments['field'].length; i++){
            return_array.push(list[arguments['field'][i]]);
         }
      }      
      return return_array;
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.getUploadMaxsize=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = [];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;

        return SUGAR.config.uploadMaxsize;
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.greaterThan=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = ["param1","param2"];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;


      arguments['param1'] = unformatNumber( arguments['param1'], num_grp_sep, dec_sep );
      arguments['param2'] = unformatNumber( arguments['param2'], num_grp_sep, dec_sep );
      if(arguments['param1'] === ""){
         arguments['param1'] = 0;
      }
      if(arguments['param2'] === ""){
         arguments['param2'] = 0;
      }

      if ( arguments['param1'] === "" || isNaN( arguments['param1'] ) || arguments['param2'] === "" || isNaN( arguments['param2'] ) ) {
         return false;
      }
      if ( parseFloat( arguments['param1'] ) > parseFloat( arguments['param2'] ) ) {
         return true;
      }
      return false;
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.ifElse=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = ["condition","if_true","if_false"];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;

      if( arguments['condition'] !== undefined && arguments['if_true'] !== undefined ){
         if ( arguments['condition'] === true || arguments['condition'] === 'true' ) {
            return arguments['if_true'];
         }
         else if ( arguments['if_false'] !== undefined ){
            return arguments['if_false'];
         }
      }
      return false;
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.inArray=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      var compareArgument = '';
      var args = [].slice.call(arguments);
      if(Array.isArray(args[1])){
         $.map( args[1], function( val, i ) {
            args.push(val);
         });
         args.splice(1, 1);
      }
      for ( var i = 0; i <= args.length; i++) {
         if( i == 0 ){
            compareArgument = args[i];
         }
         else{
            if( compareArgument == args[i] ){
               return true;
            }
         }
      }
      return false;
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.isAfter=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = ["date1","date2"];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;

      var date1 = viewTools.date.get(arguments['date1']);
      var date2 = viewTools.date.get(arguments['date2']);
      return moment( date1 ).isAfter( date2 );
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.isNumeric=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = ["value"];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;

      if ( dec_sep == "." ) {
         dec_sep_reg = /\./g;
      } else {
         dec_sep_reg = dec_sep;
      }
      if ( num_grp_sep == "." ) {
         num_grp_sep_reg = /\./g;
      } else {
         num_grp_sep_reg = num_grp_sep;
      }
      var dec_regex = new RegExp( dec_sep_reg, 'g' );
      var num_regex = new RegExp( num_grp_sep_reg, 'g' );
      var value = arguments['value'].toString().trim();
      var replaced_value = value.replace( num_regex, '' ).replace( dec_regex, '.' );
      if ( replaced_value != "" && !isNaN( replaced_value ) ) {
         if ( value.indexOf( dec_sep ) != -1 ) {
            var split_value = value.split( dec_sep );
            if ( split_value[1].indexOf( num_grp_sep ) != -1 ) {
               return false;
            }
         }
         if( dec_sep != "." && num_grp_sep != "." && value.indexOf(".") != -1 ) {
            return false;
         }
         return true;
      }
      return false;
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.isSuperior=function(){
   try {      var values = viewTools.form.getFormValues();
      values['eval_formula'] = arguments[0];
      values['formula_name'] = 'isSuperior';
      if($('#ConvertLead').length > 0){
         var submodule = '';
         var elem = $('[data-validation*="'+arguments[0]+'"], [data-required*="'+arguments[0]+'"], [data-readonly*="'+arguments[0]+'"], [data-calculated*="'+arguments[0]+'"], [data-dependency*="'+arguments[0]+'"]');
         var module_elem = elem.closest('div[id^="create"]');
         if(!module_elem.length) {
            module_elem = elem.parent();
            var elem_id = module_elem.attr('id');
            if(typeof elem_id === 'string' && elem_id.indexOf('select') === 0) {
               submodule = elem_id.replace('select', '');
            }
         } else {
            submodule = module_elem.attr('id').replace('create', '');
         }

         values['convert_lead'] = true;
         values['convert_lead_module'] = submodule;
      }
      var ret = false;
      viewTools.api.callCustomApi({
         module:'Home',
         action:'evalServersideFrontend',
         async: false,
         dataPOST:values,
         callback:function(response){
            if(response!==undefined){
               ret = response;
            }
            else{
               ret = false;
            }
         }
      });
      return ret;
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.isUnique=function(){
   try {      var values = viewTools.form.getFormValues();
      values['eval_formula'] = arguments[0];
      values['formula_name'] = 'isUnique';
      if($('#ConvertLead').length > 0){
         var submodule = '';
         var elem = $('[data-validation*="'+arguments[0]+'"], [data-required*="'+arguments[0]+'"], [data-readonly*="'+arguments[0]+'"], [data-calculated*="'+arguments[0]+'"], [data-dependency*="'+arguments[0]+'"]');
         var module_elem = elem.closest('div[id^="create"]');
         if(!module_elem.length) {
            module_elem = elem.parent();
            var elem_id = module_elem.attr('id');
            if(typeof elem_id === 'string' && elem_id.indexOf('select') === 0) {
               submodule = elem_id.replace('select', '');
            }
         } else {
            submodule = module_elem.attr('id').replace('create', '');
         }

         values['convert_lead'] = true;
         values['convert_lead_module'] = submodule;
      }
      var ret = false;
      viewTools.api.callCustomApi({
         module:'Home',
         action:'evalServersideFrontend',
         async: false,
         dataPOST:values,
         callback:function(response){
            if(response!==undefined){
               ret = response;
            }
            else{
               ret = false;
            }
         }
      });
      return ret;
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.isUserAdmin=function(){
   try {      var values = viewTools.form.getFormValues();
      values['eval_formula'] = arguments[0];
      values['formula_name'] = 'isUserAdmin';
      if($('#ConvertLead').length > 0){
         var submodule = '';
         var elem = $('[data-validation*="'+arguments[0]+'"], [data-required*="'+arguments[0]+'"], [data-readonly*="'+arguments[0]+'"], [data-calculated*="'+arguments[0]+'"], [data-dependency*="'+arguments[0]+'"]');
         var module_elem = elem.closest('div[id^="create"]');
         if(!module_elem.length) {
            module_elem = elem.parent();
            var elem_id = module_elem.attr('id');
            if(typeof elem_id === 'string' && elem_id.indexOf('select') === 0) {
               submodule = elem_id.replace('select', '');
            }
         } else {
            submodule = module_elem.attr('id').replace('create', '');
         }

         values['convert_lead'] = true;
         values['convert_lead_module'] = submodule;
      }
      var ret = false;
      viewTools.api.callCustomApi({
         module:'Home',
         action:'evalServersideFrontend',
         async: false,
         dataPOST:values,
         callback:function(response){
            if(response!==undefined){
               ret = response;
            }
            else{
               ret = false;
            }
         }
      });
      return ret;
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.isUserInRole=function(){
   try {      var values = viewTools.form.getFormValues();
      values['eval_formula'] = arguments[0];
      values['formula_name'] = 'isUserInRole';
      if($('#ConvertLead').length > 0){
         var submodule = '';
         var elem = $('[data-validation*="'+arguments[0]+'"], [data-required*="'+arguments[0]+'"], [data-readonly*="'+arguments[0]+'"], [data-calculated*="'+arguments[0]+'"], [data-dependency*="'+arguments[0]+'"]');
         var module_elem = elem.closest('div[id^="create"]');
         if(!module_elem.length) {
            module_elem = elem.parent();
            var elem_id = module_elem.attr('id');
            if(typeof elem_id === 'string' && elem_id.indexOf('select') === 0) {
               submodule = elem_id.replace('select', '');
            }
         } else {
            submodule = module_elem.attr('id').replace('create', '');
         }

         values['convert_lead'] = true;
         values['convert_lead_module'] = submodule;
      }
      var ret = false;
      viewTools.api.callCustomApi({
         module:'Home',
         action:'evalServersideFrontend',
         async: false,
         dataPOST:values,
         callback:function(response){
            if(response!==undefined){
               ret = response;
            }
            else{
               ret = false;
            }
         }
      });
      return ret;
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.multiply=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      var result = 1.0;
      for ( key in arguments ) {
         arguments[key] = unformatNumber( arguments[key], num_grp_sep, dec_sep );
         if ( arguments[key] === "" || isNaN( arguments[key] ) ) {
            return false;
         }
         result *= parseFloat( arguments[key] );
      }
      return formatNumber( result, num_grp_sep, dec_sep );
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.not=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = ["value"];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;

      if ( arguments['value'] !== true && arguments['value'] !== 'true' ) {
         return true;
      }
      return false;
      
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.or=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      for ( key in arguments ) {
         var andCheckValue = viewTools.formula.valueOf(arguments[key]);
         if ( andCheckValue === true || andCheckValue === 'true' ) {
            return true;
         }
      }
      return false;
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.related=function(){
   try {      var values = viewTools.form.getFormValues();
      values['eval_formula'] = arguments[0];
      values['formula_name'] = 'related';
      if($('#ConvertLead').length > 0){
         var submodule = '';
         var elem = $('[data-validation*="'+arguments[0]+'"], [data-required*="'+arguments[0]+'"], [data-readonly*="'+arguments[0]+'"], [data-calculated*="'+arguments[0]+'"], [data-dependency*="'+arguments[0]+'"]');
         var module_elem = elem.closest('div[id^="create"]');
         if(!module_elem.length) {
            module_elem = elem.parent();
            var elem_id = module_elem.attr('id');
            if(typeof elem_id === 'string' && elem_id.indexOf('select') === 0) {
               submodule = elem_id.replace('select', '');
            }
         } else {
            submodule = module_elem.attr('id').replace('create', '');
         }

         values['convert_lead'] = true;
         values['convert_lead_module'] = submodule;
      }
      var ret = false;
      viewTools.api.callCustomApi({
         module:'Home',
         action:'evalServersideFrontend',
         async: false,
         dataPOST:values,
         callback:function(response){
            if(response!==undefined){
               ret = response;
            }
            else{
               ret = false;
            }
         }
      });
      return ret;
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.strToLower=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = ["string"];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;

      return arguments['string'].toLowerCase();
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.strToUpper=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = ["string"];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;

      return arguments['string'].toUpperCase();
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.strlen=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = ["string"];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;

      if ( typeof arguments['string'] !== 'string' ) {
         return '';
      }
      var result = arguments['string'].length;
      if ( typeof result !== 'undefined' ) {
         return result;
      }
      return '';
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.subStr=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = ["string","from","len"];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;

      arguments['from'] = unformatNumber( arguments['from'], num_grp_sep, dec_sep );
      arguments['len'] = unformatNumber( arguments['len'], num_grp_sep, dec_sep );
      if ( arguments['from'] == "" || isNaN( arguments['from'] ) || arguments['len'] == "" || isNaN( arguments['len'] ) ) {
         return '';
      }
      return arguments['string'].substr( (arguments['from'] - 1), arguments['len'] );
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.validateNIP=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = ["nip"];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;

      var nip = arguments['nip'];
      nip = nip.replace(/\D/g,'');
      if (nip.length == 10 ){
         var nip_array = nip.split("");
         var weight_array = ['6', '5', '7', '2', '3', '4', '5', '6', '7'];
         var checksum = 0;
         for(i=0;i<9;i++) {
            checksum += nip_array[i] * weight_array[i];
         }
         checksum = checksum % 11;
         checksum = checksum % 10;
         return (checksum == nip_array[9]);
      }
      return false;
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.validatePESEL=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = ["pesel"];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;

      var pesel = arguments['pesel'];
      pesel = pesel.replace(/\D/g,'');
      if (pesel.length == 11 ){
         var pesel_array = pesel.split("");
         var weight_array = ['1', '3', '7', '9', '1', '3', '7', '9', '1', '3'];
         var checksum = 0;
         for(i=0;i<10;i++) {
            checksum += pesel_array[i] * weight_array[i];
         }
         checksum = checksum % 10;
         checksum = (checksum == 0 ? 0: 10 - checksum);
         return (checksum == pesel_array[10]);
      }
      return false;
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
}
window.viewTools.formula.validateREGON=function(){
   try {//Get arguments values
      for ( key in arguments ) {
         arguments[key] = viewTools.formula.valueOf( arguments[key] );
      }
      //set argument names
      var tmpArgumentsKeys = ["regon"];
      var tmpArguments = [ ];
      var x = 0;
      for ( key in tmpArgumentsKeys ) {
         if ( arguments[x] !== undefined ) {
            tmpArguments[tmpArgumentsKeys[key]] = (typeof arguments[x] == "string")?$.trim(arguments[x]):arguments[x];
         }
         x++;
      }
      arguments = tmpArguments;

      var regon = arguments['regon'];
      regon = regon.replace(/\D/g,'');
      var length = regon.length;
      if (length == 9 || length == 14 ){
         var regon_array = regon.split("");
         if (length == 9) {
            var weight_array = ['8', '9', '2', '3', '4', '5', '6', '7'];
         }  
         else {
            var weight_array = ['2', '4', '8', '5', '0', '9', '7', '3', '6', '1', '2', '4', '8'];
         }
         var checksum = 0;
         for(i=0;i<length-1;i++) {
            checksum += regon_array[i] * weight_array[i];
         }
         checksum = checksum % 11;
         checksum = checksum % 10;
         return (length == 9 ? checksum == regon_array[8] : checksum == regon_array[13]);
      }
      return false;
   } catch ( e ) {
      viewTools.console.log( e );
      return false;
   }
};
window.viewTools.cache.initMappings = {"appraisalitems":{"appraisal_name":{"name":"name"},"parent_name":{"name":"name"}},"appraisals":{"type":{"employee_name":"employee_name","candidature_name":"candidature_name"}},"candidatures":{"status":{"reason_for_rejection":"reason_for_rejection","work_start":"work_start","training_date":"training_date"}},"careerpaths":{"position_to_name":{"name":"name"}},"costs":{"type":{"assigned_user_id":"assigned_user_id","accommodation_no":"accommodation_no","type_of_meal":"type_of_meal","delegation_name":"delegation_name","delegation_id":"delegation_id","transportation_name":"transportation_name","transportation_id":"transportation_id"},"id":{"assigned_user_id":"assigned_user_id"},"delegation_id":{"delegation_id":"delegation_id"},"transportation_id":{"transportation_id":"transportation_id"}},"delegations":{"id":{"currency_id":"currency_id"},"currency_id":{"exchange_rate":"exchange_rate"}},"employeecertificates":{"certificate_name":{"name":"name"},"employee_id":{"name":"name"},"candidate_name":{"name":"name"},"employee_name":{"name":"name"}},"news":{"news_type":{"publication_date":"publication_date"}},"onboardingoffboardingelements":{"kind_of_element":{"user_name":"user_name","securitygroup_unit_name":"securitygroup_unit_name"}},"recruitments":{"recruitment_type":{"vacancy":"vacancy"}},"requests":{"type":{"training_name":"training_name","benefit_name":"benefit_name"}},"termsofemployment":{"id":{"term_starting_date":"term_starting_date","term_ending_date":"term_ending_date","contract_name":"contract_name"}},"transportations":{"id":{"assigned_user_id":"assigned_user_id"},"type":{"other_transportation":"other_transportation"}},"usersnews":{"news_name":{"name":"name"},"assigned_user_name":{"name":"name"}},"workschedules":{"type":{"supervisor_acceptance":"supervisor_acceptance","delegation_duration":"delegation_duration","comments":"comments","occasional_leave_type":"occasional_leave_type","delegation_name":"delegation_name","workplace_name":"workplace_name","deputy_name":"deputy_name"}}};
window.viewTools.cache.formulaRequirements = {"accounts_bugs":{"deleted":false},"accounts_cases":{"deleted":false},"accounts_contacts":{"deleted":false},"accounts_opportunities":{"deleted":false},"calls_contacts":{"deleted":false},"calls_users":{"deleted":false},"calls_leads":{"deleted":false},"cases_bugs":{"deleted":false},"contacts_bugs":{"deleted":false},"contacts_cases":{"deleted":false},"contacts_users":{"deleted":false},"email_addresses":{"id":true,"email_address":true,"email_address_caps":true},"emailaddresses":{"id":true,"email_address":true,"email_address_caps":true},"emails_email_addr_rel":{"id":true,"email_id":true,"address_type":true,"email_address_id":true},"email_addr_bean_rel":{"id":true,"email_address_id":true,"bean_id":true,"bean_module":true},"emails_beans":{"deleted":false},"emails_text":{"email_id":true},"folders":{"id":true,"name":true,"parent_folder":false,"assign_to_id":false,"created_by":true,"modified_by":true},"folders_subscriptions":{"id":true,"folder_id":true,"assigned_user_id":true},"folders_rel":{"id":true,"folder_id":true,"polymorphic_module":true,"polymorphic_id":true},"meetings_contacts":{"deleted":false},"meetings_users":{"deleted":false},"meetings_leads":{"deleted":false},"opportunities_contacts":{"deleted":false},"users_password_link":{"id":true,"keyhash":true,"deleted":false},"allocations_employees":{"deleted":true},"rooms_resources":{"deleted":true},"outbound_email":{"id":true,"name":true,"type":true,"user_id":true,"mail_sendtype":true,"mail_smtptype":true,"mail_smtpserver":false},"address_book":{"assigned_user_id":true,"bean":true,"bean_id":true},"calls_resources":{"deleted":false},"meetings_resources":{"deleted":false},"users_feeds":{"crmrank":false,"deleted":false},"workschedules_spenttime":{"deleted":true},"projects_bugs":{"deleted":false},"projects_cases":{"deleted":false},"projects_products":{"deleted":false},"projects_accounts":{"deleted":false},"projects_contacts":{"deleted":false},"projects_opportunities":{"deleted":false},"acl_roles_actions":{"access_override":false},"inbound_email_autoreply":{"id":true,"deleted":false,"date_entered":true,"date_modified":true,"autoreplied_to":true,"ie_id":true},"inbound_email_cache_ts":{"id":true,"ie_timestamp":true},"email_cache":{"mbox":true,"subject":false,"fromaddr":false,"toaddr":false,"senddate":false,"message_id":false,"mailsize":true,"imap_uid":true,"msgno":false,"recent":true,"flagged":true,"answered":true,"deleted":false,"seen":true,"draft":true},"users_signatures":{"id":true,"date_entered":true,"date_modified":true,"deleted":false,"name":false},"linked_documents":{"deleted":false},"documents_accounts":{"deleted":true},"documents_contacts":{"deleted":true},"documents_opportunities":{"deleted":true},"documents_cases":{"deleted":true},"documents_bugs":{"deleted":true},"oauth_nonce":{"conskey":true,"nonce":true,"nonce_ts":true},"aok_knowledgebase_categories":{"deleted":true},"am_projecttemplates_project_1_c":{"deleted":true},"am_projecttemplates_contacts_1_c":{"deleted":true},"am_projecttemplates_users_1_c":{"deleted":true},"am_tasktemplates_am_projecttemplates_c":{"deleted":true},"aos_contracts_documents":{"deleted":true},"aos_quotes_os_contracts_c":{"deleted":true},"aos_quotes_aos_invoices_c":{"deleted":true},"aos_quotes_project_c":{"deleted":true},"aow_processed_aow_actions":{"deleted":false},"fp_event_locations_fp_events_1_c":{"deleted":true},"fp_events_contacts_c":{"deleted":true},"fp_events_fp_event_delegates_1_c":{"deleted":true},"fp_events_fp_event_locations_1_c":{"deleted":true},"fp_events_leads_1_c":{"deleted":true},"fp_events_prospects_1_c":{"deleted":true},"jjwg_maps_jjwg_areas_c":{"deleted":true},"jjwg_maps_jjwg_markers_c":{"deleted":true},"project_contacts_1_c":{"deleted":true},"project_users_1_c":{"deleted":true},"securitygroups_acl_roles":{"id":true,"deleted":true},"securitygroups_default":{"id":true,"deleted":true},"securitygroups_records":{"id":true,"deleted":true},"securitygroups_users":{"deleted":true},"surveyquestionoptions_surveyquestionresponses":{"deleted":true},"appraisals_documents":{"deleted":true},"appraisals_meetings":{"deleted":true},"appraisals_roles":{"deleted":true},"benefits_employees":{"deleted":true},"benefits_positions":{"deleted":true},"benefits_roles":{"deleted":true},"calls_candidates":{"deleted":false},"candidates_employees":{"deleted":true},"conclusions_improvements":{"deleted":true},"conclusions_problems":{"deleted":true},"exitinterviews_documents":{"deleted":true},"exitinterviews_meetings":{"deleted":true},"meetings_candidates":{"deleted":false},"onboardingoffboardingelements_offboardingtemplates":{"deleted":true},"onboardingoffboardingelements_onboardingtemplates":{"deleted":true},"securitygroups_positions_leader":{"deleted":true},"securitygroups_positions_membership":{"deleted":true},"positions_documents":{"deleted":true},"responsibilities_activities":{"deleted":true},"responsibilities_positions":{"deleted":true},"responsibilities_roles":{"deleted":true},"roles_employees":{"deleted":true},"certificates_trainings":{"deleted":true},"trainings_documents":{"deleted":true},"trainings_meetings":{"deleted":true},"users_schedulereports":{"deleted":true},"documents_candidates":{"deleted":true},"documents_candidatures":{"deleted":true},"documents_certificates":{"deleted":true},"documents_contracts":{"deleted":true},"documents_delegations":{"deleted":true},"documents_termsofemployment":{"deleted":true},"knowledge_competencies":{"deleted":true},"skills_competencies":{"deleted":true},"attitudes_competencies":{"deleted":true},"appraisals_tokens":{"deleted":true,"status":true},"users":{"id":true,"user_name":true,"system_generated_password":true,"pwd_last_changed":false,"last_name":true,"date_entered":true,"date_modified":true,"status":true,"deleted":false,"reports_to_id":false,"email1":true,"email_addresses":true,"email_addresses_primary":true,"business_role":false},"userpreferences":{"id":true,"deleted":false,"date_entered":true,"date_modified":true,"assigned_user_id":true},"administration":{"id":true},"employees":{"id":true,"user_name":true,"system_generated_password":true,"pwd_last_changed":false,"last_name":true,"date_entered":true,"date_modified":true,"status":false,"deleted":false,"reports_to_id":false,"email1":false,"email_addresses":false,"email_addresses_primary":false,"business_role":false,"position_name":true},"candidates":{"id":true,"last_name":true,"birthdate":true,"potential":false,"last_time_contact":false,"date_planned_contact":false},"aclactions":{"id":true,"date_entered":true,"date_modified":true,"modified_user_id":false},"aclroles":{"id":true,"date_entered":true,"date_modified":true,"modified_user_id":false},"am_projecttemplates":{"id":true,"name":true,"description":false,"assigned_user_name":false,"status":false,"priority":false,"override_business_hours":false},"am_tasktemplates":{"id":true,"name":true,"status":false,"priority":false,"percent_complete":false,"predecessors":false,"milestone_flag":false,"relationship_type":false,"task_number":false,"order_number":false,"estimated_effort":false,"utilization":false,"duration":true},"aobh_businesshours":{"id":true,"name":true,"opening_hours":false,"closing_hours":false,"open_status":false,"day":false},"aod_index":{"id":true,"name":true,"last_optimised":false,"location":false},"aod_indexevent":{"id":true,"name":true,"error":false,"success":false,"record_module":false},"aok_knowledgebase":{"id":true,"name":true,"description":false,"status":false,"revision":false,"additional_info":false,"user_id_c":false,"author":true,"user_id1_c":false,"approver":false},"aok_knowledge_base_categories":{"id":true,"name":true},"aor_charts":{"id":true,"name":true,"type":false,"x_field":false,"y_field":false},"aor_conditions":{"id":true,"name":true,"aor_report_id":false,"condition_order":false,"field":false,"operator":false,"value_type":false,"value":false,"parameter":false},"aor_fields":{"id":true,"name":true,"aor_report_id":false,"field_order":false,"field":false,"display":false,"link":false,"label":false,"field_function":false,"sort_by":false,"format":false,"total":false,"sort_order":false,"group_by":false,"group_order":false,"group_display":false},"aor_reports":{"id":true,"name":true,"report_module":true,"graphs_per_row":true,"field_lines":false,"condition_lines":false},"aor_scheduled_reports":{"id":true,"name":true,"schedule":true,"last_run":false,"status":false,"email_recipients":false,"aor_report_name":true},"aos_pdf_templates":{"id":true,"name":true,"description":false,"type":true,"sample":false,"insert_fields":false,"pdfheader":false,"pdffooter":false,"margin_left":false,"margin_right":false,"margin_top":false,"margin_bottom":false,"margin_header":false,"margin_footer":false,"page_size":false,"orientation":false},"aow_actions":{"id":true,"name":true,"aow_workflow_id":false,"action_order":false,"action":false},"aow_conditions":{"id":true,"name":true,"aow_workflow_id":false,"condition_order":false,"field":false,"operator":false,"value_type":false,"value":false},"aow_processed":{"id":true,"name":true,"aow_workflow_id":false,"aow_workflow":false,"parent_id":false,"parent_type":false,"status":false},"aow_workflow":{"id":true,"name":true,"flow_module":true,"flow_run_on":true,"status":false,"run_when":false,"condition_lines":false,"action_lines":false},"upgrade_history":{"id":true,"date_entered":true},"alerts":{"id":true,"name":true,"reminder_id":false,"alert_type":false},"allocations":{"id":true,"name":true,"assigned_user_name":true,"mode":false,"date_from":true,"date_to":false,"workplace_name":true},"applications":{"id":true,"name":true,"status":true,"type":true},"appraisalitems":{"id":true,"name":true,"value":false,"parent_name":true,"parent_type":true,"parent_id":true,"appraisal_name":true,"competency_name":true,"knowledge_name":true,"skill_name":true,"attitude_name":true,"responsibiliti_name":true,"goal_name":true,"responsibilityactivitie_name":true},"appraisals":{"id":true,"name":true,"date":false,"status":true,"type":true,"evaluator_name":true,"appraisal_items_inline":false},"attitudes":{"id":true,"name":true},"benefits":{"id":true,"name":true},"calls":{"id":true,"name":true,"duration_hours":true,"date_start":true,"parent_type":false,"status":true,"reminders":false,"reschedule_history":false,"reschedule_count":false},"calls_reschedule":{"id":true,"name":true,"reason":false,"call_id":false,"call_name":false},"campaignlog":{"id":true},"campaigntrackers":{"id":true,"tracker_key":true,"campaign_id":false,"date_entered":true,"date_modified":true,"is_optout":true,"deleted":false},"campaigns":{"id":true,"name":true,"tracker_key":true,"end_date":true,"status":true,"currency_id":false,"campaign_type":true},"candidatures":{"id":true,"name":false,"scoring":false,"source":true,"status":false,"task_grade":false,"final_employment_form":false,"employment_form":false,"parent_name":true,"recruitment_name":true,"recruitment_end_name":false},"careerpaths":{"id":true,"name":true,"position_from_name":true,"position_to_name":true},"certificates":{"id":true,"name":true},"comments":{"id":true,"name":true,"parent_type":false},"competencies":{"id":true,"name":true},"competencyratings":{"id":true,"name":true,"rating":true,"competency_name":true,"parent_name":true},"conclusions":{"id":true,"name":true},"contracts":{"id":true,"name":true,"employee_name":true,"date_of_signing":false,"contract_starting_date":false,"contract_ending_date":false,"status":false,"contract_type":true,"daily_working_time":false},"costs":{"id":true,"name":true,"assigned_user_id":true,"type":true,"cost_amount":false,"cost_amount_usdollars":false,"currency_id":false,"cost_date":false,"cost_city":true,"accommodation_no":false,"type_of_meal":true},"currencies":{"id":true,"name":true,"symbol":true,"conversion_rate":true,"deleted":false,"date_entered":true,"date_modified":true,"created_by":true},"dashboardbackups":{"id":true,"name":true,"encoded_pages":false,"encoded_dashlets":false},"dashboardhistory":{"id":true,"name":true},"dashboardmanager":{"id":true,"name":true,"encoded_pages":false,"encoded_dashlets":false,"business_role":false},"delegations":{"id":true,"name":false,"assigned_user_name":true,"delegation_id":false,"purpose":true,"start_date":true,"end_date":true,"transport_cost":false,"transport_cost_usdollar":false,"transport_cost_eur":false,"currency_id":false,"regiments":false,"regiments_usdollar":false,"regiments_eur":false,"restaurant_bills":false,"accomodations":false,"total_accommodation":false,"total_accommodation_usdollar":false,"total_accommodation_eur":false,"accommodation_lump_sum":false,"accommodation_lump_sum_usdollar":false,"accommodation_lump_sum_eur":false,"other":false,"other_usdollar":false,"other_eur":false,"total_expenses":false,"total_expenses_usdollar":false,"total_expenses_eur":false,"obtained_sum":false,"obtained_sum_usdollar":false,"obtained_sum_eur":false,"payoff_sum":false,"payoff_sum_usdollar":false,"payoff_sum_eur":false,"return_sum":false,"return_sum_usdollar":false,"return_sum_usd":false,"regimen_value":false,"regimen_value_usdollar":false,"accommodation_value":false,"accommodation_value_usdollar":false,"obtained_sum_usdollars":false,"transport_cost_usd":false,"regiments_usd":false,"total_accommodation_usd":false,"accommodation_lump_sum_usd":false,"other_usd":false,"total_expenses_usd":false,"obtained_sum_usd":false,"payoff_sum_usd":false,"restaurant_bills_eur":false,"restaurant_bills_usd":false,"owner":false,"exchange_rate":true,"assured_number_of_breakfasts":true,"assured_number_of_dinners":true,"assured_number_of_suppers":true,"assured_number_of_accommodations":true,"costs_sum":true},"delegationslocale":{"id":true,"name":true,"regimen_value":true,"currency_id":false,"accommodation_value":true,"archival":false},"dictionaries":{"id":true,"name":true,"list_type":true},"documentrevisions":{"id":true,"document_id":false,"filename":true},"documents":{"id":true,"document_name":true,"filename":true,"active_date":true,"revision":true},"editcustomfields":{"len":false},"eapm":{"id":true,"password":true,"url":true,"application":true,"oauth_token":false,"oauth_secret":false,"validated":false,"note":false},"emailmarketing":{"id":true,"deleted":false,"date_entered":true,"date_modified":true,"name":true,"from_name":true,"from_addr":true,"date_start":true,"template_id":true,"status":true,"campaign_id":false,"outbound_email_id":false},"emailtemplates":{"id":true,"date_entered":true,"date_modified":true,"name":true,"deleted":false,"text_only":false,"type":false},"emailtext":{"email_id":true},"emails":{"id":true,"name":false,"orphaned":false,"last_synced":false,"flagged":false,"reply_to_status":false},"employeecertificates":{"id":true,"name":true,"employee_name":false,"status":false,"candidate_name":false,"certificate_name":true},"employeeroles":{"id":true,"name":true,"status":false},"exitinterviews":{"id":true,"name":true,"date_start":false,"date_end":false,"status":false,"offboarding_name":false},"externaloauthconnection":{"id":true,"name":true,"client_id":false,"client_secret":false,"token_type":false,"expires_in":false,"access_token":false,"refresh_token":false},"externaloauthprovider":{"id":true,"name":true,"connector":true,"redirect_uri":false,"client_id":true,"client_secret":false,"scope":false,"url_authorize":false,"authorize_url_options":false,"url_access_token":false,"extra_provider_params":false,"get_token_request_grant":false,"get_token_request_options":false,"refresh_token_request_grant":false,"refresh_token_request_options":false,"access_token_mapping":false,"expires_in_mapping":false,"refresh_token_mapping":false,"token_type_mapping":false},"fp_event_locations":{"id":true,"name":true,"address":true,"address_city":true,"address_country":false,"address_postalcode":true,"address_state":false,"capacity":false},"fp_events":{"id":true,"name":true,"duration_hours":true,"date_start":true,"budget":false,"currency_id":false,"invite_templates":false,"decline_redirect":false},"favorites":{"id":true,"name":true,"parent_id":false,"parent_type":false},"files":{"id":true,"document_name":true,"filename":true,"active_date":true},"goals":{"id":true,"name":true,"date_start":false,"date_end":false,"status":false},"ideas":{"id":true,"name":true,"description":true,"assigned_user_name":true,"status":true,"explanation":false,"user_name":true},"import_1":{"id":true,"name":true,"source":true,"enclosure":true,"delimiter":true,"module":true,"has_header":true,"deleted":false,"date_entered":true,"date_modified":true,"is_published":true},"import_2":{"id":true,"deleted":false},"improvements":{"id":true,"name":true},"inboundemail":{"id":true,"deleted":false,"date_entered":true,"date_modified":true,"name":true,"status":true,"server_url":true,"connection_string":false,"email_user":true,"email_password":false,"port":true,"service":true,"mailbox":true,"is_personal":true,"groupfolder_id":false,"is_ssl":false,"is_default":false,"is_auto_import":false,"is_create_case":false,"allow_outbound_group_usage":false,"email_provider":true},"kreports":{"id":true,"name":true},"ktemplates":{"id":true,"name":true,"template":false,"fields":false,"relatedmodule":true,"preview":false},"knowledge":{"id":true,"name":true},"meetings":{"id":true,"name":true,"duration_hours":true,"date_start":true,"reminders":false,"type":false,"repeat_pane":false,"jjwg_maps_lat_c":false,"jjwg_maps_lng_c":false,"jjwg_maps_address_c":false,"jjwg_maps_geocode_status_c":false},"news":{"id":true,"name":true,"content_of_announcement":false,"display_date":false,"news_type":true,"news_status":false,"publication_date":true},"nonworkingdays":{"id":true,"name":true,"date":true,"week_day":true},"notes":{"id":true,"name":true,"parent_id":false,"portal_flag":true,"deleted":false},"oauth2clients":{"id":true,"name":true,"secret":true,"redirect_url":false,"allowed_grant_type":true,"duration_value":true,"duration_amount":true,"duration_unit":true,"assigned_user_name":true},"oauth2tokens":{"id":true,"name":true,"token_is_revoked":true,"token_type":true,"access_token_expires":true,"access_token":true,"refresh_token":false,"refresh_token_expires":false,"grant_type":true,"state":false,"oauth2client_name":false},"oauthkeys":{"id":true,"name":true,"c_key":true,"c_secret":true},"oauthtokens":{"id":true,"secret":true,"tstate":true,"consumer":true,"token_ts":true,"deleted":true,"callback_url":false},"offboardingtemplates":{"id":true,"name":true},"offboardings":{"id":true,"name":true,"status":false,"date_start":false,"offboardingtemplate_name":false},"onboardingoffboardingelements":{"id":true,"name":true,"kind_of_element":true,"type":true,"task_duration_hours":true,"days_from_start":true,"user_name":true,"securitygroup_unit_name":true},"onboardingtemplates":{"id":true,"name":true},"onboardings":{"id":true,"name":true,"status":false,"date_start":false,"onboardingtemplate_name":false},"outboundemailaccounts":{"id":true,"name":true,"type":true,"user_id":false,"mail_sendtype":true,"mail_smtptype":true,"mail_smtpserver":false,"mail_smtppass":false,"password_change":false,"sent_test_email_btn":false},"pdftemplates":{"id":true,"name":true,"template":false,"fields":false,"relatedmodule":true,"type":true,"orientation":true,"preview":false},"periodsofemployment":{"id":true,"name":true},"positions":{"id":true,"name":true,"status":false},"problems":{"id":true,"name":true},"project":{"id":true,"assigned_user_id":false,"name":true,"description":false,"deleted":false,"estimated_start_date":true,"estimated_end_date":true,"override_business_hours":false,"jjwg_maps_lat_c":false,"jjwg_maps_lng_c":false,"jjwg_maps_address_c":false,"jjwg_maps_geocode_status_c":false},"projecttask":{"id":true,"project_id":true,"project_task_id":false,"name":true,"status":false,"relationship_type":false,"description":false,"predecessors":false,"duration":true,"actual_duration":false,"percent_complete":false,"parent_task_id":false,"assigned_user_id":false,"milestone_flag":false,"order_number":false,"task_number":false,"estimated_effort":false,"actual_effort":false,"deleted":false,"utilization":false},"prospectlists":{"id":true,"deleted":false},"reactions":{"id":true,"name":true,"reaction_type":true,"parent_name":true},"recruitments":{"id":true,"name":false,"project_status":false,"start_date":true,"position_name":true,"recruitment_channels":false,"recruitment_type":true,"salary_from":true,"salary_from_usdollar":false,"salary_to":true,"salary_to_usdollar":false},"relationships":{"id":true,"relationship_name":true,"lhs_module":true,"lhs_table":true,"lhs_key":true,"rhs_module":true,"rhs_table":true,"rhs_key":true},"reminders":{"id":true,"name":true,"popup":false,"email":false,"email_sent":false,"timer_popup":true,"timer_email":true,"related_event_module":true,"related_event_module_id":true,"date_willexecute":false},"reminders_invitees":{"id":true,"name":true,"reminder_id":true,"related_invitee_module":true,"related_invitee_module_id":true},"requests":{"id":true,"name":true,"status":false,"type":false},"reservations":{"id":true,"name":true,"employee_name":true,"starting_date":true,"ending_date":true,"resource_name":true,"parent_name":false},"resources":{"id":true,"name":true,"type":true,"unavailable":false},"responsibilities":{"id":true,"name":true},"responsibilityactivities":{"id":true,"name":true},"roles":{"id":true,"date_entered":true,"date_modified":true,"modified_user_id":false},"rooms":{"id":true,"name":true,"assigned_user_name":true,"number_of_seats":false,"room_surface":false,"room_plan":false,"reservation_type":true,"availability":true,"security_group_name":true},"salaryranges":{"id":true,"name":true,"start_date":true,"gross_value_from":true,"gross_value_to":true,"currency_id":false,"net_value_from":true,"net_value_to":true,"employer_costs_from":true,"employer_costs_to":true,"position_name":true},"savedsearch":{"id":true,"deleted":true,"date_entered":true,"date_modified":true},"schedulereports":{"id":true,"name":true,"frequency_performance":false,"active":false,"template_id":true,"email_template_id":true,"date_send":true,"kreport_name":true,"kreport_id":true},"schedulereportslogs":{"id":true,"name":true,"status":false,"execute_data":false},"schedulers":{"id":true,"deleted":false,"date_entered":true,"date_modified":true,"name":true,"job":true,"job_url":false,"job_function":false,"date_time_start":true,"job_interval":true,"adv_interval":false,"time_from":false,"time_to":false,"last_run":false,"status":false,"catch_up":false},"schedulersjobs":{"id":true,"name":true,"deleted":true,"date_entered":true,"date_modified":true,"scheduler_id":false,"execute_time":true,"status":true,"resolution":true,"message":false,"target":true,"data":false,"requeue":false,"retry_count":false,"failure_count":false,"job_delay":false,"client":true,"percent_complete":false},"securitygroups":{"id":true,"name":true,"group_type":false,"parent_id":false},"skills":{"id":true,"name":true},"spenttime":{"id":true,"name":true,"description":true,"date_start":true,"date_end":true,"work_date":true,"spent_time":true,"done_ratio":true,"remaining_hours":true,"current_done_ratio":false,"current_remaining_hours":false,"estimated_task_time":false,"worked_task_time":false,"workschedule_name":true},"sugarfeed":{"id":true},"surveyquestionoptions":{"id":true,"name":true,"sort_order":false},"surveyquestionresponses":{"id":true,"name":true},"surveyquestions":{"id":true,"name":true,"sort_order":false,"type":false,"happiness_question":false},"surveyresponses":{"id":true,"name":true},"surveys":{"id":true,"name":true,"status":false,"submit_text":false,"satisfied_text":false,"neither_text":false,"dissatisfied_text":false},"tasks":{"id":true,"name":true,"status":true,"parent_type":false,"priority":true},"templatesectionline":{"id":true,"name":true,"thumbnail":false,"grp":false,"ord":false},"termsofemployment":{"id":true,"name":true,"term_starting_date":true,"term_ending_date":false,"date_of_signing":false,"gross":false,"gross_usdollar":false,"currency_id":false,"net":false,"net_usdollar":false,"employer_cost":false,"employer_cost_usdollar":false,"contract_name":true,"position_name":true},"trackers":{"monitor_id":true},"trainings":{"id":true,"name":true,"date_start":false,"date_end":false,"status":false,"training_type":false},"transportations":{"id":true,"name":true,"assigned_user_id":true,"from_city":true,"to_city":true,"type":true,"cost_total":false,"eur_total":false,"pln_total":false,"usd_total":false,"trans_date":false},"usersnews":{"id":true,"name":true,"news_read":false,"not_display":false,"news_name":true},"workschedules":{"id":true,"name":true,"repeat_pane":false,"lp":false,"type":false,"status":false,"supervisor_acceptance":false,"schedule_date":false,"date_start":true,"date_end":true,"duration_minutes":false,"duration_hours":true,"spent_time":false,"spent_time_settlement":false,"delegation_duration":true,"comments":false,"time_tracking_pane":false,"occasional_leave_type":false},"workingmonths":{"id":true,"name":true,"year":true,"months":true,"working_days":true,"working_hours":true},"workplaces":{"id":true,"name":true,"assigned_user_name":true,"mode":true,"availability":true,"room_name":true},"vcals":{"id":true,"deleted":false,"user_id":true},"leads":{"Notesid":true,"Notesname":true,"Notesparent_id":false,"Notesportal_flag":true,"Notesdeleted":false,"Callsid":true,"Callsname":true,"Callsduration_hours":true,"Callsdate_start":true,"Callsparent_type":false,"Callsstatus":true,"Callsreminders":false,"Callsreschedule_history":false,"Callsreschedule_count":false,"Meetingsid":true,"Meetingsname":true,"Meetingsduration_hours":true,"Meetingsdate_start":true,"Meetingsreminders":false,"Meetingstype":false,"Meetingsrepeat_pane":false,"Meetingsjjwg_maps_lat_c":false,"Meetingsjjwg_maps_lng_c":false,"Meetingsjjwg_maps_address_c":false,"Meetingsjjwg_maps_geocode_status_c":false,"Tasksid":true,"Tasksname":true,"Tasksstatus":true,"Tasksparent_type":false,"Taskspriority":true}};
window.viewTools.cache.formulaDuplicateFields = [];