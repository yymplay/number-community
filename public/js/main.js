var numfurt={	
	    "h":document.documentElement.clientHeight,//当前的页面的可视高度
		"succ":true,//支付是否成功的回调
		"current":0, //当前显示的是第几也
		"bn":$("#btn_next"),//下一步按钮
		"bnh":$("#btn_next_ht"), //合同下一步按钮
		"bpay":$("#btn_par"), //合同下一步按钮
		"content":$(".content"),//容器界面
		"cp":$(".content page"),//容器里面的的容器
		
		//点击切换页面按钮
		"dianji1":function(){
            	$(".page1").hide();
            	$(".page2").show();
            	$("html ,body").scrollTop(0);
		},
		"dianji2":function(){           	
            	$(".page2").hide();
            	$(".page3").show();          			
		},
		"dianji3":function(){           
            	$(".page3").hide();
            	$(".page4").show();
            	$("html ,body").scrollTop(0);
		},		
		"yanzhen":function(){

		},
		"qianming":function(){
			var wp = new WritingPad();
			wp.init();
		}
}
