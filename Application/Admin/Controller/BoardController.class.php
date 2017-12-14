<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/8/3
 * TimeController.class: 16:32
 */

namespace Admin\Controller;


class BoardController extends CommonController
{
    public function index(){
        $data = array();
        $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $pageSize = $_REQUEST['pageSize'] ? $_REQUEST['pageSize'] : 8;

        $commentCount = D("Board")->getCommentCount($data);
        //获取评论
        $comment = D("Board")->getComment($data,$page,$pageSize);

        $res = new \Think\Page($commentCount,$pageSize);
        $pageRes = $res->show();
        $this->assign('pageRes', $pageRes);
        $this->assign('comment' , $comment);
        $this->display();
    }

    public function setStatus(){
        try{
            if($_POST){
                $id = $_POST['id'];
                if($_POST['message_type']){
                    $type = $_POST['message_type'];
                    $res = D("Board")->update1StatusById($id,$type);
                }
                if($_POST['status']){
                    $status = $_POST['status'];
                    //执行数据更新操作
                    $res = D("Board")->updateStatusById($id,$status);
                }
                if($res){
                    return show(1,'操作成功');
                }else{
                    return show(0,'操作失败');
                }
            }
        }catch (Exception $e){
            return show(0,$e->getMessage());
        }
        return show(0,'没有提交的数据');
    }


    public function  listorder() {
        $listorder = $_POST['listorder'];
        $jumpUrl = $_SERVER['HTTP_REFERER'];
        $errors = array();
        if ($listorder) {
            try {
                foreach ($listorder as $commentID => $value) {
                    //执行更新
                    $id = D("Board")->updateCommentListorder($commentID, $value);
                    if ($id === false) {
                        $errors[] = $commentID;
                    }
                }
            }catch (Exception $e){
                return show(0,$e->getMessage(),array('jump_url'=>$jumpUrl));
            }
            if($errors) {
                return show(0,'排序失败-'.implode(',',$errors),array('jump_url'=>$jumpUrl));
            }
            return show(1,'排序成功',array('jump_url'=>$jumpUrl));
        }
        return show(0,'排序数据失败',array('jump_url'=>$jumpUrl));
    }
}