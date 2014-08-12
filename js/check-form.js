/**
 * Created by zhalnin on 12/08/14.
 */
$(document).ready(function(){
    $('#form_reg').validate({
        // правила проверки
        rules: {
            "reg_login": {
                required: true,
                minlength: 5,
                maxlength: 15,
                remote: {
                    type: "post",
                    url: "reg/check_login.php"
                }
            },
            "reg_pass": {
                required: true,
                minlength: 7,
                maxlength: 15
            },
            "reg_surname": {
                required: true,
                minlength: 3,
                maxlength: 15
            },
            "reg_name": {
                required: true,
                minlength: 3,
                maxlength: 15
            },
            "reg_patronymic": {
                required: true,
                minlength: 3,
                maxlength: 25
            },
            "reg_email": {
                required: true,
                email: true
            },
            "reg_phone": {
                required: true
            },
            "reg_address": {
                required: true
            },
            "reg_captcha": {
                required: true,
                remote: {
                    type: "post",
                    url: "reg/check_captcha.php"
                }
            }
        },

        // выводим сообщения при нарушении соответствующих правил
        messages: {
            "reg_login": {
                required: "Укажите Логин!",
                minlength: "От 5 до 15 символов!",
                maxlength: "От 5 до 15 символов!",
                remote: "Логин занят!"
            },
            "reg_pass": {
                required: "Укажите Пароль!",
                minlength: "От 7 до 15 символов!",
                maxlength: "От 7 до 15 символов!"
            },
            "reg_surname": {
                required: "Укажите вашу Фамилию!",
                minlength: "От 3 до 20 символов!",
                maxlength: "От 3 до 20 символов!"
            },
            "reg_name": {
                required: "Укажите ваше Имя!",
                minlength: "От 3 до 15 символов!",
                maxlength: "От 3 до 15 символов!"
            },
            "reg_patronymic": {
                required: "Укажите ваше Отчество!",
                minlength: "От 3 до 25 символов!",
                maxlength: "От 3 до 25 символов!"
            },
            "reg_email": {
                required: "Укажите свой E-mail!",
                email: "Не корректный E-mail!"
            },
            "reg_phone": {
                required: "Укажите номер телефона!"
            },
            "reg_address": {
                required: "Необходимо указать адрес доставки!"
            },
            "reg_captcha": {
                required: "Введите код с картинки!",
                remote: "Не верный код проверки!"
            }
        },
        submitHandler: function(form) {

            $(form).ajaxSubmit({
                method: 'post',
                success: function(data) {
                    if (data == 'true'){
                        $('#block-form-registration').fadeOut(300, function() {
                            $("#reg_message").addClass("reg_message_good").fadeIn(400).html("Вы успешно зарегистрированы!");
                            $("#form_submit").hide();
                        });
                    } else {
                        $("#reg_message").addClass("reg_message_error").fadeIn(400).html(data);
                    }
                }
            });
        }
    });
});