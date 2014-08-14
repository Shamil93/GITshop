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


    /**
     * Появление и сокрытие формы для входа пользователей
     */
    $('.top-auth').click(function(){
        $("#block-top-auth").fadeToggle(200);
        $(this).attr('id', ($(this).attr('id') === 'active-button' ? '' : 'active-button'));
    });

    /**
     * Открыть закрыть пароль и сменить изображение закрытого глаза на открытый
     * и наоборот
     */
    $('#button-pass-show-hide').click(function(){
        var statuspass = $('#button-pass-show-hide').attr('class');
        if (statuspass == "pass-show") {
            $('#button-pass-show-hide').attr('class', 'pass-hide');
            var $input = $('#auth-pass'); // ссылка на объект в JQ
            var change = "text";
            var rep = $("<input placeholder='Пароль' type='"+change+"' />")
                .attr('id', $input.attr('id'))
                .attr('name', $input.attr('name'))
                .attr('class', $input.attr('class'))
                .val($input.val())
                .insertBefore($input);
            $input.remove();
//            $input = rep;
        } else {
            $('#button-pass-show-hide').attr('class', 'pass-show');
            var $input = $('#auth-pass'); // ссылка на объект в JQ
            var change = 'password';
            var rep = $("<input placeholder='Пароль' type='"+change+"' />")
                .attr('id', $input.attr('id'))
                .attr('name', $input.attr('name'))
                .attr('class', $input.attr('class'))
                .val($input.val())
                .insertBefore($input);
            $input.remove();
//            $input = rep;
        }
    });


    /**
     * При клике на кнопку Вход
     * проверяем поле Логин, по ошибке отображаем красную границу
     * проверяем поле Пароль, по ошибке отображаем красную границу
     * если отмечен чекбокс Запомнить - сохраняем ее
     * если нет ошибок, делаем ajax-запрос в обработчик auth.php
     * по результату, либо перегружаем страницу, либо опять отображаем кнопку входа
     */
    $('#button-auth').click(function() {
        var auth_login = $('#auth-login').val(),
            auth_pass = $('#auth-pass').val(),
            send_login, send_pass, auth_rememberme;

        if (auth_login == "" || auth_login.length > 30) {
            $('#auth-login').css("borderColor", "#FDB6B6");
            send_login = 'no';
        } else {
            $('#auth-login').css("borderColor", '#DBDBDB');
            send_login = 'yes';
        }

        if (auth_pass == "" || auth_pass > 15) {
            $('#auth-pass').css("borderColor", '#FDB6B6');
            send_pass = 'no';
        } else {
            $('#auth-pass').css('borderColor', '#DBDBDB');
            send_pass = 'yes';
        }

         if ($('#rememberme').prop('checked')) {
             auth_rememberme = 'yes';
         } else {
             auth_rememberme = 'no';
         }

        if (send_login == 'yes' && send_pass == 'yes') {
            $('#button-auth').hide();
            $('.auth-loading').show();

            $.ajax({
                type: 'POST',
                url: 'include/auth.php',
                data:  'login='+auth_login+'&pass='+auth_pass+'&rememberme='+auth_rememberme,
                dataType: 'html',
                cache: false,
                success: function(data) {
                    if (data == 'yes_auth') {
                        location.reload();
                    } else {
                        $('#message-auth').slideDown(400);
                        $('.auth-loading').hide();
                        $('#button-auth').show();
                    }
                }
            });
        }

    });

    /**
     * Появление блока восстановления пароля
     */
    $('#remindpass').click(function(){
        $('#input-email-pass').fadeOut(200, function() {
            $('#block-remind').fadeIn(300);
        });
    });
    $('#prev-auth').click(function(){
       $('#block-remind').fadeOut(200, function(){
           $('#input-email-pass').fadeIn(300);
       }) ;
    });


    /**
     * Отправка запроса обработчику на восстановление пароля
     * посредством ajax
     */
    $('#button-remind').click(function(){
       var recall_email = $('#remind-email').val();
        if(recall_email == "" || recall_email.length > 20) {
            $('#remind-email').css('borderColor', '#FDB6B6');
        } else {
            $('#remind-email').css('borderColor', '#DBDBDB');
            $('#button-remind').hide();
            $('.auth-loading').show();

            $.ajax({
                type: 'POST',
                url: 'include/remind-pass.php',
                data: 'email='+recall_email,
                dataType: 'html',
                cache: false,
                success: function(data){
                    if(data == 'yes') {
                        $('.auth-loading').hide();
                        $('#button-remind').show();
                        $('#message-remind').attr('class','message-remind-success').html('На ваш E-mail выслан пароль.').slideDown(400);
                        setTimeout("$('#message-remind').html('').hide(), $('#block-remind').hide(), $('#input-email-pass').show()", 3000);
                    } else {
                        $(".auth-loading").hide();
                        $('#button-remind').show();
                        $('#message-remind').attr('class','message-remind-error').html(data).slideDown(400);
                    }
                }
            })
        }
    });



} );

