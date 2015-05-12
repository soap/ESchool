<?php
defined('_JEXEC') or die;

class plgContentEsscore extends JPlugin
{
	/**
	 * 
	 * Enter description here ...
	 * @param unknown $context
	 * @param unknown $table
	 * @param unknown $isNew
	 * @return boolean
	 */
	public function onContentAfterSave($context, $table, $isNew)
	{
		
		if ($context != 'com_eschool.rawscore') {
			return true;
		}
		
		$this->updateScore($table->exam_id, $table->student_id);
		$this->calculateGrade($table->exam_id, $table->student_id);
		
		return true;
	} 
	
	/**
	 * "onContentAfterDelete" event handler
	 *
	 * @param     string     $context    The item context
	 * @param     object     $table      The item table object
	 *
	 * @return    boolean                True
	 */
	public function onContentAfterDelete($context, $table)
	{
		if ($context != 'com_eschool.rawscore') {
			return true;
		}
		
		$this->updateScore($table->exam_id, $table->student_id);
		$this->calculateGrade($table->exam_id, $table->student_id);
		return true;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown $exam_id
	 * @param unknown $student_id
	 */
	 
	protected function updateScore($exam_id, $student_id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select('e.id');
		$query->from('#__eschool_exams AS e');
		$query->select('p.semester_id, p.syllabus_course_id, p.scoring_type_id');
		$query->join('LEFT','#__eschool_scoring_plans AS p ON p.id=e.scoring_plan_id');
		$query->where('e.id='.(int)$exam_id);
		//JFactory::getApplication()->enqueueMessage('Exam ID = '.$exam_id.' Student ID = '.$student_id);
				
		$db->setQuery($query);
		$obj = $db->loadObject();
		//JFactory::getApplication()->enqueueMessage('Semester ID = '.$obj->semester_id.' Syllabus Course ID = '.$obj->syllabus_course_id. ' Scoring type ID ='.$obj->scoring_type_id);
		
		$query->clear();
		$query->select('SUM(a.score * e.weight/e.full_score)');
		$query->from('#__eschool_rawscores AS a');
		$query->join('LEFT', '#__eschool_exams AS e ON e.id=a.exam_id');
		$query->join('LEFT', '#__eschool_scoring_plans AS p ON p.id=e.scoring_plan_id');
		$query->where('a.student_id='.$student_id);
		$query->where('p.semester_id='.$obj->semester_id);
		$query->where('p.syllabus_course_id='.$obj->syllabus_course_id);
		$query->where('p.scoring_type_id='.$obj->scoring_type_id);
		
		
		$db->setQuery($query);
		$calculatedScore = (int)$db->loadResult();
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/com_eschool/tables');
		$calcScore = JTable::getInstance('Calcscore', 'EschoolTable');
		
		//JFactory::getApplication()->enqueueMessage('Calculated Score = '.$calculatedScore);
		
		
		$keys = array('student_id'=>$student_id,'semester_id'=>$obj->semester_id, 
					'syllabus_course_id'=>$obj->syllabus_course_id, 'scoring_type_id'=>$obj->scoring_type_id);

		if (!$calcScore->load($keys)) { 
	
			$calcScore->bind($keys);
		}
		//var_dump($keys); jexit();
		$calcScore->calc_score = $calculatedScore;
		$calcScore->check();
		$calcScore->store();
		
		return true;
	}

	protected function calculateGrade($exam_id, $student_id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select('e.id');
		$query->from('#__eschool_exams AS e');
		$query->select('p.semester_id, p.syllabus_course_id, p.scoring_type_id');
		$query->join('LEFT','#__eschool_scoring_plans AS p ON p.id=e.scoring_plan_id');
		$query->where('e.id='.(int)$exam_id);
		//JFactory::getApplication()->enqueueMessage('Exam ID = '.$exam_id.' Student ID = '.$student_id);
		
		$db->setQuery($query);
		$obj = $db->loadObject();
		
		$query->clear();
		
		$query->select('id');
		$query->from('#__eschool_registrations');
		$query->where('student_id='.$student_id);
		$query->where('semester_id='.$obj->semester_id);
		
		$db->setQuery($query);
		$regId = $db->loadResult();
		
		if (!$regId) {
			
			return true;
		}
		
		$query->clear();
		$query->select('SUM(a.calc_score*p.weight/100)');
		
		$query->from('#__eschool_calcscores AS a');
		$query->join('LEFT', '#__eschool_scoring_plans AS p ON p.semester_id=a.semester_id AND p.syllabus_course_id=a.syllabus_course_id AND p.scoring_type_id=a.scoring_type_id');
		$query->where('a.student_id='.$student_id);
		$query->where('a.syllabus_course_id='.$obj->syllabus_course_id);
		
		$db->setQuery($query);
		$percent = $db->loadResult();
		
		$query->clear();
		$query->select('a.id');
		$query->from('#__eschool_gradings AS a');
		$query->where($percent .' >= a.min_score');
		$query->order('a.min_score DESC');

		$db->setQuery($query);
		$grading_id = $db->loadResult();
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/com_eschool/tables');
		$gradeTable = JTable::getInstance('Registrationrecord', 'EschoolTable');
		$keys = array('registration_id'=>$regId, 'semester_id'=>$obj->semester_id, 'syllabus_course_id'=>$obj->syllabus_course_id);
		if ($gradeTable->load($keys)) {
			$gradeTable->scoring_percent = $percent;
			$gradeTable->grading_id = $grading_id;
			$gradeTable->store();
		}
		
		return true;
	}
	
}