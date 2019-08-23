<?php

/**
 * @Author: LYX6666666
 * @Date:   2019-08-14 21:26:00
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-08-22 20:32:30
 */
namespace app\index\model;
use think\Model;

class courseinfo extends Model
{

	public function classroom()
	{
		return $this->belongsTo('classroom');
	}

	public function teacher()
	{
		return $this->belongsTo('teacher');
	}

	public function course()
	{
		return $this->belongsTo('course');
	}

	//获取特定课程、星期和时段的所有课——刘宇轩
	//传入课程ID、星期几、课程起始时间，返回对象数组
	public static function getCourseinfo($id,$Weekday,$Begin)
	{
		$Courses = Courseinfo::where('course_id',$id)->where('weekday',$Weekday)->where('Begin',$Begin)->select();
		return $Courses;
	}

	//获取特定课程、星期和时段的所有课的周次数组——刘宇轩
	//传入课程ID、星期几、课程起始时间，返回数组
	//注意：只返回一个数组
	public static function getCourseWeek($id,$Weekday,$Begin)
	{
		$weeks = [];
		$str = "";
		$Courses = Courseinfo::where('course_id',$id)->where('weekday',$Weekday)->where('Begin',$Begin)->select();
		foreach ($Courses as $key => $acourse) {
			$weeks[$key] = $acourse->week;
		}
		asort($weeks);
		foreach ($weeks as $key => $week) {
			$str = $str . $week . " ";
		}
		return $str;
	}

	//获取特定课程、星期和时段的所有课的长度——刘宇轩
	//传入课程ID、星期几、课程起始时间，返回整数
	//注意：只返回一个整数
	public static function getCourseLength($id,$Weekday,$Begin)
	{
		$length = 0;
		$Courses = Courseinfo::where('course_id',$id)->where('weekday',$Weekday)->where('Begin',$Begin)->select();
		foreach ($Courses as $key => $acourse) {
			$length = $acourse->length;
		}
		return $length;
	}

	//获取特定课程、星期和时段的所有课的教室——刘宇轩
	//传入课程ID、星期几、课程起始时间，返回整数
	//注意：只返回一个整数
	public static function getCourseClassroom($id,$Weekday,$Begin)
	{
		$classroom_id = 0;
		$Courses = Courseinfo::where('course_id',$id)->where('weekday',$Weekday)->where('Begin',$Begin)->select();
		foreach ($Courses as $key => $acourse) {
			$classroom_id = $acourse->classroom_id;
		}
		return $classroom_id;
	}

	//获取特定课程的课程表(全部周次)——刘宇轩
	//传入课程ID，返回二维数组
	public static function getCourseTable($id)
	{
		$CourseTable[] = array();
		$i = 0;
		for ($Begin=1; $Begin <= 9; $Begin++) { 
			for ($Weekday=1; $Weekday <= 7; $Weekday++) { 
				$CourseTable[$i][0] = Courseinfo::getCourseWeek($id,$Weekday,$Begin);
				$CourseTable[$i][1] = Courseinfo::getCourseLength($id,$Weekday,$Begin);
				$CourseTable[$i][2] = Courseinfo::getCourseClassroom($id,$Weekday,$Begin);
				$i++;
			}
		}
		return $CourseTable;
	}

	//获取特定课程、星期、时段和周次的一节课是否存在——刘宇轩
	public function getIsCheched($id,$weekday,$Begin,$week)
	{
		$Courses = Courseinfo::where('course_id',$id)->where('weekday',$weekday)->where('Begin',$Begin)->where('week',$week)->find();
		if (is_null($Courses)) {
            return 0;
        } else {
            return 1;
        }
	}

}
