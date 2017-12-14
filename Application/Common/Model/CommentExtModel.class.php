<?php
/**
 * Created by PhpStorm.
 * User: rehellinen
 * Date: 2017/8/3
 * Time: 22:49
 */

namespace Common\Model;
use Think\Model;


class CommentExtModel extends Model
{
    private $_db;

    public function __construct()
    {
        $this->_db = M('tea_to_stu_comment');
    }

    public function getComment($data,$page,$pageSize = 10) {
        $data['status'] = array('neq',-1);
        $offset = ($page -1) * $pageSize;
        $list = $this->_db->where($data)->order('listorder desc , comment_id desc')->limit($offset,$pageSize)->select();
        return $list;
    }

    public function getCommentCount($data = array()) {
        $data['status'] = array('neq',-1);
        return $this->_db->where($data)->count();
    }

    public function updateCommentListorder($id , $listorder) {
        if(!$id || !is_numeric($id)) {
            throw_exception('ID不合法');
        }
        $data = array(
            'listorder' => intval($listorder),
        );
        return $this->_db->where('comment_id='.$id)->save($data);
    }

    public function updateStatusById($id , $status){
        if(!is_numeric($id) || !$id){
            throw_exception("ID不合法");
        }
        //if(!is_numeric($status) || !$status){
        //  throw_exception("状态不合法");
        //}
        //echo $status;exit;
        $data['status'] = $status;
        //print_r($data);exit;
        return $this->_db->where('comment_id='.$id)->save($data);
    }

    public function getcommentById($id) {
        return $this->_db->where('comment_id='.$id)->find();
    }

}