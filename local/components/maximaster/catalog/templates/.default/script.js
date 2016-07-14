/**
 * Created by STANKEVICH on 14.07.2016.
 */
$(document).ready(function(){
    PopUpHide();
    $('button').click(function () {
        PopUpShow();
        var id = $(this).attr("data-parameter");
        add2basket(id);
    })
});
function PopUpShow(){
    $("#popup1").show();
}
function PopUpHide(){
    $("#popup1").hide();
}
function add2basket(ID)
{
    var clv = 1;
    $.ajax({
        type: 'POST',
        url: "/local/templates/maximaster/cart/addbasket_ajax.php",
        data: {id: ID, quantity: clv}
    });
}