<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/9/3
 * TimeController.class: 20:45
 */

namespace Common\Model;


use Think\Model;

class ScoreExtModel extends Model
{
    private $_db;

    public function __construct()
    {
        $this->_db = M('subject_score');
    }

    public function getAll($data, $page, $pageSize)
    {
        $offset = ($page -1 ) * $pageSize;
        $data['status'] = array('neq',-1);
        $ret = $this->_db->where($data)->order('listorder desc , id desc')->limit($offset,$pageSize)->select();
        return $ret;
    }

    public function getStuIdByClass($classId)
    {
        $data['status'] = array('neq',-1);
        $data['class_id'] = $classId;
        return $this->_db->where($data)->select();
    }

    public function getAllCount()
    {
        $data['status'] = array('neq',-1);
        return $this->_db->where($data)->count();
    }

    public function getById($id)
    {
        $data['status'] = array('neq', -1);
        $data['id'] = $id;
        return $this->_db->where($data)->find();
    }

    public function updateById($id,$data)
    {
        return $this->_db->where('id='.$id)->save($data);
    }
}