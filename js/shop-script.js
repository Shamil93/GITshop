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
    $( "#style-grid" ).click( function(e) {
        e.preventDefault();
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
    $( "#style-list" ).click( function(e) {
        e.preventDefault();
        $("#block-tovar-grid").hide();
        $("#block-tovar-list").show();
        $("#style-list").attr('src', 'images/icon-list-active.png');
        $("#style-grid").attr('src', 'images/icon-grid.png');
        $.cookie( 'select_style', 'list' ); // сохраняем в куки
    } );

    $( '#select-sort' ).click( function(e) {
        e.preventDefault();
        $("#sorting-list").slideToggle( 200 );
    });

    /**
     * Проверяем наличие установленных куки для вида расположения картинок
     */
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


    /**
     * Раскрываем или сворачиваем категории товаров
     */
    $('#block-category > ul > li > a').click( function() { // по клику на одной из категории
        if( $(this).attr('class') != 'active' ) { // если не имеет класс active - значит закрыта
            $('#block-category > ul > li > ul').slideUp(400); // сворачиваем все пункты
            $(this).next().slideToggle(400); // а пункты у нажатой категории разворачиваем
            $('#block-category > ul > li > a').removeClass('active'); // и у свернутых пунктов удаляем класс active
            $(this).addClass('active'); // а добавляем его только что развернутому
            $.cookie('select_cat', $(this).attr('id') ); // сохраняем в куки атрибут id развернутой категории
        } else { // если категория уже развернута - имеет класс active
            $('#block-category > ul > li > a').removeClass('active'); // удаляем его у нее
            $('#block-category > ul > li > ul').slideUp(400); // сворачиваем категорию
            $.cookie('select_cat', ''); // удаляем из куки информацию о ней
        }
    })

    /**
     * Проверяем наличие установленных куки для категории товаров
     */
    if($.cookie('select_cat') != '') {
        $('#block-category > ul > li > #'+ $.cookie('select_cat')).addClass('active').next().show();
    }


    /**
     * Для генерации пароля для формы регистрации
     */
    $('#genpass').click(function(){
        $.ajax({
            type: "POST",
            url: "utility/genpass.php",
            dataType: "html",
            cache: false,
            success: function(data) {
                $('#reg_pass').val(data);
            }
        });
    });


    /**
     * Для обновление изображения капчи
     */
    $('#reloadcaptcha').click(function(){
        $('#block-captcha > img').attr('src','reg/reg_captcha.php?r='+Math.random());
    });

} );

