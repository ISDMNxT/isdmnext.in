<?php
class Course extends Ajax_Controller
{
    //for course
    function add()
    {
        $data = $this->post();
        if ($this->center_model->isCenter()) {
            $data['status'] = 2;
        }
        $this->response(
            'status',
            $this->db->insert('course', $data)
        );
        if ($this->center_model->isAdmin()) {
            $this->response('html', 'Course Added successfully.');
        } else {
            $this->response('html', 'Course Request Sent successfully.');
        }
    }
    function approve_or_reject(){
        $post = $this->post();
        if($post['status'] == 'R'){
            $this->db->update('course', ['status' => 3], ['id' => $post['id']]);
            $this->response('status', true);
        }
        if($post['status'] == 'A'){
            $this->db->update('course', ['status' => 1], ['id' => $post['id']]);
            $this->response('status', true);
        }
    }
    function edit_course()
    {
        $course = $this->db->get_where('course', ['id' => $this->post('id')])->row();
        $categories = $this->db->order_by('title', 'ASC')->get('course_category')->result_array();

        $rowData = [
            'course_id' => $course->id,
            'category_id' => $course->category_id,
            'course_name' => $course->course_name,
            'fees' => $course->fees,
            'duration' => $course->duration,
            'duration_type' => $course->duration_type,
            'royality_fees' => $course->royality_fees,
            'categories' => $categories,
        ];

        $this->response('rowData', $rowData);
        $this->response('status', true);
    }
    function edit()
    {
        $temp = [
            'category_id' => $this->post('category_id'),
            'course_name' => $this->post('course_name'),
            'fees' => $this->post('fees'),
            'duration' => $this->post('duration'),
            'duration_type' => $this->post('duration_type')
        ];

        if(!empty($_POST['royality_fees'])){
           $temp['royality_fees'] = $this->post('royality_fees');
        }
        $this->db->where('id', $this->post('id'))->update('course', $temp);
        $this->response('status', true);
    }
    function edit_subject()
    {
        $get = $this->db->where('course_id', $this->post('course_id'))->where('subject_id', $this->post('subject_id'))->where('isDeleted', 0)->where('id !=', $this->post('id'))->get('subjects');

        if ($get->num_rows()) 
        {
            $this->response('status', false);
            $this->response('html', 'This Course already have their subject. please assign another subject.');
            $this->response('error', 'This Course already have their subject. please assign another subject.');
        } else {
            $this->db->where('id', $this->post('id'))->update('subjects', [
                'subject_code' => $this->post('subject_code'),
                'subject_name' => $this->post('subject_name'),
                'subject_id' => $this->post('subject_id')
            ]);
            $this->response('status', true);
        }
    }
    function edit_category()
    {
        // $this->response($this->post());
        $this->db->where('id', $this->post('id'))->update('course_category', [
            'title' => $this->post('title')
        ]);
        $this->response('status', true);
    }
    function delete($course_id)
    {
        if ($course_id) {
            $this->response(
                'status',
                $this->db->where('id', $course_id)->update('course', ['isDeleted' => 1])
            );
            $this->response('html', 'Data Delete successfully.');
        } else
            $this->response('html', 'Action id undefined');
    }
    function param_delete()
    {
        if ($this->post('course_id')) {
            $get = $this->db->where('course_id', $this->post('course_id'))->get('students');
            if ($get->num_rows()) {
                $this->response('error', 'There are ' . $get->num_rows() . ' students associated with this course, first delete them.');
            } else {
                $get = $this->db->where('course_id', $this->post('course_id'))->get('subjects');
                if ($get->num_rows()) {
                    $this->response('error', 'There are ' . $get->num_rows() . ' Subjects associated with this course, first delete them.');
                } else {
                    $this->response(
                        'status',
                        $this->db->where('id', $this->post('course_id'))->delete('course')
                    );
                    $this->response('error', 'Course Permanently Deleted successfully.');
                }
            }
        } else
            $this->response('error', 'Action id undefined');
    }
    function list()
    {
        $list = $this->db->
            select('c.*,c.id as course_id,cat.title as category')
            ->from('course as c')
            ->join('course_category as cat', 'cat.id = c.category_id', 'left')
            ->where('c.isDeleted', 0);
        if(!empty($_GET['category']) && $_GET['category'] != 'undefined'){
            $list = $list->where('c.category_id', $_GET['category']);
        }

        if($this->center_model->isMaster()) {
            $list = $list->join('center_courses as cc', "cc.course_id = c.id and cc.isDeleted = '0'");
            $list = $list->where_in('cc.center_id',$this->session->userdata('permission'));
            $list = $list->group_by('c.id');
        } else if($this->center_model->isCenter()) {
            $list = $list->join('center_courses as cc', "cc.course_id = c.id and cc.isDeleted = '0'");
            $list = $list->where('cc.center_id', $this->center_model->loginId());
        } 

        $list = $list->order_by('c.id', 'DESC')->get();
        $data = [];
        if ($list->num_rows()){
            $data = $list->result();
        }
        $this->response('data', $data);
    }
    function listar()
    {
        $list = $this->db->
            select('c.*,c.id as course_id,cat.title as category')
            ->from('course as c')
            ->join('course_category as cat', 'cat.id = c.category_id', 'left')
            ->where('c.isDeleted', 0)
            ->where('c.status', 2)
            ->order_by('c.id', 'DESC')
            ->get();
        $data = [];
        if ($list->num_rows())
            $data = $list->result();
        $this->response('data', $data);
    }
    function delete_list()
    {
        $list = $this->db->
            select('c.*,c.id as course_id,cat.title as category')
            ->from('course as c')
            ->join('course_category as cat', 'cat.id = c.category_id', 'left')
            ->where('c.isDeleted', 1)
            ->get();
        $data = [];
        if ($list->num_rows())
            $data = $list->result();
        // if()
        $this->response('data', $data);
    }
    function setting_form()
    {
        if (CHECK_PERMISSION('SHOW_MULTIPLE_CERTIFICATES')) {
            $get = $this->db->where('id', $this->post('id'))->get('course');
            if ($get->num_rows()) {
                $this->response('status', true);
                $this->set_data($get->row_array());
                $this->response('html', $this->template('course-multi-certi-setting'));
            }
        } else {
            $this->response('error', 'You have no permission to view this page.');
        }
    }
    function update_multi_certi()
    {
        if (CHECK_PERMISSION('SHOW_MULTIPLE_CERTIFICATES')) {
            $this->db->where('id', $this->post('id'))->update('course', [
                'parent_id' => $this->post('parent_id')
            ]);
            $this->response('status', true);
            $this->response('html', $this->db->last_query());
        }
    }
    function add_subject()
    {
        $get = $this->db->where('course_id', $this->post('course_id'))->where('subject_id', $this->post('subject_id'))->where('isDeleted', 0)->get('subjects');

        if ($get->num_rows()) 
        {
            $this->response('html', 'This Course already have their subject. please assign another subject.');
        } else {
            $data = [
                'subject_id' => $this->post("subject_id"),
                'subject_name' => $this->post("subject_name"),
                'subject_code' => $this->post("subject_code"),
                'course_id' => $this->post("course_id"),
                'duration' => $this->post("duration"),
                'duration_type' => $this->post("duration_type"),
                'subject_type' => $this->post("subject_type")
            ];
            if (in_array($this->post('subject_type'), ['practical', 'both'])) {
                $data['practical_max_marks'] = $this->post("practical_max_marks");
                $data['practical_min_marks'] = $this->post("practical_min_marks");
            }
            if (in_array($this->post('subject_type'), ['theory', 'both'])) {
                $data['theory_max_marks'] = $this->post("theory_max_marks");
                $data['theory_min_marks'] = $this->post("theory_min_marks");
            }
            $this->response(
                'status',
                $this->db->insert('subjects', $data)
            );
        }
    }
    function list_subjects()
    {
        $this->db->select('s.*,s.id as cc_id,s.duration as subject_duration, c.course_name')
        ->from('subjects as s')
        ->join('course as c', 's.course_id = c.id')
        ->where('s.isDeleted',0);
        $this->db->where('c.isDeleted', "0");

        if(!empty($_GET['course']) && $_GET['course'] != 'undefined'){
            $this->db->where('s.course_id', $_GET['course']);
        }

        $this->response('data', $this->db->get()->result()); 
    }
    function list_deleted_subjects(){
        $this->response('data',$this->student_model->system_subjects(1)->result());
    }
    function subject_delete()
    {
        $this->db->where('id', $this->post('id'))->update('subjects', ['isDeleted' => 1]);
        $this->response('status', true);
    }
    function parma_subject_delete()
    {
        $get = $this->db->where('subject_id', $this->post('id'))->get('marks_table');
        if ($get->num_rows()) {
            $this->response('error', 'There are ' . $get->num_rows() . ' Marksheets associated with this Subject, first delete them.');
        } else {
            $this->db->where('id', $this->post('id'))->delete('subjects');
            $this->response('status', true);
        }
    }
    /*
    function fetch_course_fees_form()
    {
        $this->response(
            'form',
            $this->parser->parse('course/fees-box', [
                'course_id' => $this->post('course_id')
            ], true)
        );
    }*/
    // for category
    function add_category()
    {
        if ($this->validation()) {
            $this->response(
                'status',
                $this->db->insert('course_category', $this->post())
            );
        }
    }
    function list_category()
    {
        $list = $this->db->get('course_category');
        $data = [];
        if ($list->num_rows())
            $data = $list->result();
        // if()
        $this->response('data', $data);
    }
    function delete_category($category_id = 0)
    {
        // $this->response($_GET);
        if ($category_id) {
            $get = $this->db->where('category_id', $category_id)->get('course');
            if ($get->num_rows()) {
                $this->response('html', 'This Category have ' . $get->num_rows() . ' Course(s), before their subjects delete then delete category');
            } else {
                $this->response(
                    'status',
                    $this->db->where('id', $category_id)->delete('course_category')
                );
                $this->response('html', 'Data Delete successfully.');
            }
        } else
            $this->response('html', 'Action id undefined');
        // $this->response('html',$category_id);
    }
    function list_subjects_html()
    {
        $where = ['course_id' => $this->post('id'), 'isDeleted' => 0];
        $subjects = $this->student_model->course_subject($where);
        if ($subjects->num_rows()) {

            $this->set_data('subjects', $subjects->result_array());
            $this->response('status', true);
            $this->response('html', $this->template('list-course-subjects'));
        } else {
            $this->response('html', alert('Subjects Not Found', 'danger'));
        }
    }
    function update_arrange_subject()
    {
        // $this->response($this->post());
        $data = [];
        if ($this->post('sortedIds')) {
            foreach ($this->post('sortedIds') as $i => $id) {
                $data[] = [
                    'id' => $id,
                    'seq' => ($i + 1)
                ];
            }
            $this->db->update_batch('subjects', $data, 'id');
            $this->response('status', true);
        }
    }
    
    /*Code By Arvind Soni */
    function add_master_subject()
    {
        
        $data = [
            'subject_name' => $this->post("subject_name"),
            'subject_code' => $this->post("subject_code")
        ];

        // Create an array to store information about uploaded files
        $uploadedFiles              = [];
        $errors                     = [];
        $config['upload_path']      = './upload/study_material/';
        $config['allowed_types']    = 'pdf|mp4';
        $config['max_size']         = 10240; // 10 MB max file size
        // Loop through each file
        $this->load->library('upload', $config);
        foreach ($_FILES['study_material']['name'] as $key => $filename) {
            $_FILES['file']['name']     = $_FILES['study_material']['name'][$key];
            $_FILES['file']['type']     = $_FILES['study_material']['type'][$key];
            $_FILES['file']['tmp_name'] = $_FILES['study_material']['tmp_name'][$key];
            $_FILES['file']['error']    = $_FILES['study_material']['error'][$key];
            $_FILES['file']['size']     = $_FILES['study_material']['size'][$key];

            // Initialize the upload library for each file
            $this->load->library('upload', $config);

            // Attempt to upload the file
            if ($this->upload->do_upload('file')) {
                $uploadedFiles[] = $this->upload->data('file_name');// Store file data
            } else {
                $errors[] = $this->upload->display_errors(); // Store errors
            }
        }

        if (count($uploadedFiles) > 0) {
            $data['study_material'] = implode(',', $uploadedFiles);
        }

        $this->response(
            'status',
            $this->db->insert('subject_master', $data)
        );
    }

    function edit_master_subject()
    {
        $data = [
            'subject_code' => $this->post('subject_code'),
            'subject_name' => $this->post('subject_name')
        ];
        // Create an array to store information about uploaded files
        $uploadedFiles              = [];
        $errors                     = [];
        $config['upload_path']      = './upload/study_material/';
        $config['allowed_types']    = 'pdf|mp4';
        $config['max_size']         = 10240; // 10 MB max file size
        // Loop through each file
        //pre($_FILES);
        //die;
        $this->load->library('upload', $config);
        foreach ($_FILES['study_material']['name'] as $key => $filename) {
            $_FILES['file']['name']     = $_FILES['study_material']['name'][$key];
            $_FILES['file']['type']     = $_FILES['study_material']['type'][$key];
            $_FILES['file']['tmp_name'] = $_FILES['study_material']['tmp_name'][$key];
            $_FILES['file']['error']    = $_FILES['study_material']['error'][$key];
            $_FILES['file']['size']     = $_FILES['study_material']['size'][$key];

            // Initialize the upload library for each file
            $this->load->library('upload', $config);

            // Attempt to upload the file
            if ($this->upload->do_upload('file')) {
                $uploadedFiles[] = $this->upload->data('file_name');// Store file data
            } else {
                $errors[] = $this->upload->display_errors(); // Store errors
            }
        }

        if (count($uploadedFiles) > 0) {
            $data['study_material'] = implode(',', $uploadedFiles);
        }

        $this->db->where('id', $this->post('id'))->update('subject_master', $data);
        $this->response('status', true);
    }

    function delete_master_subject()
    {
        $this->db->where('id', $this->post('id'))->update('subject_master', ['isDeleted' => 1]);
        $this->response('status', true);
    }

    function delete_master_subject_file()
    {
        $newArray = [];
        $get = $this->db->where('id', $this->post('id'))->get('subject_master');
        if ($get->num_rows()) {
            $result = $get->result_array();
            $files = $result[0]['study_material'];
            $nfile = explode(",",$files);
            
            foreach($nfile as $k => $v){
                if($this->post('file_name') != $v)
                $newArray[] = $v;
            }
        } 
        if(count($newArray) > 0){
            $study_material = implode(",",$newArray);
            $this->db->where('id', $this->post('id'))->update('subject_master', ['study_material' => $study_material]);
        } else if(count($newArray) == 0){
            $this->db->where('id', $this->post('id'))->update('subject_master', ['study_material' => NULL]);
        }
        $this->response('status', true);
    }

    function list_master_subjects()
    {
        $this->response('data', $this->student_model->system_master_subjects()->result());
    }

    function list_deleted_master_subjects(){
        $this->response('data',$this->student_model->system_master_subjects(1)->result());
    }

    
    function parma_master_subject_delete()
    {
       /* $get = $this->db->where('subject_id', $this->post('id'))->get('marks_table');
        if ($get->num_rows()) {
            $this->response('error', 'There are ' . $get->num_rows() . ' Marksheets associated with this Subject, first delete them.');
        } else {*/
            $this->db->where('id', $this->post('id'))->delete('subject_master');
            $this->response('status', true);
        //}
    }

    function view_course()
    {
        $where = [];
        $where['course_id'] = $this->post('id');
        $where['isDeleted']         = 0;
        $subjects = $this->student_model->course_subject($where);
        $this->response('status', true);
        $this->set_data('subjects', $subjects->result_array());
        $this->response('html', $this->template('view-course'));
    }
}
