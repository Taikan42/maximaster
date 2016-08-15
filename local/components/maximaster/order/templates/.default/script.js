jQuery(function ($) {
    $('#user_phone').mask("+7(999) 999-99-99");
});
$(document).ready(function () {
    $.validator.addMethod("loc", function() {
        var val = $(".dropdown-field").val();
        if (val && val > 0){
            Cost();
            return true;
        } else{
            return false;
        }
    }, "Введите ваше местоположение");
    $.validator.addClassRules({
        'bx-ui-sls-fake': {
            loc: true
        }
    });
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
    var selectID = 0;
    var checkhide = true;
    $("[name=Delivery]").click(function () {
        var id = $(this).val();
        if (id != selectID) {
            selectID = id;
            var $load = $('.load');
            $load.show();
            Cost();
            $.ajax({
                type: 'POST',
                url: "/local/components/maximaster/order/ajax.php",
                data: {id: id, type: "PaySys"},
                success: function(out){
                    $("#PaySystem").html(out);
                    if (checkhide){
                        $('.PaySystem').show();
                        checkhide = false;
                    }
                    $load.hide();
                }
            });
        }
    });
});
function Cost() {
    var idDelivery = $('input[name=Delivery]:checked').val();
    var location = $(".dropdown-field").val();
    if (location > 0 && idDelivery){
        $.ajax({
            type: 'POST',
            url: "/local/components/maximaster/order/ajax.php",
            data: {idDelivery: idDelivery, location: location, type: "Cost"},
            success: function(out){
                $("#СostDelivery").html(out);
            }
        });
    } else {
        $("#СostDelivery").html("");
    }
}