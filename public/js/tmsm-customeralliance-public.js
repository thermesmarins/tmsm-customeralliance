(function( $ ) {
	'use strict';

  $('.customeralliance-reviews-item-heading').on('click', function (e) {
    $($(this).attr('href')).slideToggle();
    e.preventDefault();
  });
	$('#customeralliance-certificate-btn').on('click', function (e) {
	  $('#whatiscustomeralliance').slideToggle();
	  e.preventDefault();
  });

  $('#customeralliance-reviews-btnmore').on('click', function () {

    var hrefmodel = $(this).data('hrefmodel');
    var page = $(this).data('page');
    var nextpage = page + 1;
    var maxpage = $(this).data('maxpage');
    var href = $(this).attr('href');

    $(this).data('page', nextpage);
    $(this).attr('href','#'+hrefmodel+nextpage);
    $('.customeralliance-reviews .customeralliance-reviews-item[data-page="'+page+'"]').show();
    var scrolltopage = $('.customeralliance-reviews-item[data-page-id="'+page+'"]').attr('id');
    $('html, body').animate({ scrollTop: $('#'+scrolltopage).offset().top}, 600, 'swing');

    if( nextpage === maxpage){
      $(this).remove();
    }
    return false;
  });

})( jQuery );