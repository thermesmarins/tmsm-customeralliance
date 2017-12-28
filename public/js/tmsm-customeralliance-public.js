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
    var maxpage = $(this).data('maxpage');
    var href = $(this).attr('href');

    $(this).data('page', (page + 1));
    $(this).attr('href','#'+hrefmodel+(page + 1));
    $('.customeralliance-reviews .customeralliance-reviews-item[data-page="'+page+'"]').show();
    console.log($('.customeralliance-reviews-item[data-page-id="'+(page)+'"]'));
    var scrolltopage = $('.customeralliance-reviews-item[data-page-id="'+(page)+'"]').attr('id');
    console.log('scrolltopage:'+scrolltopage);
    $('html, body').animate({ scrollTop: $('#'+scrolltopage).offset().top}, 600, 'swing');

    if((page +1 )== maxpage){
      $(this).remove();
    }

    return false;
  });

})( jQuery );
