jQuery(function($) {
    if($('.location-grid').length){
        var $grid = $('.location-grid').imagesLoaded ( function() {
            $grid.isotope({
                // options
                itemSelector: '.item',
                layoutMode: 'masonry'
            })});
    }
    if($('.location-grid').length){
        var $gridfix = $('.location-grid').imagesLoaded ( function() {
            $gridfix.isotope({
                // options
                itemSelector: '.item',
                layoutMode: 'fitRows',
                fitRows: {
                    gutter: 10
                }
            })});
    }
});
