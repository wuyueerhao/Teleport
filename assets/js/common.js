jQuery(document).ready(function($) {	
	//菜单伸缩
	$(".menu-button").click(function(){
		$("#slide-menu").slideToggle();
	});
	Comm();
});
function Comm(){
	/* comments */
	if($("#comments")[0]) {
		var $rChange = $("#rChange");
		var $rClose = $("#rClose");
		var $author_info = $("#author-info");
		var $author = $("#author");
		var $author_email = $("#email");
		var $comment = $("#comment")
		var sync_commenter_info = new Mutex();
		$rChange.click(function() {
			if(sync_commenter_info.isLock) {
				sync_commenter_info.lock();
				$author_info.slideDown().queue(function(next) {
					$rChange.hide();
					$rClose.show();
					sync_commenter_info.unLock();
					next();
				});
			}
		});
		$rClose.click(function() {
			if(sync_commenter_info.isLock) {
				sync_commenter_info.lock();
				if(check_commenter($author) || check_commenter($author_email)) {
					sync_commenter_info.unLock();
					return
				}
				$author_info.slideUp().queue(function(next) {
					$rClose.hide();
					$rChange.show();
					$("#welcome-back strong").text($author.val());
					sync_commenter_info.unLock();
					next();
				});
			}
		});
		$("#respond .kaomoji li").click(function() {
			$comment.focus();
			$comment.val($comment.val() + " " + $(this).html() + " ");
		});
		$("#comments .comment").hover(function() {
			$(".reply>a", this).fadeIn("fast");
		}, 
		function() {
			$(".reply>a", this).fadeOut("fast");
		});	
		var check_commenter = function(target) {
			if(target.val() == "") {
				scrollToTarget($("#welcome-back")).queue(function(next) {
					next();
				});
				target.focus();
				return true;
			}
			return false;
		}
	}
	if($("#content.archives")[0]) {
		$("#content.archives .selector").change(function(){
			currentVal = $(this).val();
			if(currentVal == "all") {
				$(".monthly-archives>.month:hidden").slideDown();
			} else {
				$(".monthly-archives>.month:not(:hidden):not(.year-" + currentVal + ")").slideUp().queue(function(next) {
					$(".monthly-archives>.year-" + currentVal + ":hidden").slideDown();
					next();
				});
			}
		})
	}
	var scrollToTarget = function(target) {
		return $("body").animate({
			scrollTop: target.position().top
		}, 200);
	}
	//二级菜单
	$('#main-nav li').hover(function() {
		$('ul', this).slideDown(300)
	},
	function() {
		$('ul', this).slideUp(300)
	});
			
	 //评论区Tab切换
	$('.comments-tab span').click(function(){ 
		$(".comments-tab span").eq($(this).index()).addClass("active").siblings().removeClass('active');//removeClass就是删除当前其他类；只有当前对象有addClass("selected")；siblings()意思就是当前对象的同级元素，removeClass就是删除； 
		$("#comments > .showlist").addClass('hide').eq($('.comments-tab span').index(this)).removeClass("hide"); 
	}); 
	
}
function Mutex() {
	this.state = 0;
	this.lock = function() {
		this.state = 1;
	}
	this.unLock = function() {
		this.state = 0;
	}
	this.isLock = function() {
		return this.state;
	}
}






