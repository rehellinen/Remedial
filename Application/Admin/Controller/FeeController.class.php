<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 2018/2/8
 * Time: 23:16
 */
namespace Admin\Controller;

use Common\Model\FeeModel;
use Common\Model\OpenidModel;
use Common\Model\StudentModel;

class FeeController extends CommonController
{
    public function index()
    {
        $fee = (new FeeModel())->getFee();

        foreach ($fee as $key => $value){
            foreach ($value as $key2 => $value2){
                if($key2 == 'openid'){
                    $buyer = (new OpenidModel())->findOpenid($value2);
                    $buyerID = $buyer['id'];

                    $trueBuyer = (new StudentModel())->findStudent($buyerID);
                    $fee[$key]['openid'] = $trueBuyer['stu_name'];
                }
            }
        }

        foreach ($fee as $key => $value){
            foreach ($value as $key2 => $value2){
                if($key2 == 'time_end'){
                    $str1 = substr($value2, 0 , 4);
                    $str2 = substr($value2, 4 , 4);
                    $str3 = substr($value2, 8 , 4);
                    $str4 = substr($value2, 12 , 2);

                    $str = $str1.'-'.chunk_split($str2,2,"-").'--'.chunk_split($str3,2,"-").$str4;
                    $fee[$key][$key2] = $str;
                }
            }
        }

        $this->assign('fee', $fee);
        return $this->display();
    }
}