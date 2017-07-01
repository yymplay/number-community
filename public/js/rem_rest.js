;(function(win) {
    var docElem = win.document.documentElement,
    	timer=null;
    function refreshRem() {
        var width = docElem.getBoundingClientRect().width;
       
        if (width > 900) {width=1000;}         	   
        var rem = width / 10;
        localStorage.setItem("c",width);
        docElem.style.fontSize = rem + 'px';
    }
    
    win.addEventListener('resize', function(){
        clearTimeout(timer);
        timer = setTimeout(refreshRem, 300);
    }, false);
    win.addEventListener('pageshow', function(e){
        if (e.persisted) {
            clearTimeout(timer);
            timer = setTimeout(refreshRem, 300);
        }
    }, false);
    
    refreshRem();   
  
})(window);