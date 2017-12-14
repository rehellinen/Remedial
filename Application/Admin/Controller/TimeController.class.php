<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/7
 * TimeController.class: 13:50
 */

namespace Admin\Controller;


use Think\Controller;

class TimeController extends Controller
{
    public function index()
    {
        $data = array();
        $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $pageSize = $_REQUEST['pageSize'] ? $_REQUEST['pageSize'] : 8;
        $score= D("ScoreExt")->getAll($data, $page, $pageSize);
        $scoreCount = D("ScoreExt")->getAllCount();
        //print_r($score);exit;
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
        $res1 = new \Think\Page($scoreCount, $pageSize);
        $pageRes = $res1->show();
        $this->assign('pageRes', $pageRes);

        $this->assign('res', $score);
        $this->display();
    }

    public function edit()
    {
        if($_POST){
            if(!isset($_POST['remain_hour']) || !$_POST['remain_hour'])
            {
                return show(0,'剩余课时不能为空');
            }
            $id = $_POST['id'];
            $data['remain_hour'] = $_POST['remain_hour'];
            $res = D("ScoreExt")->updateById($id,$data);
            if($res){
                return show(1,'修改课时成功');
            }else{
                return show(0,'修改课时失败');
            }
        }else{
            $id = $_GET['id'];
            $res = D("ScoreExt")->getById($id);
            $this->assign('res', $res);
            $this->display();
        }
    }
}