/**
 * Created by zhalnin on 08/08/14.
 */
$(document).ready( function() {
    $("#newsticker").jCarouselLite( {
        vertical: true,
        hoverPause: true,
        btnPrev: "#news-prev",
        btnNext: "#news-next",
        visible: 3,
        auto: 3000,
        speed: 500
    } );
} );