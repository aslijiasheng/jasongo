//遮罩
function LeeShowLoading(){
	var overlayDiv = jQuery('<div></div>');
	var loadingDiv = jQuery('<div></div>');
	jQuery(overlayDiv).addClass('loading-indicator-overlay');
	jQuery(overlayDiv).css({top:0,left:0});
	jQuery(overlayDiv).css('display', 'none');
	jQuery(overlayDiv).css('background-color','Gray');
	jQuery(overlayDiv).css('position','absolute'); 
	jQuery(overlayDiv).css('z-index', '10000');
	jQuery(document.body).append(overlayDiv); //新建遮罩层
	jQuery(loadingDiv).css('display', 'none');
	jQuery(loadingDiv).css('position','absolute'); 
	jQuery(loadingDiv).addClass('loading-indicator');
	jQuery(loadingDiv).css('z-index', '10001');
	jQuery(document.body).append(loadingDiv);//新建loading层

	
	var ch=document.documentElement.clientHeight;//屏幕的高度 
	var bH=$("html").height(); 
	if (ch>bH){
		bH=ch;
	}
	var cw=document.documentElement.clientWidth;//屏幕的宽度 
    var bW=$("html").width();
	if (cw>bW){
		bW=cw;
	}
    jQuery(overlayDiv).css({width:bW,height:bH,display:"block"});
	var objW=parseInt(jQuery(loadingDiv).width());//浮动对象的高度
	var objL=(Number(cw)-Number(objW))/2;
	var objH=parseInt(jQuery(loadingDiv).height());//浮动对象的高度
	var objT=(Number(ch)-Number(objH))/2;
	jQuery(loadingDiv).css({top:objT,left:objL,display:"block"});

	$(window).scroll(function(){resetBg(overlayDiv,loadingDiv)});//滚动条移动的时候刷新值
    $(window).resize(function(){resetBg(overlayDiv,loadingDiv)});//窗口变化的时候刷新值
}

function resetBg(overlayDiv,loadingDiv){ 
	var fullbg=$(overlayDiv).css("display"); 
	if(fullbg=="block"){ 
		var ch2=document.documentElement.clientHeight;//屏幕的高度 
		var bH2=$("html").height();
		if (ch2>bH2){
			bH2=ch2;
		}
		var cw2=document.documentElement.clientWidth;//屏幕的宽度 
		var bW2=$("html").width();
		if (cw2>bW2){
			bW2=cw2;
		}
		$(overlayDiv).css({width:bW2,height:bH2});
		var sl2=document.documentElement.scrollLeft;//滚动条距左边的距离 
		var cw2=document.documentElement.clientWidth;//屏幕的宽度 
		var objW2=parseInt(jQuery(loadingDiv).width());//浮动对象的高度
		var objL2=Number(sl2)+(Number(cw2)-Number(objW2))/2;
		var st2=document.documentElement.scrollTop;//滚动条距顶部的距离 
		var objH2=parseInt(jQuery(loadingDiv).height());//浮动对象的高度
		var objT2=Number(st2)+(Number(ch2)-Number(objH2))/2;
		$(loadingDiv).css({top:objT2,left:objL2});
	}
}

function LeeHideLoading(){ 
	$(document.body).find(".loading-indicator-overlay").remove();
	$(document.body).find(".loading-indicator.").remove();
}
