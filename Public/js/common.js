/**
 * Created by rehellinen on 2017/7/26.
 */
//添加按钮
function skip() {
    window.location.href = SCOPE.skip_url;
}

function getOption() {
    var value = $("#select").val();
    data = {};
    data['classId'] = value;
    data['select'] = "select";
    var url = "admin.php?c=score&a=add";
    $.post(url, data, function (result) {
        if(result.status===1){
            var arr = result.data;
            var length = arr.length;
            var htmlChange = "";
            for(var i=0; i<length; i++){
                htmlChange = htmlChange + '<option name="stu" value="' + arr[i] +'">' + arr[i] + '</option>';
            }
            $('#rehe-select-op').after(htmlChange);
        }
        if(result.status===0){
            return dialog.error(result.message);
        }
    },"JSON");
}

function formSubmit() {
    var data = $("#rehe-form").serializeArray();
    postData = {};
    $(data).each(function (i){
        postData[this.name] = this.value;
    });
    //利用ajax把获取到的数据post给服务器
    url = SCOPE.add_url;
    $.post(url,postData,function (result) {
        if(result.status===1) {
            //成功插入
            return dialog.success(result.message,SCOPE.success_url);
        }else if(result.status===0){
            //插入失败
            return dialog.error(result.message);
        }

    },"JSON");
}

$('.table-striped #rehe-edit').on('click',function () {
    var id = $(this).attr('attr-id');
    var url = SCOPE.edit_url + '&id=' +id;
    window.location.href=url;
});

$('.table-striped #rehe-delete').on('click',function () {
    var id = $(this).attr('attr-id');
    var message = $(this).attr('attr-message');
    var url = SCOPE.set_status_url;
    data = {};
    data['id'] = id;
    data['status'] = -1;

    layer.open({
        type : 0,
        title : '是否提交? ',
        btn : ['yes','no'],
        icon : 3,
        closeBtn : 2,
        content : "是否确定"+message ,
        scrollbar : true,
        yes : function () {
            //执行相关跳转
            todelete(url,data);
        }
    });
});

function todelete(url,data) {
    $.post(url,data,function (result) {
            if(result.status === 1) {
                return dialog.success(result.message,'');
            }else{
                return dialog.error(result.message);
            }
        }
        ,"JSON");
}

$('.table-striped #button-listorder').click(function () {
    //获取listorder内容
    var data = $("#singcms-listorder").serializeArray();
    postDate={};
    $(data).each(function (i) {
        postDate[this.name] = this.value;
    });
    var url = SCOPE.listorder_url;
    $.post(url,postDate,function (result) {
        if(result.status ==1) {
            return dialog.success(result.message,result['data']['jump_url']);
        }else if(result.status == 0) {
            return dialog.error(result.message,result['data']['jump_url']);
        }
    },"JSON");
});

function listorder() {
    var data = $("#singcms-listorder").serializeArray();
    postDate={};
    $(data).each(function (i) {
        postDate[this.name] = this.value;
    });
    var url = SCOPE.listorder_url;
    $.post(url,postDate,function (result) {
        if(result.status ==1) {
            return dialog.success(result.message,result['data']['jump_url']);
        }else if(result.status == 0) {
            return dialog.error(result.message,result['data']['jump_url']);
        }
    },"JSON");
}

$('.singcms-table #singcms-on-off').on('click',function () {
    var id = $(this).attr('attr-id');
    var type = $(this).attr('attr-type');
    var status = $(this).attr('attr-status');
    var url = SCOPE.set_status_url;
    data = {};
    data['id'] = id;
    data['type'] = type;
    data['status'] = status;

    layer.open({
        type : 0,
        title : '是否提交? ',
        btn : ['yes','no'],
        icon : 3,
        closeBtn : 2,
        content : "是否确定更改状态",
        scrollbar : true,
        yes : function () {
            //执行相关跳转
            todelete(url,data);
        },
    });
});


$('.singcms-table #tea-on-off').on('click',function () {
    var id = $(this).attr('attr-id');
    var url = SCOPE.set_status_url;
    data = {};
    data['id'] = id;

    layer.open({
        type : 0,
        title : '审核 ',
        btn : ['通过','不通过'],
        closeBtn : 2,
        content : "点击下面按钮审核",
        scrollbar : true,
        yes : function () {
            data['tea_type'] = 1;
            data['type'] = 2;
            todelete(url,data);
        },
        btn2 : function () {
            alert(1);
            data['tea_type'] = 0;
            data['type'] = 0;
            todelete(url,data);
        }
    });
});

$('.singcms-table #comment-on-off').on('click',function () {
    var id = $(this).attr('attr-id');
    var url = SCOPE.set_status_url;
    data = {};
    data['id'] = id;

    layer.open({
        type : 0,
        title : '审核 ',
        btn : ['通过','不通过'],
        closeBtn : 2,
        content : "点击下面按钮审核",
        scrollbar : true,
        yes : function () {
            data['status'] = 2;
            todelete(url,data);
        },
        btn2 : function () {
            data['status'] = 0;
            todelete(url,data);
            return false;
        }
    });
});

$('.singcms-table #comment1-on-off').on('click',function () {
    var id = $(this).attr('attr-id');
    var url = SCOPE.set_status_url;
    data = {};
    data['id'] = id;

    layer.open({
        type : 0,
        title : '审核 ',
        btn : ['通过','不通过'],
        closeBtn : 2,
        content : "点击下面按钮审核",
        scrollbar : true,
        yes : function () {
            data['message_type'] = 1;
            todelete(url,data);
        },
        btn2 : function () {
            data['message_type'] = -2;
            todelete(url,data);
            return false;
        }
    });
});

$('.singcms-table #class-on-off').on('click',function () {
    var id = $(this).attr('attr-id');
    var url = SCOPE.set_status_url;
    data = {};
    data['id'] = id;

    layer.open({
        type : 0,
        title : '审核 ',
        btn : ['通过','不通过'],
        closeBtn : 2,
        content : "点击下面按钮审核",
        scrollbar : true,
        yes : function () {
            data['tea_type'] = 1;
            todelete(url,data);
        },
        btn2 : function () {
            data['tea_type'] = -2;
            todelete(url,data);
            return false;
        }
    });
});



$('.table-striped #rehe-detail').on('click',function () {
    if($(this).attr('attr-teaid')){
        var skip_url = "admin.php?c=index&a=content";
        id = {};
        id['tea_id'] = $(this).attr('attr-teaid');
        window.location.href = skip_url+ '&tea_id=' +id['tea_id'];
    }

    if($(this).attr('attr-scoreid')){
        var skip_url = "admin.php?c=index&a=content";
        id = {};
        id['score_id'] = $(this).attr('attr-scoreid');
        window.location.href = skip_url+ '&score_id=' +id['score_id'];
    }

    if($(this).attr('attr-commentid')){
        var skip_url = "admin.php?c=index&a=content";
        id = {};
        id['comment_id'] = $(this).attr('attr-commentid');
        window.location.href = skip_url+ '&comment_id=' +id['comment_id'];
    }

    if($(this).attr('attr-commentid2')){
        var skip_url = "admin.php?c=index&a=content";
        id = {};
        id['comment_id'] = $(this).attr('attr-commentid2');
        window.location.href = skip_url+ '&comment_id2=' +id['comment_id'];
    }

    if($(this).attr('attr-commentid3')){
        var skip_url = "admin.php?c=index&a=content";
        id = {};
        id['comment_id'] = $(this).attr('attr-commentid3');
        window.location.href = skip_url+ '&comment_id3=' +id['comment_id'];
    }

    if($(this).attr('attr-classid')){
        var skip_url = "admin.php?c=index&a=content";
        id = {};
        id['class_id'] = $(this).attr('attr-classid');
        window.location.href = skip_url+ '&class_id=' +id['class_id'];
    }

});