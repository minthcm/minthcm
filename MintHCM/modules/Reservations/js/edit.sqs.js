function modifySQS() {

   var enableQS_original = enableQS.bind( {} );
   enableQS = function ( no_reload ) {
      enableQS_original( no_reload );

      var resources_input_id = 'resource_name';
      var form_name = $( '#' + resources_input_id ).closest( 'form' ).attr( 'id' );
      var sqs_field_name = form_name + '_' + resources_input_id;

      sqs_objects[sqs_field_name].group = 'and';
      sqs_objects[sqs_field_name].conditions.push(
              {
                 name: 'unavailable',
                 op: 'equal',
                 value: '0',
              },
              {
                 name: 'type',
                 op: 'equal',
                 value: 'for_reservation',
              } );
   }
}

modifySQS();