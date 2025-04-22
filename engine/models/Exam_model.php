<?php
class Exam_model extends MY_Model
{
    function fetch_all($id = 0)
    {
        $this->db->select('*,e.id as exam_id,e.duration as exam_duration,e.status as exam_status')
            ->from('exams as e')
            ->join('course as c', 'c.isDeleted = 0 and c.id = e.course_id')
            ->where('e.isDeleted', 0);
        if ($id) {
            $this->db->where('e.id', $id);
        }
        return $this->db->get();
    }
    function list_exam_questions($exam_id)
    {
        return $this->db->where('exam_id', $exam_id)->get('exam_questions');
    }
    function fetch_question($id){
        return $this->db->where('id',$id)->get('exam_questions');
    }
    
    function student_exam($where)
    {
        $this->db->select('*,es.id as exam_id')
            ->from('exams_master_trans as es')
            ->join('subject_master as ess', 'ess.id = es.subject_id ')
            ->join('exams_master as e', 'e.id = es.exam_master_id')
            ->join('question_paper as qp', "qp.id = es.question_paper_id");
        $this->myWhere('es', $where);
        return $this->db->get();
    }
    function get_student_exam($where){
        return $this->db->where($where)->get('exam_students');
    }
    public function get_shuffled_questions($question_paper_id, $limit = 0)
    {
        $this->db->select('q.*')
            ->from('question_paper as qp')
            ->join('question_paper_trans as qpt', 'qp.id = qpt.paper_id AND qpt.status = 1')
            ->join('questions as q', 'q.id = qpt.question_id AND q.status = 1')
            ->where('qp.id', $question_paper_id);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $questions = $query->result_array();
            shuffle($questions);
            return $questions;
        } else {
            return array();
        }
    }
    public function get_nonshuffled_questions($question_paper_id, $limit = 0)
    {
        $this->db->select('q.*')
            ->from('question_paper as qp')
            ->join('question_paper_trans as qpt', 'qp.id = qpt.paper_id AND qpt.status = 1')
            ->join('questions as q', 'q.id = qpt.question_id AND q.status = 1')
            ->where('qp.id', $question_paper_id);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $questions = $query->result_array();
            return $questions;
        } else {
            return array();
        }
    }

    function list_subject_questions($subject_id)
    {
        return $this->db->where('subject_id', $subject_id)->get('questions');
    }

    function list_subject_paper($subject_id)
    {
        return $this->db->where('subject_id', $subject_id)->get('question_paper');
    }

    function list_question_answers($ques_id)
    {
        return $this->db->select('*,eqa.id as answer_id')
            ->from('questions_answers as eqa')
            ->join('questions as eq', 'eq.id = eqa.question_id')
            ->where('eq.id', $ques_id)
            ->get();
    }

    function list_question_papers_ids($paper_id)
    {
        $query = $this->db->where('paper_id', $paper_id)->get('question_paper_trans');
        if ($query->num_rows() > 0) {
            $questions = $query->result_array();
            $new_ques = [];
            foreach($questions as $key  => $value){
                $new_ques[] = $value['question_id'];
            }
            return $new_ques;
        } else {
            return array();
        }
    }
}