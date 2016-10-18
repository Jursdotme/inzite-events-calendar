(function( $ ) {

    $( '#inz_event_start_date' ).datepicker({
        dateFormat: 'dd-mm-yy',
        onClose: function( selectedDate ){
            $( '#inz_event_end_date' ).datepicker( 'option', 'minDate', selectedDate );
        }
    });
    $( '#inz_event_end_date' ).datepicker({
        dateFormat: 'dd-mm-yy',
        onClose: function( selectedDate ){
            $( '#inz_event_start_date' ).datepicker( 'option', 'maxDate', selectedDate );
        }
    });

    $( 'table.inz-event-calendar td.date a').each( function() {
      $(this).click(function(e) {
        e.preventDefault();
        var date = $(this).data('date');
        console.log('Vis: ' + date);
        inz_show_events(date);
      });
    });

})( jQuery );
