<?php
class Academic extends Ajax_Controller
{
    function add_batch()
    {
        if ($this->validation()) {
            $data = $this->post();
            $data['from_date'] = date('Y-m-d',strtotime($this->post('from_date')));
            $data['to_date'] = date('Y-m-d',strtotime($this->post('to_date')));
            $this->response(
                'status',
                $this->db->insert('batch', $data)
            );
        }
    }
    function edit_batch(){
        $this->db->where('id',$this->post('id'))->update('batch',[
            'batch_name' => $this->post('batch_name'),
        ]);        
        $this->response('status',true);
    }
    function edit_session(){
        $this->db->where('id',$this->post('id'))->update('session',[
            'title' => $this->post('title'),
        ]);        
        $this->response('status',true);
    }
    function edit_occupation(){
        $this->db->where('id',$this->post('id'))->update('occupation',[
            'title' => $this->post('title'),
        ]);        
        $this->response('status',true);
    }
    function list_batch(){
        $ci = &get_instance();
        if($ci->session->userdata('admin_type') == 'center'){
            $list = $this->db->
            select('c.*,cat.name as center,cc.course_name as course')
            ->from('batch as c')
            ->join('centers as cat', 'cat.id = c.center_id', 'left')
            ->join('course as cc', 'cc.id = c.course_id', 'left')
            ->where('cat.id',$ci->session->userdata('admin_id'))
            ->get();
        } else {
            $list = $this->db->
            select('c.*,cat.name as center,cc.course_name as course')
            ->from('batch as c')
            ->join('centers as cat', 'cat.id = c.center_id', 'left')
            ->join('course as cc', 'cc.id = c.course_id', 'left')
            ->get();
        }

        $data = [];
        if($list->num_rows())
            $data = $list->result();
        $this->response('data',$data);
    }
    function delete_batch($batch_id = 0){
        // $this->response($_GET);
        if($batch_id){
            $this->response( 'status',
                $this->db->where('id',$batch_id)->delete('batch')
            );
            $this->response('html','Data Delete successfully.');
        }
        else
            $this->response('html','Action id undefined');
        // $this->response('html',$batch_id);
    } 


    //session part
    function add_session()
    {
        if ($this->validation()) {
            $this->response(
                'status',
                $this->db->insert('session', $this->post())
            );
        }
    }
    function list_session(){
        $list = $this->db->get('session');
        $data = [];
        if($list->num_rows())
            $data = $list->result();
        $this->response('data',$data);
    }
    function delete_session($session_id = 0){
        // $this->response($_GET);
        if($session_id){
            $this->response( 'status',
                $this->db->where('id',$session_id)->delete('session')
            );
            $this->response('html','Data Delete successfully.');
        }
        else
            $this->response('html','Action id undefined');
        // $this->response('html',$batch_id);
    } 

    //Occupation part
    function add_occupation()
    {
        if ($this->validation()) {
            $this->response(
                'status',
                $this->db->insert('occupation', $this->post())
            );
        }
    }
    function list_occupation(){
        $list = $this->db->get('occupation');
        $data = [];
        if($list->num_rows())
            $data = $list->result();
        $this->response('data',$data);
    }
    function delete_occupation($occupation_id = 0){
        // $this->response($_GET);
        if($occupation_id){
            $this->response( 'status',
                $this->db->where('id',$occupation_id)->delete('occupation')
            );
            $this->response('html','Data Delete successfully.');
        }
        else
            $this->response('html','Action id undefined');
        // $this->response('html',$batch_id);
    } 

    //Class Plan
    function add_class_plan(){

        $data = $this->post();

        $this->ki_theme->set_default_vars('max_upload_size', 10485760); // 10MB
        if ($file = $this->file_up('notes')) {
            $data['notes'] = $file;
        }

        $data['status'] = 1;
        $data['isDeleted'] = 0;

        $this->response(
            'status',
            $this->db->insert('class_plan', $data)
        );
    }

    function list_master_class(){
        $this->db->select(' 
                ce.institute_name as center_name,
                c.course_name,
                c.duration,
                c.duration_type,
                b.batch_name,
                ss.subject_name,
                ces.name as staff_name,
                s.id,
                s.title,
                s.description,
                s.type,
                s.plan_date,
                s.notes'
        )
        ->from('class_plan as s')
        ->join("course as c", "s.course_id = c.id ", 'left')
        ->join('batch as b', "b.id = s.batch_id", 'left')
        ->join('subject_master as ss', "ss.id = s.subject_id", 'left')
        ->join('centers as ces', 'ces.id = s.staff_id','left')
        ->where('s.isDeleted', 0);

        if ($this->center_model->isCenter()){
            if(!empty($this->session->userdata('staff_id'))){
                $this->db->where('s.staff_id',$this->session->userdata('staff_id'));
            } 
            $this->db->join('centers as ce', 'ce.id = s.center_id AND s.center_id = ' . $this->center_model->loginId());
        }
        else {
            $this->db->join('centers as ce', 'ce.id = s.center_id', 'left');
        }

        $this->db->order_by('s.id','DESC');

        $list = $this->db->get();
        $data = [];
        if($list->num_rows())
            $data = $list->result();
        $this->response('data',$data);
    }

    function delete_master_class(){
        
        if($this->post('id')){
            $this->response( 'status',
                $this->db->where('id',$this->post('id'))->delete('class_plan')
            );
            $this->response('html','Data Delete successfully.');
        }
        else
            $this->response('html','Action id undefined');
        // $this->response('html',$batch_id);
    }
}
?>