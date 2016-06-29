/**
 * Created by STANKEVICH on 29.06.2016.
 */
window.onscroll = function() {
    var scrolled = window.pageYOffset || document.documentElement.scrollTop;
    var elem = document.getElementById("head-navigate");
    if (scrolled > 60)
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