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
            location: {
                required: true
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
    var deliv = $("[name=Delivery]");
    var selectID;
    var checkhide = true;
    deliv.click(function () {
        var id = $(this).val();
        console.log("click");
        console.log(id);
        if (id != selectID) {
            var selectID = id;
            var $load = $('.load');
            $load.show();
            $.ajax({
                type: 'POST',
                url: "/local/components/maximaster/order/PaySystems.php",
                data: {id: id},
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
/*
$('body').on('click', "[name='Delivery']", function () {
    var id = $(this).attr('value');
    var $PaySys = $('.PaySystem');
    $PaySys.hide();
    $.ajax({
        type: 'POST',
        url: "/local/components/maximaster/order/PaySystems.php",
        data: {id: id},
        success: function(out){
            $("#PaySystem").html(out);
            $PaySys.show();
        }
    });
});*/