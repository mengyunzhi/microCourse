<?php

namespace app\index\model;
use think\Model;
use app\index\validate\CourseValidate;

class Course extends Model
{
	private $Teacher;
	private $Term;
	private static $validate;

	public function getTeacher()
	{
		if (is_null($this->Teacher)) {
			$teacherId = $this->getData('teacher_id');
		$this->Teacher = Teacher::get($teacherId);
		}
		return $this->Teacher;
	}

	public function getTerm()
	{

		if (is_null($this->Term)) {
			$termId = $this->getData('term_id');
		    $Term = Term::get($termId);
		}
		return $Term;
	}


	public function getIsChecked(Klass &$Klass)
	{
		// 取课程ID
		$courseId = (int)$this->id;
		$klassId = (int)$Klass->id;

		// 定制查询条件
        $map = array();
        $map['klass_id'] = $klassId;
        $map['course_id'] = $courseId;

		// 从关联表中取信息
		$KlassCourse = KlassCourse::get($map);
		if (is_null($KlassCourse)) {
			return false;
		} else {
			return true;
		}
		// 有记录 返回true；没记录， 返回false。
	}

	public function KlassCourses()
	{
		return $this->hasMany('KlassCourse');
	}

    public function save($data = [], $where = [], $sequence = null)
    {
    	if (!$this->validate($this)) {
    		return false;
    	}

    	return parent::save($data, $where, $sequence);
    }

    private function validate() {
    	if (is_null(self::$validate)) {
    		self::$validate = new CourseValidate();
    	}

    	return self::$validate->check($this);
    }

    public function Score()
    {
    	return $this->hasMany('Score');
    }
  
  public function Term()
	{
		return $this->belongsTo('Term');
	}

	public function Teacher()
	{
		return $this->belongsTo('Teacher');
	}

	public function Courseinfo()
    {
        return $this->hasMany('Courseinfo');
    }

    public function Klasses()
    {
    	return $this->belongsToMany('Klass', 'klass_course');
    }

    //获取某一学生一天的课表
    public static function getStudentCourse($studentId,$Term,$week,$weekday)
	{
        //取出本学期的所有课程，编为一个数组
        $courses = Course::where('term_id',$Term)->select();
        //储存所有课程的ID
        $courseIds = [];
        foreach ($courses  as $value) {
          array_push($courseIds, $value->id);
        }

        //从成绩表中，查询本学期本本学生的所有课程
        $score = new Score();
        $getScore =  $score->where(['course_id'=>$courseIds, 'student_id'=>$studentId])->select();

        //本学生本星期的课程表
        //前面是小节，后面是星期
        //0没用了！！！！！！！！！
        $nullcourseinfo = new Courseinfo();
        $coursetable = array();

        // 得到本学期本学生所有课程成绩所对应的course_id
        $course = new Course();
        if(count($getScore) == 0) {
            // $coursetable = [];
        } else {
            //储存本学期本学生所有课程的course_id
            $scoreIds = [];
            foreach ($getScore as $score) {
                array_push($scoreIds, $score->course_id);
            }

            $courseinfos = Courseinfo::where(['course_id'=>$scoreIds, 'week'=>$week, 'weekday'=>$weekday])->select();
            // dump($courseinfos);
            // return;
            foreach ($courseinfos as $key => $acourseinfo) {
                {
                    $coursetable[$acourseinfo->begin]['name'] = Course::where('id',$acourseinfo->course_id)->find()["name"];
                    $coursetable[$acourseinfo->begin]['littleClass'] = $acourseinfo->begin . "-" . ($acourseinfo->begin + $acourseinfo->length -1 );
                    $coursetable[$acourseinfo->begin]['Classroom'] = $acourseinfo->Classroom->area["name"]." ".$acourseinfo->Classroom["classroomname"];
                }
            }
            // dump($studentId);
            // dump($getScore);
            // // dump($coursetable);
            // dump($courseinfos);
            // return;
            // 通过成绩对应course_id得到本学期本学生所有课程
            // $courses = $course->where(['id'=>$scoreIds])->paginate(5);
        }

        return $coursetable;
	}
}
