$(function () {
  var lastTimeRequested;
  var searchField = $('#search-ticket');
  var ticketTable = $('#listTickets');
  var ticketTableHtml = ticketTable.find('tbody').html();

  /**
   * 
   * @param {*} fn 
   * @param {*} delay 
   */
  function debounce(fn, delay) {
    var timer = null;

    return function () {
      var context = this,
        args = arguments;

      clearTimeout(timer);

      timer = setTimeout(function () {

        fn.apply(context, args);

      }, delay);
    };
  }

  function searchTickets() {
    lastTimeRequested = new Date().getTime();

    if (searchField.val().trim().length < 3) {
      ticketTable.find("tbody").html(ticketTableHtml);
      $('.pagination').show()
    } else {

      $.ajax({
        type: "post",
        url: "classes/Search.php",
        data: {
          action: "Load",
          requestedAt: lastTimeRequested,
          s: searchField.val()
        },
        dataType: "json",
        beforeSend: function() {
          $('.pagination').hide()
          ticketTable.find('tbody').html("<tr><td colspan='11'>Loading</td></tr>");
        },
        success: function (response) {
          if (response.results) {
            if (response.requested_at == lastTimeRequested) {
              setTimeout(function(){
                ticketTable.find('tbody').html(response.results)
              }, 1000)
            }
          }
        },
        complete: function() {
          console.log('complete');
        },
        error: function (error) {
          console.error(error);
          ticketTable.find('tbody').html("<tr><td colspan='11'>Could not find results</td></tr>");
        }
      });
    }
  }

  searchField.on('change textInput input keyup', debounce(searchTickets, 500));
});