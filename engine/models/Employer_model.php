<?php
class Employer_model extends MY_Model
{
    function get_all_role(){
        return $this->db->select('*')->where('status',1)->get('job_role');
    }

    function get_all_group(){
        return $this->db->select('*')->where('status',1)->get('group');
    }

    // function get_all_department(){
    //     return $this->db->select('*')->where('status',1)->get('department');
    // }

    function get_all_education(){
        return $this->db->select('*')->where('status',1)->get('education');
    }


    function get_employer_via_id($id = 0)
    {
    	if($id > 0){
    		return $this->db->select('e.*')
            ->from('centers as e')
            ->where('e.type', 'employer')
            ->where('e.id', $id);
    	} else {
    		return '';
    	}
    }

    function getKeySKill()
    {
        return $this->db->select('*')
        ->from('job_skill')
        ->where('status',1)
        ->get()->result_array(); 
    }

    function getIndustry()
    {
        return $this->db->select('*')
        ->from('industry')
        ->where('status',1)
        ->get()->result_array(); 
    }

    function getMatchedAllStudent(){
        return $this->db->select('sp.*')
        ->from('students as s')
        ->join('student_profile as sp', 'sp.student_id = s.id', 'inner')
        ->where('s.status',1)
        ->get()->result_array();
    }

    function getTotalAppliedStudent($jobs){
        return $this->db->select('*')
        ->from('received_interview_request')
        ->where('job_id',$jobs['id'])
        ->get()->result_array();
    }

    public function get_job_roles_by_industries($industry_ids = []) {
        if (!empty($industry_ids)) {
            $this->db->where_in('ijr.industry_id', $industry_ids);
        }
    
        $this->db->select('ijr.id, ijr.industry_id, jr.id as job_role_id, jr.role')
                 ->from('isdm_industry_job_role as ijr')
                 ->join('job_role as jr', 'ijr.job_role_id = jr.id', 'left')
                 ->where('ijr.status', 1)
                 ->order_by('jr.role', 'ASC');
    
        return $this->db->get()->result_array();
    }
    
    
    
    
    

}
?>