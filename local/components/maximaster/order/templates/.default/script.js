/*Проверка телефона*/
jQuery(function($){
    $('#user_phone').mask("+7(999) 999-99-99");
});
$(document).ready(function() {
    /*Проверка и выделение пустых полей*/
    $('.form_text').blur(function() {
        if ($(this).val()){
            $(this).css("border-color","green");
            $(this).removeClass('empty_field');
        } else {
            $(this).css("border-color","red");
            $(this).addClass('empty_field');
        }
    });
    /*Проверка и выделение для email*/
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
    });
    /*Деактивация принятия формы при пустых полях*/
    $('input').blur(function () {
        if($('form').find('.empty_field').size() > 0){
            $('.submit').attr('disabled','disabled');
        } else {
            $('.submit').removeAttr('disabled');
        }
    });
});