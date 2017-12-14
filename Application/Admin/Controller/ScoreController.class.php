<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/7/23
 * TimeController.class: 16:35
 */

namespace Admin\Controller;
use Think\Controller;


class ScoreController extends CommonController
{
    public function index()
    {
        $user = session('adminUser');
        $userName = $user['tea_name'];

        //$data用于储存搜索的数据
        $data = array();
        if($userName!='admin'){
            $data = array('tea' => $userName);
        }
        //获取第几页
        $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
        //获取分页的条数
        $pageSize = $_REQUEST['pageSize'] ? $_REQUEST['pageSize'] : 8;
        //print_r($data);exit;
        $score = D("Score")->getScore($data, $page, $pageSize);
        //获取成绩总数
        $scoreCount = D("Score")->getScoreCount($data);
        //获取学生成绩


        //根据id获取名字
        foreach ($score as $key=>$value){
            foreach ($value as $key2=>$value2){
                //echo $key2."=>".$value2;
                if($key2=='stu_id'){
                    //此时value2即为stu_id
                    //获取学生的名字
                    $studentName[$value2] = D("Student")->findStudentName($value2);
                    $score[$key][$key2] = $studentName[$value2];
                }
                if($key2=='class_id'){
                    $list = D("Class")->findClassName($value2);
                    $classGrade[$value2] = $list['grade'];
                    $classSubject[$value2] = $list['subject'];
                    $score[$key][$key2] = $classGrade[$value2].'-'.$classSubject[$value2];
                }
                if($key2=='tea_id'){
                    $teacherName[$value2] = D("Admin")->findTeacherName($value2);
                    $score[$key][$key2] = $teacherName[$value2];
                }
            }
        }


        //使用tp自带分页功能
        $res = new \Think\Page($scoreCount, $pageSize);
        $pageRes = $res->show();
        $this->assign('pageRes', $pageRes);

        $this->assign('score', $score);
        $this->display();
    }

    public function add()
    {
        if ($_POST) {
            //判断
            if($_POST['test']){
                if (!isset($_POST['class']) || !$_POST['class']) {
                    return show(0, '课程名不能为空');
                }
                if (!isset($_POST['stu']) || !$_POST['stu']) {
                    return show(0, '学生名不能为空');
                }
                if (!isset($_POST['score']) || !$_POST['score']) {
                    return show(0, '分数不能为空');
                }
                if (!isset($_POST['score_remark']) || !$_POST['score_remark']) {
                    return show(0, '分数备注不能为空');
                }
                //有id则证明是在修改，此时跳转到save方法
                if ($_POST['id']) {
                    return $this->save($_POST);
                }
                $user = session('adminUser');
                $userName = $user['tea_name'];
                $_POST['tea'] = $userName;
                $id = D("Score")->insert($_POST);
                if ($id) {
                    return show(1, '新增成功');
                }
                return show(0, '新增失败');
            }


            if($_POST['select']){
                $classId = $_POST['classId'];
                $res = D('ScoreExt')->getStuIdByClass($classId);
                foreach ($res as $key => $value){
                    foreach ($value as $key2 => $value2){
                        if($key2=='stu_id'){
                            //此时value2即为stu_id
                            //获取学生的名字
                            $studentName[] = D("Student")->findStudentName($value2);
                        }
                    }
                }
                if($res){
                    return show(1,'有学生数据',$studentName);
                }else{
                    return show(0,'没有学生数据');
                }

            }
        } else {
            //先根据登陆者的资料获取登陆者的姓名和ID
            $user = session('adminUser');
            $userName = $user['tea_name'];
            $userID = $user['tea_id'];
            //再根据登陆者的ID在class表获取所开的课程
            $class = D("Class")->getClassByTeaId($userID);
            $this->assign('class', $class);
            $this->display();
        }
    }

    public function edit()
    {
        //先根据登陆者的资料获取登陆者的姓名和ID
        $user = session('adminUser');
        $userName = $user['tea_name'];
        $userID = $user['tea_id'];
        //再根据登陆者的ID在class表获取所开的课程
        $class = D("Class")->getClassByTeaId($userID);
        $this->assign('class', $class);
        $scoreId = $_GET['id'];
        $score = D('Score')->find($scoreId);
        $this->assign('score', $score);
        $this->display();

    }

    public function save($data)
    {
        $scoreId = $data['id'];
        unset($data['id']);
        $id = D("Score")->updateScoreById($scoreId, $data);
        if ($id === false) {
            return show(0, '更新失败');
        }
        return show(1, '更新成功');
    }

    public function setStatus(){
        try{
            if($_POST){
                $id = $_POST['id'];
                $status = $_POST['status'];
                //执行数据更新操作
                $res = D("Score")->updateStatusById($id,$status);
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
                foreach ($listorder as $scoreId => $value) {
                    //执行更新
                    $id = D("Score")->updateListorderById($scoreId, $value);
                    if ($id === false) {
                        $errors[] = $scoreId;
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
        return show(0,'排序失败',array('jump_url'=>$jumpUrl));
    }

    public function getStuByClass($classId)
    {


    }
}