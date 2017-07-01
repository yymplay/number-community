/**
 * 添加按钮操作
 */
$("#button-add").click(function(){
    var url = SCOPE.add_url;
    window.location.href=url;
});

/**
 * 提交form表单操作
 */
$("#singcms-button-submit").click(function(){

    var data = $("#singcms-form").serializeArray();
    postData = {};
    $(data).each(function(i){

        if(this.name=='pris[]'){
            if(!postData[this.name])
                postData[this.name]='';
            postData[this.name] += this.value+',';
        }else{
            postData[this.name] = this.value;
        }
       
    });

    console.log(postData);
    // 将获取到的数据post给服务器
    url = SCOPE.save_url;
    jump_url = SCOPE.jump_url;
    $.post(url,postData,function(result){
        if(result.status == 1) {
            //成功
            return dialog.success(result.info,jump_url);
        }else if(result.status == 0) {
            // 失败
            return dialog.error(result.info);
        }
    },"JSON");
});
$("#singcms-button-submit-user-add").click(function(){

    var data = $("#singcms-form").serializeArray();
    postData = {};
    $(data).each(function(i){

        if(this.name=='pris[]'){
            if(!postData[this.name])
                postData[this.name]='';
            postData[this.name] += this.value+',';
        }else{
            postData[this.name] = this.value;
        }
       
    });

    console.log(postData);
    // 将获取到的数据post给服务器
    url = SCOPE.save_url;
    jump_url = SCOPE.jump_url;
    $.post(url,postData,function(result){
        if(result.status == 1) {
            //成功
            return dialog.success(result.info,jump_url+'/member_id/'+result.data.id);
        }else if(result.status == 0) {
            // 失败
            return dialog.error(result.info);
        }
    },"JSON");
});
$("#singcms-button-submit2").click(function(){
    var data = new FormData($('#singcms-form')[0]);
    $.ajax({
        type: "post",
        url: SCOPE.save_url,
        //data: {da:document.getElementById("preview").src},
        data:data,
        dataType:"json",

        //报错请加入以下三行，则ajax提交无问题
        cache: false,  
            processData: false,  
            contentType: false,
        success: function(result){
            if(result.status == 1) {
                //成功
                return dialog.success(result.info,SCOPE.jump_url+'/member_id/'+result.data.id);
            }else if(result.status == 0) {
                // 失败
                return dialog.error(result.info);
            }
        }
    });
});
$("#setinfo-submit").click(function(){
    var data = new FormData($('#singcms-form')[0]);
    $.ajax({
        type: "post",
        url: SCOPE.save_url,
        //data: {da:document.getElementById("preview").src},
        data:data,
        dataType:"json",

        //报错请加入以下三行，则ajax提交无问题
        cache: false,  
            processData: false,  
            contentType: false,
        success: function(result){
            if(result.status == 1) {
                //成功
                return dialog.success(result.info,SCOPE.jump_url);
            }else if(result.status == 0) {
                // 失败
                return dialog.error(result.info);
            }
        }
    });
});

$("#province").change(function(){


    // 将获取到的数据post给服务器
    url = 'index.php?s=admin/common/getcity/province/'+$("#province").val();

    $.post(url,{},function(result){
        if(result.status == 1) {
                //成功
                //清除city和 area
                $("#city").empty();
                $("#area").empty();
                $("#community_id").empty();
                $("#city").append("<option value=''>---</option>");
                $("#area").append("<option value=''>---</option>");
                $("#community_id").append("<option value=''>---</option>");
                $(result.data.city).each(function(i){
                    $("#city").append("<option value='"+this+"'>"+this+"</option>");
               });
        }else if(result.status == 0) {

        }
    },"JSON");
});
$("#city").change(function(){
  

    // 将获取到的数据post给服务器
    url = 'index.php?s=admin/common/getarea/city/'+$("#province").val()+'_'+$("#city").val();

    $.post(url,{},function(result){
        if(result.status == 1) {
                //成功
                //清除 area

                $("#area").empty();
                $("#community_id").empty();
                $("#area").append("<option value=''>---</option>");
                $("#community_id").append("<option value=''>---</option>");
                $(result.data.city).each(function(i){
                    $("#area").append("<option value='"+this+"'>"+this+"</option>");
               });
        }else if(result.status == 0) {

        }
    },"JSON");
});
$("#area").change(function(){

    // 将获取到的数据post给服务器
    url = 'index.php?s=admin/common/getcommunity/province/'+$("#province").val()+'/city/'+$("#city").val()+'/area/'+$("#area").val();

    $.post(url,{},function(result){
        if(result.status == 1) {
                //成功
                //清除 area

                $("#community_id").empty();

                $(result.data.city).each(function(i){
                    console.log(this);
                    $("#community_id").append("<option value='"+this.community_id+"'>"+this.community_id+"</option>");
               });
        }else if(result.status == 0) {

        }
    },"JSON");
});
/*
编辑模型
 */
$('.singcms-table #singcms-edit').on('click',function(){
    var id = $(this).attr('attr-id');
    var url = SCOPE.edit_url + '/id/'+id;
    window.location.href=url;
});
$('.singcms-table #singcms-look').on('click',function(){
    var id = $(this).attr('attr-id');
    var url = SCOPE.look_url + '/member_id/'+id;
    window.location.href=url;
});
$('.singcms-table #user-edit').on('click',function(){
    var id = $(this).attr('attr-id');
    var url = SCOPE.edit_url + '/member_id/'+id;
    window.location.href=url;
});
/**
 * 删除操作JS
 */
$('.singcms-table #singcms-delete').on('click',function(){
    var id = $(this).attr('attr-id');
    var a = $(this).attr("attr-a");
    var message = $(this).attr("attr-message");
    var url = SCOPE.set_status_url;

    data = {};
    data['id'] = id;

    layer.open({
        type : 0,
        title : '是否提交？',
        btn: ['yes', 'no'],
        icon : 3,
        closeBtn : 2,
        content: "是否确定"+message,
        scrollbar: true,
        yes: function(){
            // 执行相关跳转
            todelete(url, data);
        },

    });

});
function todelete(url, data) {
    $.post(
        url,
        data,
        function(s){
            if(s.status == 1) {
                return dialog.success(s.info,'');
                // 跳转到相关页面
            }else {
                return dialog.error(s.info);
            }
        }
    ,"JSON");
}

/**
 * 排序操作 
 */
$('#button-listorder').click(function() {
    // 获取 listorder内容
    var data = $("#singcms-listorder").serializeArray();
    postData = {};
    $(data).each(function(i){
       postData[this.name] = this.value;
    });
    console.log(data);
    var url = SCOPE.listorder_url;
    $.post(url,postData,function(result){
        if(result.status == 1) {
            //成功
            return dialog.success(result.info,result['data']['jump_url']);
        }else if(result.status == 0) {
            // 失败
            return dialog.error(result.info,result['data']['jump_url']);
        }
    },"JSON");
});

/**
 * 修改状态
 */
$('.singcms-table #singcms-on-off').on('click', function(){

    var id = $(this).attr('attr-id');
    var status = $(this).attr("attr-status");
    var url = SCOPE.set_status_url;

    data = {};
    data['id'] = id;
    data['status'] = status;

    layer.open({
        type : 0,
        title : '是否提交？',
        btn: ['yes', 'no'],
        icon : 3,
        closeBtn : 2,
        content: "是否确定更改状态",
        scrollbar: true,
        yes: function(){
            // 执行相关跳转
            todelete(url, data);
        },

    });

});

/**
 * 推送JS相关
 */
$("#singcms-push").click(function(){
    var id = $("#select-push").val();
    if(id==0) {
        return dialog.error("请选择推荐位");
    }
    push = {};
    postData = {};
    $("input[name='pushcheck']:checked").each(function(i){
        push[i] = $(this).val();
    });

    postData['push'] = push;
    postData['position_id']  =  id;
    //console.log(postData);return;
    var url = SCOPE.push_url;
    $.post(url, postData, function(result){
        if(result.status == 1) {
            // TODO
            return dialog.success(result.message,result['data']['jump_url']);
        }
        if(result.status == 0) {
            // TODO
            return dialog.error(result.message);
        }
    },"json");

});
$("#register-get-checkcode").click(function(){
    var tel = $("#account").val();
    var status=false;
    var re=/^1\d{10}$/;
    if(tel==''){
        return dialog.error("手机号不为空！!!!!");
    }
    if(!re.test(tel)){
        return dialog.error("手机号格式不正确！");
    }

    postData = {};
    postData['account']=tel;


    console.log(postData);
    // 将获取到的数据post给服务器
    var url = SCOPE.sendmsg_url;
    $.post(url,postData,function(result){
        if(result.status == 1) {
            //成功
            return dialog.noredirect(result.info);
        }else if(result.status == 0) {
            // 失败
            return dialog.error(result.info);
        }
    },"JSON");
});