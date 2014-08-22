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

//    /**
//     * Обновление данных в корзине товаров
//     */
//    loadCart();

    /**
     * Изменяем вид списка товаров на сетку
     * и меняем цвет иконки
     */
    $( "#style-grid" ).click( function(e) {
        e.preventDefault();
        $("#block-tovar-list").hide();
        $("#block-tovar-grid").show();
        $("#style-grid").attr('src', 'ecommerce/view/images/icon-grid-active.png');
        $("#style-list").attr('src', 'ecommerce/view/images/icon-list.png');
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
        $("#style-list").attr('src', 'ecommerce/view/images/icon-list-active.png');
        $("#style-grid").attr('src', 'ecommerce/view/images/icon-grid.png');
        $.cookie( 'select_style', 'list' ); // сохраняем в куки
    } );

//    $( '#select-sort' ).click( function(e) {
//        e.preventDefault();
//        $("#sorting-list").slideToggle( 200 );
//    });
//
    /**
     * Проверяем наличие установленных куки для вида расположения картинок
     */
    if($.cookie( 'select_style' ) == 'grid' ) {
        $("#block-tovar-list").hide();
        $("#block-tovar-grid").show();
        $("#style-grid").attr('src', 'ecommerce/view/images/icon-grid-active.png');
        $("#style-list").attr('src', 'ecommerce/view/images/icon-list.png');
    } else {
        $("#block-tovar-grid").hide();
        $("#block-tovar-list").show();
        $("#style-list").attr('src', 'ecommerce/view/images/icon-list-active.png');
        $("#style-grid").attr('src', 'ecommerce/view/images/icon-grid.png');
    }


//    /**
//     * Раскрываем или сворачиваем категории товаров
//     */
//    $('#block-category > ul > li > a').click( function() { // по клику на одной из категории
//        if( $(this).attr('class') != 'active' ) { // если не имеет класс active - значит закрыта
//            $('#block-category > ul > li > ul').slideUp(400); // сворачиваем все пункты
//            $(this).next().slideToggle(400); // а пункты у нажатой категории разворачиваем
//            $('#block-category > ul > li > a').removeClass('active'); // и у свернутых пунктов удаляем класс active
//            $(this).addClass('active'); // а добавляем его только что развернутому
//            $.cookie('select_cat', $(this).attr('id') ); // сохраняем в куки атрибут id развернутой категории
//        } else { // если категория уже развернута - имеет класс active
//            $('#block-category > ul > li > a').removeClass('active'); // удаляем его у нее
//            $('#block-category > ul > li > ul').slideUp(400); // сворачиваем категорию
//            $.cookie('select_cat', ''); // удаляем из куки информацию о ней
//        }
//    })
//
//    /**
//     * Проверяем наличие установленных куки для категории товаров
//     */
//    if($.cookie('select_cat') != '') {
//        $('#block-category > ul > li > #'+ $.cookie('select_cat')).addClass('active').next().show();
//    }
//
//
//    /**
//     * Для генерации пароля для формы регистрации
//     */
//    $('#genpass').click(function(){
//        $.ajax({
//            type: "POST",
//            url: "utility/genpass.php",
//            dataType: "html",
//            cache: false,
//            success: function(data) {
//                $('#reg_pass').val(data);
//            }
//        });
//    });
//
//
//    /**
//     * Для обновление изображения капчи
//     */
//    $('#reloadcaptcha').click(function(){
//        $('#block-captcha > img').attr('src','reg/reg_captcha.php?r='+Math.random());
//    });
//
//
//    /**
//     * Появление и сокрытие формы для входа пользователей
//     */
//    $('.top-auth').click(function(){
//        $("#block-top-auth").fadeToggle(200);
//        $(this).attr('id', ($(this).attr('id') === 'active-button' ? '' : 'active-button'));
//    });
//
//    /**
//     * Открыть закрыть пароль и сменить изображение закрытого глаза на открытый
//     * и наоборот
//     */
//    $('#button-pass-show-hide').click(function(){
//        var statuspass = $('#button-pass-show-hide').attr('class');
//        if (statuspass == "pass-show") {
//            $('#button-pass-show-hide').attr('class', 'pass-hide');
//            var $input = $('#auth-pass'); // ссылка на объект в JQ
//            var change = "text";
//            var rep = $("<input placeholder='Пароль' type='"+change+"' />")
//                .attr('id', $input.attr('id'))
//                .attr('name', $input.attr('name'))
//                .attr('class', $input.attr('class'))
//                .val($input.val())
//                .insertBefore($input);
//            $input.remove();
////            $input = rep;
//        } else {
//            $('#button-pass-show-hide').attr('class', 'pass-show');
//            var $input = $('#auth-pass'); // ссылка на объект в JQ
//            var change = 'password';
//            var rep = $("<input placeholder='Пароль' type='"+change+"' />")
//                .attr('id', $input.attr('id'))
//                .attr('name', $input.attr('name'))
//                .attr('class', $input.attr('class'))
//                .val($input.val())
//                .insertBefore($input);
//            $input.remove();
////            $input = rep;
//        }
//    });
//
//
//    /**
//     * При клике на кнопку Вход
//     * проверяем поле Логин, по ошибке отображаем красную границу
//     * проверяем поле Пароль, по ошибке отображаем красную границу
//     * если отмечен чекбокс Запомнить - сохраняем ее
//     * если нет ошибок, делаем ajax-запрос в обработчик auth.php
//     * по результату, либо перегружаем страницу, либо опять отображаем кнопку входа
//     */
//    $('#button-auth').click(function() {
//        var auth_login = $('#auth-login').val(),
//            auth_pass = $('#auth-pass').val(),
//            send_login, send_pass, auth_rememberme;
//
//        if (auth_login == "" || auth_login.length > 30) {
//            $('#auth-login').css("borderColor", "#FDB6B6");
//            send_login = 'no';
//        } else {
//            $('#auth-login').css("borderColor", '#DBDBDB');
//            send_login = 'yes';
//        }
//
//        if (auth_pass.length == "" || auth_pass.length > 15) {
//            $('#auth-pass').css("borderColor", '#FDB6B6');
//            send_pass = 'no';
//        } else {
//            $('#auth-pass').css('borderColor', '#DBDBDB');
//            send_pass = 'yes';
//        }
//
//         if ($('#rememberme').prop('checked')) {
//             auth_rememberme = 'yes';
//         } else {
//             auth_rememberme = 'no';
//         }
//
//        if (send_login == 'yes' && send_pass == 'yes') {
//            $('#button-auth').hide();
//            $('.auth-loading').show();
//
//            $.ajax({
//                type: 'POST',
//                url: 'include/auth.php',
//                data:  'login='+auth_login+'&pass='+auth_pass+'&rememberme='+auth_rememberme,
//                dataType: 'html',
//                cache: false,
//                success: function(data) {
//                    if (data == 'yes_auth') {
//                        location.reload();
//                    } else {
//                        $('#message-auth').slideDown(400);
//                        $('.auth-loading').hide();
//                        $('#button-auth').show();
//                    }
//                }
//            });
//        }
//
//    });
//
//    /**
//     * Появление блока восстановления пароля
//     */
//    $('#remindpass').click(function(){
//        $('#input-email-pass').fadeOut(200, function() {
//            $('#block-remind').fadeIn(300);
//        });
//    });
//    $('#prev-auth').click(function(){
//       $('#block-remind').fadeOut(200, function(){
//           $('#input-email-pass').fadeIn(300);
//       }) ;
//    });
//
//
//    /**
//     * Отправка запроса обработчику на восстановление пароля
//     * посредством ajax
//     */
//    $('#button-remind').click(function(){
//       var recall_email = $('#remind-email').val();
//        if(recall_email == "" || recall_email.length > 20) {
//            $('#remind-email').css('borderColor', '#FDB6B6');
//        } else {
//            $('#remind-email').css('borderColor', '#DBDBDB');
//            $('#button-remind').hide();
//            $('.auth-loading').show();
//
//            $.ajax({
//                type: 'POST',
//                url: 'include/remind-pass.php',
//                data: 'email='+recall_email,
//                dataType: 'html',
//                cache: false,
//                success: function(data){
//                    if(data == 'yes') {
//                        $('.auth-loading').hide();
//                        $('#button-remind').show();
//                        $('#message-remind').attr('class','message-remind-success').html('На ваш E-mail выслан пароль.').slideDown(400);
//                        setTimeout("$('#message-remind').html('').hide(), $('#block-remind').hide(), $('#input-email-pass').show()", 3000);
//                    } else {
//                        $(".auth-loading").hide();
//                        $('#button-remind').show();
//                        $('#message-remind').attr('class','message-remind-error').html(data).slideDown(400);
//                    }
//                }
//            })
//        }
//    });
//
//    /**
//     * Появление окна с профилем
//     */
//    $('#auth-user-info').click(function(){
//        $('#block-user').fadeToggle(100);
//    });
//
//
//    /**
//     * Обработчик logout
//     */
//     $('#logout').click(function() {
//         $.ajax({
//             type: 'POST',
//             url: 'include/logout.php',
//             dataType: 'html',
//             cache: false,
//             success: function(data) {
//                 if (data == 'logout') {
//                     location.reload();
//                 }
//             }
//         });
//     });
//
//
//    /**
//     * Появление результатов при вводе в поле поиска
//     */
//    $('#input-search').bind('textchange', function(e){
//        e.preventDefault();
//        var input_search = $('#input-search').val();
//
//        if (input_search.length >= 2 && input_search.length < 150) {
//            $.ajax({
//                type: 'POST',
//                url: 'include/search.php',
//                data: 'text='+input_search,
//                dataType: 'html',
//                cache: false,
//                success: function(data) {
//                    if (data > '') {
//                        $('#result-search').show().html(data);
//                    } else {
//                        $('#result-search').hide();
//                    }
//                }
//            });
//        } else {
//            $('#result-search').hide();
//        }
//    });
//
//
//    /**
//     * Функция проверки E-mail
//     * @param $email
//     * @returns {boolean}
//     */
//    function isValidEmail($email) {
//        var pattern = new RegExp(/^[-a-z0-9_\.]+@[-a-z0-9_\.]+\.[a-z]{2,6}$/i);
//        return pattern.test($email);
//    }
//
//    // Контактные данные
//    $('#confirm-button-next').click(function(e) {
//
//        var order_fio = $('#order_fio').val();
//        var order_email = $('#order_email').val();
//        var order_phone = $('#order_phone').val();
//        var order_address = $('#order_address').val();
//        var send_order_error = "";
//
//        // Проверка способа доставки
//        if (!$('.order_delivery').is(":checked")) {
//            $(".label_delivery").css('color', '#E07B7B');
////            send_order_delivery = '0';
//            send_order_error = 'error';
//        } else {
//            $('.label_delivery').css('color','#000000');
////            send_order_delivery = '1';
//        }
//        // Проверка фамилии
//        if (order_fio == "" || order_fio.length > 50) {
//            $("#order_fio").css('borderColor', '#FDB6B6');
////            send_order_fio = '0';
//            send_order_error = 'error';
//        } else {
//            $('#order_fio').css('borderColor','#DBDBDB');
////            send_order_fio = '1';
//        }
//        // Проверка email
//        if (order_email == "" || isValidEmail(order_email) == false) {
//            $('#order_email').css('borderColor','#FDB6B6');
////            send_order_email = '0';
//            send_order_error = 'error';
//        } else {
//            $('#order_email').css('borderColor','#DBDBDB');
////            send_order_email = '1';
//        }
//        // Проверка телефона
//        if (order_phone == "" || order_phone.length > 50) {
//            $('#order_phone').css('borderColor','#FDB6B6');
////            send_order_phone = '0';
//            send_order_error = 'error';
//        } else {
//            $('#order_phone').css('borderColor', '#DBDBDB');
////            send_order_phone = '1';
//        }
//        // Проверка адреса
//        if (order_address == "" || order_address.length > 150) {
//            $('#order_address').css('borderColor', '#FDB6B6');
////            send_order_address = '0';
//            send_order_error = 'error';
//        } else {
//            $('#order_address').css('borderColor', '#DBDBDB');
////            send_order_address = '1';
//        }
//        // Если нет ошибки, то отправляем форму
//        if( send_order_error.indexOf('error') === -1) {
//            return true;
//        }
//        e.preventDefault();
//    });
//
//
//    /**
//     * Добавление товара в корзину
//     */
//    $('.add-cart-style-list, .add-cart-style-grid, .add-cart, .random-add-cart').click(function(e){
//        e.preventDefault();
//        var tid = $(this).attr("tid");
//        $.ajax({
//            type: 'POST',
//            url: "include/addtocart.php",
//            data: "id="+tid,
//            dataType: 'html',
//            cache: false,
//            success: function(data){
//                loadCart();
//            }
//        });
//    });
//    function loadCart() {
//        $.ajax({
//            type: 'POST',
//            url: 'include/loadcart.php',
//            dataType: 'html',
//            cache: false,
//            success: function(data) {
//                if (data == "0") {
//                    $("#block-basket > a").html('Корзина пуста');
//                } else {
//                    $('#block-basket > a').html(data);
////                    $('.itog-price > strong').html(data);
//                }
//            }
//        });
//    }
//
//    /**
//     * Форматируем цену
//     * Находим разрывы между цифрами, если после каждого разрыва следует
//     * блоки по 3 цифры в каждом и при этом после этого блока из 3-х цифр нет одиночных цифр
//     * Эти разрывы заменяем пробелами
//     * @param value
//     */
//    function groupPrice(value) {
//        var value = String(value);
//        // \B - находит не границу слов, антоним \b
//        // x(?=y) - находит x, только если за x следует y
//        // x(!y) - находит x, только если за x не следует y
//        // (:x) - находит x, но не запоминает
//        // g - глобальный поиск(обрабатываются все совпадения)
//        return  value.replace(/\B(?=(?:\d{3})+(?!\d))/g,' ');
//    }
//
//
//    /**
//     *  Уменьшение количества товаров
//     * с их перерасчетом
//     */
//    $('.count-minus').click(function(){
//        var iid = $(this).attr('iid');
//        $.ajax({
//           type: 'POST',
//            url: 'include/count-minus.php',
//            data: 'id='+iid,
//            dataType: 'html',
//            cache: false,
//            success: function(data) {
//                $('#input-id'+iid).val(data);
//                loadCart();
//
//                var priceproduct = $('#tovar' + iid + " > p").attr('price'),
//                    result_total = Number(priceproduct) * Number(data);
//
//                $('#tovar'+iid+' > p').html(groupPrice(result_total)+" руб");
//                $('#tovar'+iid+' > h5 > .span-count').html(data);
//
//                itogPrice();
//            }
//
//        });
//    });
//
//    /**
//     * Увеличение количества товаров
//     * с их перерасчетом
//     */
//    $('.count-plus').click(function(){
//        var iid = $(this).attr('iid');
//
//        $.ajax({
//            type: 'POST',
//            url: 'include/count-plus.php',
//            data: 'id='+iid,
//            dataType: 'html',
//            cache: false,
//            success: function(data) {
//                $('#input-id'+iid).val(data);
//                loadCart();
//
//                var priceproduct = $('#tovar' + iid + " > p").attr('price'),
//                    result_total = Number(priceproduct) * Number(data);
//
//                $('#tovar'+iid+' > p').html(groupPrice(result_total)+" руб");
//                $('#tovar'+iid+' > h5 > .span-count').html(data);
//
//                itogPrice();
//            }
//
//        });
//    });
//
//
//    /**
//     * Изменение количества товара в корзине
//     * при изменении его в поле input
//     * и нажатии на Enter
//     */
//    $('.count-input').keypress(function(e){
//        if (e.keyCode == 13) {
//            var iid = $(this).attr('iid');
//            var inc = $('#input-id'+iid).val();
//
//            $.ajax({
//                type: "POST",
//                url: 'include/count-input.php',
//                data: 'id='+iid+'&count='+inc,
//                dataType: 'html',
//                cache: false,
//                success: function(data) {
//                    $('#input-id'+iid).val(data);
//                    loadCart();
//
//                    var priceProduct = $('#tovar'+iid+" > p").attr('price'),
//                        result_total = Number(priceProduct) * Number(data);
//
//                    $('#tovar'+iid+' > p').html(groupPrice(result_total) + ' руб');
//                    $('#tovar'+iid+' > h5 > .span-count').html(data);
//                    itogPrice();
//                }
//            });
//        }
//    });
//
//    /**
//     * Обновление итоговой суммы в корзине при перерасчете
//     */
//    function itogPrice(){
//        $.ajax({
//            type: 'POST',
//            url: 'include/itog-price.php',
//            dataType: 'html',
//            cache: false,
//            success: function(data) {
//                $(".itog-price > strong").html(data);
//            }
//        });
//    }
//
//
//    /**
//     * Проверка полей формы отзыва
//     */
//    $('#button-send-review').click(function(){
//        var name = $('#name_review').val(),
//            good = $('#good_review').val(),
//            bad = $('#bad_review').val(),
//            comment = $('#comment_review').val(),
//            iid = $('#button-send-review').attr('iid'),
//            name_review = '',
//            good_review = '',
//            bad_review = '',
//            comment_review = '';
//
//        if (name != "") {
//           name_review = '1';
//            $('#name_review').css('borderColor', "#DBDBDB");
//        } else {
//            name_review = "0";
//            $('#name_review').css('borderColor', '#FDB6B6');
//        }
//        if (good != "") {
//            good_review = '1';
//            $('#good_review').css('borderColor', "#DBDBDB");
//        } else {
//            good_review = "0";
//            $('#good_review').css('borderColor', '#FDB6B6');
//        }
//        if (bad != "") {
//            bad_review = '1';
//            $('#bad_review').css('borderColor', "#DBDBDB");
//        } else {
//            bad_review = "0";
//            $('#bad_review').css('borderColor', '#FDB6B6');
//        }
//        if (name_review == '1' && good_review == '1' && bad_review == '1') {
//            $('#button-send-review').hide();
//            $('#reload-img').show();
//
//            $.ajax({
//                type: 'POST',
//                url: 'include/add_review.php',
//                data: 'id='+iid+'&name='+name+'&good='+good+'&bad='+bad+'&comment='+comment,
//                dataType: 'html',
//                cache: false,
//                success: function(){
//                    setTimeout('$.fancybox.close()', 1000);
//                }
//            });
//        }
//    });
//
//
//    /**
//     * Блок "Нравится"
//     */
//    $('#likegood').click(function(){
//        var tid = $(this).attr('tid');
//
//        $.ajax({
//            type: 'POST',
//            url: 'include/like.php',
//            data: 'id='+tid,
//            dataType: 'html',
//            cache: false,
//            success: function(data) {
//                if (data == 'no') {
//                    alert('Вы уже проголосовали!');
//                } else {
//                    $('#likegoodcount').html(data);
//                }
//            }
//        });
//    });
//

});

