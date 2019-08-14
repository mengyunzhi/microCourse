<?php
namespace app\index\controller;
use app\common\model\Index;
use think\Controller;
/**
 * @Author: LYX6666666
 * @Date:   2019-07-19 14:58:16
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-08-13 10:46:39
 */
class IndexController extends Controller
{
	public function index()
	{
		return $this->fetch();
	}
}