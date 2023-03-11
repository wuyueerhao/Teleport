function navList(id) {
    var $obj = $("#navi_mobile"), $item = $("#J_nav_" + id);
    $item.addClass("on").parent().removeClass("none").parent().addClass("selected");
    $obj.find("h4").hover(function () {
        $(this).addClass("hover");
    }, function () {
        $(this).removeClass("hover");
    });
	$obj.find("p").hover(function () {
        if ($(this).hasClass("on")) { return; }
        $(this).addClass("hover");
    }, function () {
        if ($(this).hasClass("on")) { return; }
        $(this).removeClass("hover");
    });
    $obj.find("h4").click(function () {
        var $div = $(this).siblings(".list-item");
        if ($(this).parent().hasClass("selected")) {
            $div.slideUp(600);
            $(this).parent().removeClass("selected");
        }
        if ($div.is(":hidden")) {
            $("#navi_mobile li").find(".list-item").slideUp(600);
            $("#navi_mobile li").removeClass("selected");
            $(this).parent().addClass("selected");
            $div.slideDown(600);

        } else {
            $div.slideUp(600);
        }
    });
}

$(document).ready(function() {
        //首先将#gotop隐藏
        $("#gotop").hide();
        //当滚动条的位置处于距顶部100像素以下时，跳转链接出现，否则消失
        $(function() {
            $(window).scroll(function() {
                if ($(window).scrollTop() > 100) {
                    $("#gotop").fadeIn(1500);
                } else {
                    $("#gotop").fadeOut(1500);
                }
            });
            //当点击跳转链接后，回到页面顶部位置
            $("#gotop").click(function() {
                $('body,html').animate({
                    scrollTop: 0
                },
                1000);
                return false;
            });
        });
    });