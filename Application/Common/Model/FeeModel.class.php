<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 2018/2/8
 * Time: 23:21
 */

namespace Common\Model;


use Think\Model;

class FeeModel extends Model
{
    private $_db;

    public function __construct()
    {
        $this->_db = M('pay');
    }

    public function getFee()
    {
        return $this->_db->select();
    }
}