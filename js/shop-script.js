/**
 * Created by zhalnin on 08/08/14.
 */
$(document).ready( function() {
    /**
     * Для прокрутки блока новостей
     */
    $("#newsticker").jCarouselLite( {
        vertical: true,
        hoverPause: true,
        btnPrev: "#news-prev",
        btnNext: "#news-next",
        visible: 3,
        auto: 3000,
        speed: 500
    } );

    /**
     * Изменяем вид списка товаров на сетку
     * и меняем цвет иконки
     */
    $( "#style-grid" ).click( function() {
        $("#block-tovar-list").hide();
        $("#block-tovar-grid").show();
        $("#style-grid").attr('src', 'images/icon-grid-active.png');
        $("#style-list").attr('src', 'images/icon-list.png');
        $.cookie( 'select_style', 'grid' );
    } );
    /**
     * Изменяем вид списка товаров на список
     * и меняем цвет иконки
     */
    $( "#style-list" ).click( function() {
        $("#block-tovar-grid").hide();
        $("#block-tovar-list").show();
        $("#style-list").attr('src', 'images/icon-list-active.png');
        $("#style-grid").attr('src', 'images/icon-grid.png');
        $.cookie( 'select_style', 'list' );
    } );

    $( '#select-sort' ).click( function() {
        $("#sorting-list").slideToggle( 200 );
    });




    if($.cookie( 'select_style' ) == 'grid' ) {
        $("#block-tovar-list").hide();
        $("#block-tovar-grid").show();
        $("#style-grid").attr('src', 'images/icon-grid-active.png');
        $("#style-list").attr('src', 'images/icon-list.png');
    } else {
        $("#block-tovar-grid").hide();
        $("#block-tovar-list").show();
        $("#style-list").attr('src', 'images/icon-list-active.png');
        $("#style-grid").attr('src', 'images/icon-grid.png');
    }




} );

