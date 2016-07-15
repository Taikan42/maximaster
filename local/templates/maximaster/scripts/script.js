/**
 * Created by STANKEVICH on 15.07.2016.
 */
$(document).ready(function () {
    PopUpHide();
});
$('body').on('click', 'button', function () {
    PopUpShow();
    var id = $(this).attr('id');
    var quantity = $('.QUANTITY').attr('value');
    add2basket(id, quantity ? quantity : 1);
});
$('body').on('click', '.popup', function () {
    PopUpHide();
});
$('body').on('click', 'a.continue', function () {
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
window.onscroll = function() {
    var scrolled = window.pageYOffset || document.documentElement.scrollTop;
    var elem = document.getElementById("head-navigate");
    if (scrolled > 70)
    {
        if (elem.classList.contains("navigate-static")){
            elem.classList.remove("navigate-static");
            elem.classList.add("navigate-fixed");
        }
    } else {
        if (elem.classList.contains("navigate-fixed")){
            elem.classList.remove("navigate-fixed");
            elem.classList.add("navigate-static");
        }
    }
}