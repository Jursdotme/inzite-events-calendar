(function( $ ) {

    check_calender_links();

})( jQuery );

function check_events_today() {
  $( 'table.inzite-event-calendar td.active a').each( function() {
      var date = $(this).data('date');
      jQuery('.inzite_event_info').html( '<img src="' + inzite_events.img + 'ajax-loader.gif">');
      inzite_show_events(date);
  });
}

function check_calender_links() {
  check_events_today();
  $( 'table.inzite-event-calendar td.date a').each( function() {
    $(this).click(function(e) {
      e.preventDefault();
      $( 'table.inzite-event-calendar td.date' ).removeClass('active');
      var date = $(this).data('date');
      $(this).parent('.date').addClass('active');
      jQuery('.inzite_event_info').html( '<img src="' + inzite_events.img + 'ajax-loader.gif">');
      inzite_show_events(date);
    });
  });
}

function inzite_show_events(date) {
  days = new Array();
  days['Mon'] = 'Man'; days['Tue'] = 'Tir'; days['Wed'] = 'Ons'; days['Thu'] = 'Tor'; days['Fri'] = 'Fre'; days['Sat'] = 'Lør'; days['Sun'] = 'Søn';

  months = new Array();
  months['January'] = 'Januar'; months['February'] = 'Februar'; months['March'] = 'Marts'; months['April'] = 'April'; months['May'] = 'Maj'; months['June'] = 'Juni';
  months['July'] = 'Juli'; months['August'] = 'August'; months['September'] = 'September'; months['October'] = 'Oktober'; months['November'] = 'November'; months['December'] = 'December';

  jQuery.ajax({
      type: "POST",
      url: inzite_events.ajaxurl,
      data: {
        action: 'inzite_show_events', date: date
      },
      success: function (response) {

        var obj = JSON.parse(response);
        jQuery('.inzite_event_info').fadeOut(50);

        response = "";
        for(var k in obj) {
           var post = obj[k];
           response += '<div class="event">';
            response += '<div class="event_date">';
              response += '<div class="date_text">' + days[ post['date_text'] ] + '</div>';
              response += '<div class="date_number">' + post['date_number'] + '</div>';
            response += '</div>';
            response += '<div class="event_text">';
              response += '<div class="text_title">' + post['post_title'] + '</div>';
              response += '<div class="text_date">' + post['date_number'] + '. ' + months[ post['date_month'] ];
              if (post['start_time'] != null && post['start_time'] != "") {
                response += ' (Kl. ' + post['start_time'];
                if (post['end_time'] != null && post['end_time'] != "") {
                  response += ' - ' + post['end_time'];
                }
                response += ')';
              }
              response += '</div>';
              response += '<div class="text_content">' + post['post_excerpt'] + '</div>';
              response += '<div class="text_link"><a href="' + post['post_name'] + '">L\346s mere</a></div>';
            response += '</div>';
           response += '</div>';
        }
        jQuery('.inzite_event_info').html(response).fadeIn(250);

      },
      error: function(e, s, t) {
        console.log(t);
        console.log(s);
      }

    });
}

function inzite_change_month(month, year) {
  var parent_container = jQuery('.inzite-event-calendar').parent();
  jQuery('.inzite-event-calendar').remove();
  jQuery('.inzite_event_info').html( '<img src="' + inzite_events.img + 'ajax-loader.gif">');

  jQuery.ajax({
      type: "POST",
      url: inzite_events.ajaxurl,
      data: {
        action: 'inzite_change_month', month: month, year: year
      },
      success: function (response) {
        parent_container.prepend(response);
        check_calender_links();
        jQuery('.inzite_event_info').empty();
      }

    });
}
