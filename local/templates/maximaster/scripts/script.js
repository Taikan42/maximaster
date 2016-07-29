/**
 * Created by STANKEVICH on 15.07.2016.
 */
$(document).ready(function () {
    PopUpHide();
    var navigate = $('#head-navigate');
    var borderY = navigate.offset().top;
    $(window).scroll(function(){
        var currentY = $(document).scrollTop();
        if(currentY > borderY)
        {
            if (navigate.hasClass("navigate-static")){
                navigate.removeClass("navigate-static");
                navigate.addClass("navigate-fixed");
            }
        }
        else
        {
            if (navigate.hasClass("navigate-fixed")){
                navigate.removeClass("navigate-fixed");
                navigate.addClass("navigate-static");
            }
        }
    });
});
var $body = $('body');
$body.on('click', 'button', function () {
    PopUpShow();
    var id = $(this).attr('id');
    var quantity = $('.QUANTITY').attr('value');
    add2basket(id, quantity ? quantity : 1);
});
$body.on('click', '.popup', function () {
    PopUpHide();
});
$body.on('click', 'a.continue', function () {
    PopUpHide();
});
function PopUpShow() {
    $("#popup1").show();
}
function PopUpHide() {
    $("#popup1").hide();
}
function add2basket(ID, QUANTITY) {
    $.ajax({
        type: 'POST',
        url: "/local/templates/maximaster/cart/addbasket_ajax.php",
        data: {id: ID, quantity: QUANTITY}
    });
}
jQuery(function(){
    $(".zoom-img").imagezoomsl({
        zoomrange: [1, 12],
        zoomstart: 4,
        innerzoom: true,
        magnifierborder: "none"
    });
});   