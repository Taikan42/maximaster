jQuery(function ($) {
    $('#user_phone').mask("+7(999) 999-99-99");
});
$(document).ready(function () {
    $('#sale_order').validate({
        rules: {
            name: {
                required: true,
                minlength: 2,
                maxlength: 16
            },
            surname: {
                required: true,
                minlength: 2,
                maxlength: 16
            },
            middle_name: {
                required: true,
                minlength: 2,
                maxlength: 16
            },
            phone: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            Delivery: {
                required: true
            },
            Payment: {
                required: true
            }
        },
        messages: {
            name: {
                required: "Это поле обязательно для заполнения",
                minlength: "Минимальное число символо - 2",
                maxlength: "Максимальное число символо - 16"
            },
            surname: {
                required: "Это поле обязательно для заполнения",
                minlength: "Минимальное число символо - 2",
                maxlength: "Максимальное число символо - 16"
            },
            middle_name: {
                required: "Это поле обязательно для заполнения",
                minlength: "Минимальное число символо - 2",
                maxlength: "Максимальное число символо - 16"
            },
            phone: {
                required: "Это поле обязательно для заполнения"
            },
            email: {
                required: "Это поле обязательно для заполнения",
                email: "Your email address must be in the format of name@domain.com"
            },
            Delivery: {
                required: ""
            },
            Payment: {
                required: ""
            }
        }
    });
    var deliv = $("[name='Delivery']");
    var check_pay = false;
    var selectID;
    deliv.click(function () {
        if (!check_pay) {
            $(".payment").removeClass("hide");
            check_pay = true;
        }
        if ($(this).val() != selectID) {
            selectID = $(this).val();
            /*ищем скрытый инпут с ограничениями для типа платежей для данного способа получения
             * записываем айди разрешенных типов платежей в массив*/
            var Pay_ID = $("[name=\'permiss_types_payment-delID=" + $(this).val() + "\']").val().split(',');
            $('.payment_wrap').each(function () {
                /*скрываем все платежы и снимаем выделение*/
                $(this).addClass("hide");
                $(this).children().removeAttr("checked");
            });
            for (index = Pay_ID.length - 1; index >= 0; --index) {
                /*показываем только разрешенные платежы*/
                $(".paymentID-" + String(Pay_ID[index])).removeClass("hide");
            }
        }
    });
    /*Проверка и выделение пустых полей*/
    /*
     $('.form_text').blur(function() {
     if ($(this).val()){
     $(this).css("border-color","green");
     $(this).removeClass('empty_field');
     } else {
     $(this).css("border-color","red");
     $(this).addClass('empty_field');
     }
     });*/
    /*Проверка и выделение для email*/
    /*
     $('#user_email').blur(function() {
     if($(this).val() != '') {
     var pattern = /.+@.+\..+/i;
     if(pattern.test($(this).val())){
     $(this).css("border-color","green");
     $(this).removeClass('empty_field');
     $(this).next().html("");
     } else {
     $(this).css("border-color","red");
     $(this).addClass('empty_field');
     $(this).next().html("неккоректный email");
     }
     } else {
     $(this).css("border-color","red");
     $(this).addClass('empty_field');
     }
     });*/
    /*Деактивация принятия формы при пустых полях*/
    /*
     $('input').blur(function () {
     if($('form').find('.empty_field').size() > 0){
     $('.submit').attr('disabled','disabled');
     } else {
     $('.submit').removeAttr('disabled');
     }
     });*/
});