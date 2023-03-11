var scroll_top={
	drawCircle:function(c,n,e){
		var d=jQuery(c).width();
		var k=jQuery(c).height();
		var i=parseInt(d/2.2);
		var g=d;
		var m=g/2;
		var f=jQuery(c)[0];c=c.split("#");
		var l=f.getContext("2d");
		var h=null;
		var b=Math.PI*2;
		var a=Math.PI/2;l.clearRect(0,0,d,k);l.beginPath();l.strokeStyle=e;l.lineCap="square";l.closePath();l.fill();l.lineWidth=3;h=l.getImageData(0,0,g,g);
		var j=function(o,p){
			p.putImageData(h,0,0);
			p.beginPath();
			p.arc(m,m,i,-(a),((b)*o)-a,false);
			p.stroke()
		};
			j(n/100,l)
	},
	backToTop:function(a){
		a.click(function(){
			jQuery("body,html").animate({scrollTop:0},800);
			return false
		}		
	)},
	scrollHook:function(b,a){
		a=a?a:"#000000";
		b.scroll(function(){
			var d=(jQuery(document).height()-jQuery(window).height()),g=b,f=jQuery(".per"),c=0;
			defaultScroll=g.scrollTop();
			c=parseInt((defaultScroll/d)*100);
			var e=jQuery("#backtoTop");
			if(e.length>0){
				if(g.scrollTop()>200){
					e.addClass("button--show")
				}else{
					e.removeClass("button--show")
				}
				f.attr("data-percent",c);
				scroll_top.drawCircle("#backtoTopCanvas",c,a)
			}
		})
	}
};

jQuery(document).ready(function(){
	if(jQuery(document).width()>799){
		jQuery("body").append('<div id="backtoTop" data-action="gototop"><canvas id="backtoTopCanvas" width="48" height="48"></canvas><div class="per"></div></div>');
		var a=scroll_top;a.backToTop(jQuery("#backtoTop"));
		a.scrollHook(jQuery(window),"#333333")
	}
});