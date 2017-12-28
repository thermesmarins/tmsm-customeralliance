(function( $ ) {
	'use strict';

  $('#customeralliance-reviews-btnmore').on('click', function () {

    var hrefmodel = $(this).data('hrefmodel');
    var page = $(this).data('page');
    var maxpage = $(this).data('maxpage');
    var href = $(this).attr("href");

    $(this).data('page', (page + 1));
    $(this).attr('href','#'+hrefmodel+(page + 1));
    $('.customeralliance-reviews .customeralliance-reviews-item[data-page="'+page+'"]').show();
    $('html, body').animate({ scrollTop: $(href).offset().top}, 600, 'swing');

    if((page +1 )== maxpage){
      $(this).remove();
    }

    return false;
  });

})( jQuery );
