<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/8/3
 * TimeController.class: 16:57
 */

namespace Admin\Controller;


class CommentExtController extends CommonController
{
    public function index() {
        $data = array();
        $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $pageSize = $_REQUEST['pageSize'] ? $_REQUEST['pageSize'] : 8;

        $commentCount = D("CommentExt")->getCommentCount($data);
        //获取评论
        $comment = D("CommentExt")->getComment($data,$page,$pageSize);
        //根据id找到相应的名字
        foreach ($comment as $key=>$value){
            foreach ($value as $key2=>$value2){
                //echo $key2."=>".$value2;
                if($key2=='stu_id'){
                    //此时value2即为stu_id
                    //获取学生的名字
                    $studentName[$value2] = D("Student")->findStudentName($value2);
                    $comment[$key][$key2] = $studentName[$value2];
                }
                if($key2=='class_id'){
                    $list = D("Class")->findClassName($value2);
                    $classGrade[$value2] = $list['grade'];
                    $classSubject[$value2] = $list['subject'];
                    $comment[$key][$key2] = $classGrade[$value2].'-'.$classSubject[$value2];
                }
                if($key2=='tea_id'){
                    $teacherName[$value2] = D("Admin")->findTeacherName($value2);
                    $comment[$key][$key2] = $teacherName[$value2];
                }
            }
        }

        $res = new \Think\Page($commentCount,$pageSize);
        $pageRes = $res->show();
        $this->assign('pageRes', $pageRes);
        $this->assign('comment' , $comment);
        $this->display();
    }

    public function  listorder() {
        $listorder = $_POST['listorder'];
        $jumpUrl = $_SERVER['HTTP_REFERER'];
        $errors = array();
        if ($listorder) {
            try {
                foreach ($listorder as $commentID => $value) {
                    //执行更新
                    $id = D("CommentExt")->updateCommentListorder($commentID, $value);
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

    public function setStatus(){
        try{
            if($_POST){
                $id = $_POST['id'];
                $status = $_POST['status'];
                //执行数据更新操作
                $res = D("CommentExt")->updateStatusById($id,$status);
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
}