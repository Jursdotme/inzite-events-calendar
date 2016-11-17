(function( $ ) {

    $( '#inzite_event_start_date' ).datepicker({
        dateFormat: 'dd-mm-yy',
        onClose: function( selectedDate ){
            $( '#inzite_event_end_date' ).datepicker( 'option', 'minDate', selectedDate );
        }
    });
    $( '#inzite_event_end_date' ).datepicker({
        dateFormat: 'dd-mm-yy',
        onClose: function( selectedDate ){
            $( '#inzite_event_start_date' ).datepicker( 'option', 'maxDate', selectedDate );
        }
    });

    $( 'table.inzite-event-calendar td.date a').each( function() {
      $(this).click(function(e) {
        e.preventDefault();
        var date = $(this).data('date');
        console.log('Vis: ' + date);
        inzite_show_events(date);
      });
    });

})( jQuery );
