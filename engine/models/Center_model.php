<?php
class Center_model extends MY_Model
{
    function center_fees($id = 0,$select = '*'){
        $id = $id ? $id : $this->loginId();
        $this->db->select($select);
        return $this->db->where('center_id',$id)->get('center_fees');
    }

    function get_center_course_batches($center_id, $course_id)
    {
        $this->db->select('c.*')
            ->from('batch as c')
            ->where('c.center_id', $center_id)
            ->where('c.course_id', $course_id);
        return $this->db->get();
    }

    function get_assign_courses($id, $condition = false)
    {
        $this->db->select('c.*,co.course_name,co.id as course_id,co.fees,co.duration,co.duration_type,cc.course_fee,cc.status as course_status, co.royality_fees')
            ->from('centers as c')
            ->join('center_courses as cc', "cc.center_id = c.id and c.id = '$id' and cc.isDeleted = '0' ")
            ->join('course as co', 'co.id = cc.course_id and co.status = 1');
        if (is_array($condition))
            $this->myWhere('cc', $condition);
        return $this->db->get();
    }

    function get_admin()
    {
        $this->db->where('type', 'admin_signature');
        return $this->db->get('settings');
    }

    function get_all_center()
    {
        $this->db->where('type','center');
        $this->db->where('isDeleted', '0');
        if($this->center_model->isMaster()){
            $this->db->where_in('id', $this->session->userdata('permission'));
        }
        return $this->db->get('centers');
    }

    function get_emp($id)
    {
        return $this->db->select('c.*,ei.about_company, ei.website')->from('centers as c')->join('employer_info as ei', 'ei.employer_id = c.id', 'left')->where('c.type', 'employer')->where('c.isDeleted', '0')->where('c.id', $id)->get();
    }

    function get_center($id = 0, $type = 'center')
    {
        if ($id)
            $this->db->where('id', $id);    
        $this->db->where('type', $type);
        $this->db->where('isDeleted', '0');
        return $this->db->get('centers');
    }
    function get_verified($where = 0)
    {
        $this->myWhere('c', $where);
        $get =  $this->db
            ->from('centers as c')
            ->join('state as s', 's.STATE_ID = c.state_id','left')
            ->join('district as d', 'd.DISTRICT_ID = c.city_id','left')
            ->get();
        // echo $this->db->last_query();
        return $get;
        // if ($where)
        //     $this->db->where($where);
        // $this->db->where('type', 'center');
        // return $this->db->get('centers');
    }

    function get_details(){

    }

    public function list_requests()
{
    $this->db->select('er.*, co.course_name, c.institute_name as center_name, ba.batch_name')
        ->from('exams_master as er')
        ->join('centers as c', 'c.id = er.center_id', 'left')
        ->join('course as co', 'co.id = er.course_id', 'left')
        ->join('batch as ba', 'ba.id = er.batch_id', 'left')
        ->where('er.isDeleted', 0);

    if ($this->isCenter()) {
        $this->db->where('c.id', $this->loginId());
    }

    if ($this->center_model->isMaster()) {
        $this->db->where_in('c.id', $this->session->userdata('permission'));
    }

    // ✅ Add this line to sort by most recent entries
    $this->db->order_by('er.id', 'DESC'); // You can use 'er.created_at' if you prefer

    return $this->db->get();
}


    function get_requests($id = 0)
    {
        if($id > 0){
            $this->db->select('er.*,co.course_name,c.institute_name as center_name,ba.batch_name')
            ->from('exams_master as er')
            ->join('centers as c', 'c.id = er.center_id', 'left')
            ->join('course as co', 'co.id = er.course_id', 'left')
            ->join('batch as ba', 'ba.id = er.batch_id', 'left')
            ->where('er.id', $id);

            return $this->db->get();
        } else {
            return '';
        }
       
    }

    function update_wallet($centre_id , $wallet){
        return $this->db->where('id',$centre_id)->update('centers',['wallet' => $wallet]);
    }

    function verified_centers(){
        $this->db->where('type','center');
        $this->db->where('isPending',0);
        $this->db->where('isDeleted',0);
        $this->db->where('status',1);
        $this->db->where('valid_upto !=','');
        return $this->db->get('centers');
    }

    function wallet_history($c_id = 0){
        
        if(empty($c_id)){
            $c_id = $this->loginId();
        }
        $this->db->select('wt.*,DATE_FORMAT(wt.timestamp,"%d-%m-%Y") as date');
        $this->db->from('wallet_transcations as wt');
        $this->db->where('wt.center_id',$c_id);        
        return $this->db->order_by('id','DESC')->get();
    }

    function wallet_report(){
        $this->db->select('wt.*,DATE_FORMAT(wt.timestamp,"%d-%m-%Y") as date,c.institute_name as center_name');
        $this->db->from('wallet_transcations as wt');
        $this->db->join('centers as c', 'c.id = wt.center_id', 'left');
        $this->db->where('wt.wallet_status','credit'); 
        return $this->db->order_by('wt.id','DESC')->get();
    }

    function student_exams_list($postData){
        $this->db->select('emt.id as id,s.name as student_name,s.roll_no,sm.subject_name,UPPER(qp.paper_type) as paper_type,c.institute_name as center_name,UPPER(co.course_name) as course_name,em.exam_title,DATE_FORMAT(emt.attempt_time, "%d-%m-%Y %h:%i %p") as attempt_time,emt.paper_total_marks,emt.student_total_marks,ROUND(emt.percentage,0) as percentage,emt.status, (SELECT id FROM isdm_marksheets WHERE center_id = em.center_id AND course_id = em.course_id AND exam_id = em.id AND student_id = emt.student_id) as marksheet_id')
            ->from('exams_master as em')
            ->join('exams_master_trans as emt', 'emt.exam_master_id = em.id', 'left')
            ->join('centers as c', 'c.id = em.center_id', 'left')
            ->join('course as co', 'co.id = em.course_id', 'left')
            ->join('subject_master as sm', 'sm.id = emt.subject_id', 'left')
            ->join('students as s', 's.id = emt.student_id', 'left')
            ->join('question_paper as qp', "qp.id = emt.question_paper_id", 'left')
            ->where('em.status', 1)
            ->where('em.is_admin_approved', 2)
            ->where('em.isDeleted', 0);
        if ($this->isCenter())
            $this->db->where('em.center_id', $this->loginId());

        if(!empty($postData['c_id'])){
            $this->db->where('em.center_id', $postData['c_id']);
        }

        if(!empty($postData['s_id'])){
            $this->db->where('emt.student_id', $postData['s_id']);
        }

        return $this->db->get();
    }

    function get_student_exams($post){
        $this->db->select('em.id,em.exam_title,DATE_FORMAT(em.start_date, "%d-%m-%Y") as start_date,DATE_FORMAT(em.end_date, "%d-%m-%Y") as end_date')
            ->from('exams_master as em')
            ->join('exams_master_trans as emt', 'emt.exam_master_id = em.id')
            ->where('em.status', 1)
            ->where('em.is_admin_approved', 2)
            ->where('em.isDeleted', 0)
            ->where('emt.student_id', $post['student_id'])
            ->where('em.course_id', $post['course_id'])
            ->where('em.center_id', $post['center_id']);
        return $this->db->group_by('id')->get();
    }

    function get_student_exam_marks($post){
        $this->db->select('emt.id,emt.subject_id,sm.subject_name,qp.paper_type,emt.paper_total_marks,emt.student_total_marks')
            ->from('exams_master as em')
            ->join('exams_master_trans as emt', 'emt.exam_master_id = em.id')
            ->join('subject_master as sm', 'sm.id = emt.subject_id', 'left')
            ->join('question_paper as qp', "qp.id = emt.question_paper_id", 'left')
            ->where('em.status', 1)
            ->where('em.is_admin_approved', 2)
            ->where('em.isDeleted', 0)
            ->where('emt.student_id', $post['student_id'])
            ->where('em.course_id', $post['course_id'])
            ->where('em.center_id', $post['center_id']);
        return $this->db->get();
    }
}
?>