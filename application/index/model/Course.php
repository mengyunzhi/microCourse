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

	public function Klasses()
	{
		return $this->belongsToMany('Klass', 'klass_course');
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
}