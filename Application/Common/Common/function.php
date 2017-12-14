<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/7/22
 * TimeController.class: 13:05
 */

function show($status,$message,$data=array()){
    $result = array(
        'status' => $status,
        'message' => $message,
        'data' => $data,
    );
    exit(json_encode($result));
}

function getMenuStatus($type) {
    return $type == 1 ? '正常':'关闭';
}

function getTeaType($type) {
    if($type==1){
        return '审核通过';
    }elseif ($type==0){
        return '审核不通过';
    }else{
        return '未审核';
    }

}

function getCommentType($type){
    if($type==1){
        return '未审核';
    }elseif($type==0){
        return '审核未通过';
    }elseif ($type==2){
        return '审核通过';
    }else{
        return '未知状态';
    }

}

function getClassType($type){
    if($type==1){
        return '审核通过';
    }elseif ($type==0){
        return '未审核';
    }elseif ($type==-1){
        return '已完结';
    }elseif ($type==-2){
        return '审核未通过';
    }else{
        return '未知状态';
    }
}

function getActive($navc) {
    $c = strtolower(CONTROLLER_NAME);
    $a = strtolower(ACTION_NAME);
    //strtolower把所有字符转化为小写
    if(strtolower($navc) == $c && 'index'==$a) {
        return 'class="active"';
    }
    return '';
}

function getActive1($nava) {
    $a = strtolower(ACTION_NAME);
    if(strtolower($nava) == $a) {
        return 'class="active"';
    }
    return '';
}

function isAdmin($name) {
    $a = strtolower(ACTION_NAME);
    if(strtolower($name) != 'admin') {
        return 'style="display:none"';
    }
    return '';
}

function getLoginUsername() {
    return $_SESSION['adminUser']['tea_name'] ? $_SESSION['adminUser']['tea_name'] : '';
}

function getStatus($status){
    return $status == 1 ? '审核未通过':'审核通过';
}

function getStuTea($type){
    return $type == 0 ? '已结课' : '未结课';
}

function getMethod($method) {
    return $method==1?'到店':'上门';
}

function getMessageType($type) {
    if($type==-1){
        return '待审核';
    }elseif ($type==1){
        return '审核通过';
    }elseif ($type==-2){
        return '审核失败';
    }else{
        return '未知状态';
    }
}

function boardType($type) {
    if($type==2){
        return '老师';
    }elseif ($type==1){
        return '学生';
    }else{
        return '未知';
    }
}
//小程序用
function api_return($data=array()){
    echo(json_encode($data));
}