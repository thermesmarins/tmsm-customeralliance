;(function ($) {

    $('#customeralliance-reviews-btnmore').on('click', function () {

        hrefmodel = $(this).data('hrefmodel');
        page = $(this).data('page');
        maxpage = $(this).data('maxpage');
        href = $(this).attr("href");

        $(this).data('page', (page + 1));
        $(this).attr('href','#'+hrefmodel+(page + 1));
        $('.customeralliance-reviews .customeralliance-reviews-item[data-page="'+page+'"]').show();
        $('html, body').animate({ scrollTop: $(href).offset().top}, 600, 'swing');

        if((page +1 )== maxpage){
            $(this).remove();
        }

        return false;
    });
}(jQuery));

