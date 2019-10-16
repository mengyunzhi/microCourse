<?php

/**
 * @Author: LYX6666666
 * @Date:   2019-08-13 10:09:10
 * @Last Modified by:   LYX6666666
 * @Last Modified time: 2019-10-16 21:16:21
 */
namespace app\index\model;
use think\Model;
use app\index\validate\TermValidate;

class Term extends Model
{
	private static $validate;

    // public static $domainname = "http://192.168.2.66";
    // public static $domainname = "http://localhost";

    public static $term;            //学期
    public static $date;    //日期
    public static $week;            //周次
    public static $weekday;//星期
    public static $largeClass;              //大节
    public static $littleClass;         //小节
    public static $length;
    public static $termId;  //学期id

    public static function timeAll()
    {
        $time = new Term();
        $time->term = Term::ifterm();
        $time->date = date('Y-m-d H:i:s');
        $time->week = Term::getWeek();
        $time->weekday = Term::getWeekday(Term::weekday()-1);
        $time->largeClass = Term::largeClass();
        $time->littleClass =Term::littleClass();
        $time->length = Term::TermLength();
        $time->termId = Term::termId();

        return $time;
    }

    public function save($data = [], $where = [], $sequence = null)
    {
    	if (!$this->validate($this)) {
    		return false;
    	}

    	return parent::save($data, $where, $sequence);
    }

    private function validate()
    {
    	if (is_null(self::$validate)) {
    		self::$validate = new TermValidate();
    	}

    	return self::$validate->check($this);
    }

    //星期函数：将星期数字转换为星期
    //传入星期数字传出星期字符串
    public static function getWeekday($Weekday)
    {
        $weekday = '';
        switch ($Weekday) {
            case 0:
                $weekday = "星期一";
                break;
            case 1:
                $weekday = "星期二";
                break;
            case 2:
                $weekday = "星期三";
                break;
            case 3:
                $weekday = "星期四";
                break;
            case 4:
                $weekday = "星期五";
                break;
            case 5:
                $weekday = "星期六";
                break;
            case 6:
                $weekday = "星期日";
                break;
}       return $weekday;
    }

    //日期函数：求出两个日期的周次差——刘宇轩
    //注意：传入的数据应该是合法的日期数据（YYYY-mm-dd）
    //获取日期的函数：date("Y/m/d")
    public static function diffWeek ($day1, $day2)
    {
        $second1 = strtotime($day1);
        $second2 = strtotime($day2);
        if ($second1 < $second2) {      //两日期差取绝对值
        $tmp = $second2;
        $second2 = $second1;
        $second1 = $tmp;
        }
        return ceil(($second1 - $second2) / 86400 /7);
    }

    //星期函数：求出当前激活学期的长度——刘宇轩
    //注意：无传参
    //注意：当前无学期激活时，返回0，请自行转化！
    public static function TermLength ()
    {
        $Term = new Term;
        $begin = $Term->where('state',1)->find();
        if (is_null($begin)) {
            return 0;   
        }
        $second1 = strtotime($begin->start);
        $second2 = strtotime($begin->end);
        if ($second1 < $second2) { 
        $tmp = $second2;
        $second2 = $second1;
        $second1 = $tmp;
        }
        return ceil(($second1 - $second2) / 86400 /7);
    }

    //星期函数：求出当前日期的星期——刘宇轩
    //注意：无传参
    public static function Weekday()
    {
        $day = date("Y/m/d");
        $second = strtotime($day);
        $weekday = array('7','1','2','3','4','5','6'); //返回整数，7为周日
        return $weekday[date('w', $second)];
    }

    //日期函数：获取当前周次——刘宇轩
    //注意：无传参
    public static function getWeek()
    {
        $Term = new Term;
        $begin = $Term->where('state',1)->find();
        if (is_null($begin)) {
            return 0;   //注意：当前无学期激活时，返回0，请自行转化！
        }
        $second1 = strtotime($begin->start);
        $second2 = strtotime(date("Y/m/d"));
        if ($second1 < $second2) { 
        $tmp = $second2;
        $second2 = $second1;
        $second1 = $tmp;
        }
        return ceil(($second1 - $second2) / 86400 /7);
    }

    //课程函数：获取当前节次（小节）——刘宇轩
    //注意：无传参，注意小数的调用
    public static function littleClass ( )
    {
        $now = date("H-i-s");
        $times = ["06-30-00","09-15-00","09-20-00","10-05-00","10-25-00","11-10-00","11-15-00","12-00-00","14-00-00","14-45-00","14-50-00","15-35-00","15-55-00","16-40-00","16-45-00","17-30-00","18-40-00","19-25-00","19-30-00","20-15-00","20-20-00","23-59-59"];

        for ($i=0; $i <= 20 ; $i = $i+2) { 
            if ($now > $times[$i] && $now <$times[$i+1])
            {
                return $i/2+1; //返回小节数，整数为课上
            }
        }
        for ($i=1; $i <= 19 ; $i = $i+2) { 
            if ($now > $times[$i] && $now <$times[$i+1])
            {
                return $i/2+1.5; //返回课间数，小数为课间
            }
        }

        if ($now > $times[21])
        {
            return 12;
        }
        return 0;
    }
    
    //课程函数：获取当前节次（大节）——刘宇轩
    //注意：无传参，注意小数的调用
    public static function largeClass ( )
    {
        $now = date("H-i-s");
        $times = ["08-30-00","10-05-00","10-25-00","12-00-00","14-00-00","15-35-00","15-55-00","17-30-00","18-40-00","21-05-00"];

        for ($i=0; $i < 10 ; $i = $i+2) { 
            if ($now > $times[$i] && $now <$times[$i+1])
            {
                return $i/2+1; //返回大节数，整数为课上
            }
        }
        for ($i=1; $i < 9 ; $i = $i+2) { 
            if ($now > $times[$i] && $now <$times[$i+1])
            {
                return $i/2+1.5; //返回课间数，小数为课间
            }
        }
        return 0;
    }     

    // trem一对多查询course函数——赵凯强
    public function Course()
    {
        return $this->hasMany('Course');
    }
    
    // 学期函数：判断当前学期状态————赵凯强
    // 不传参
    static public function ifterm()
    {
        $terms = Term::all();
        foreach ($terms as $term) {
            if ($term->state == 1) {
                return 1;
            }
        }
        return 0;
    }

    // 获取当前学期id --ztq
    static public function termId()
    {
        $terms = Term::all();
        
        if (is_null(Term::where('state',1)->value('id'))) {
            return '当前无激活学期';
        } else {
            $termId = Term::where('state',1)->value('id');
            return $termId;
        }
    }


    public static $timetable = ['0','(08:30-09:15)','(09:20-10:05)','(10:25-11:10)','(11:15-12:00)','(14:00-14:45)','(14:50-15:35)','(15:55-16:40)','(16:45-17:30)','(18:40-19:25)','(19:30-20:15)','(20:20-21:05)'];

    // 判断是否有学期
    // 无传参
    static public function NoTerm()
    {
        $terms = Term::all();
        if (count($terms) == 0){
            return 1;
        } else {
            return 0;
        }
    } 

}
