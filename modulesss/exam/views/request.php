<div class="row">
    
    <?php if (!$this->center_model->isMaster()) { ?> 
    <div class="col-md-12">
        <form action="" id="add_request">
            <div class="{card_class}">
                <div class="card-header">
                    <h3 class="card-title">Send Request for Exam</h3>
                </div>
                <div id="kt_docs_card_collapsible" class="collapse show">
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="exam_id" id="exam_id" value="<?= isset($exam['id']) ? $exam['id'] : '' ?>">
                            <input type="hidden" name="submit_type" id="submit_type" value="<?= $type ?>">
                            <div class="form-group col-md-4" <?php if($this->center_model->isCenter()) { ?> style="display: none" <?php } ?> >
                                <label class="form-label required">Center</label>
                                <?php
                                $center_id = 0;
                                if ($this->center_model->isCenter()) {
                                    $center_id = $this->center_model->loginId();
                                    $this->db->where('id', $center_id);
                                }
                                ?>
                                <select class="form-select" id="center_id" name="center_id" data-control="select2"
                                    data-placeholder="Select a Center"
                                    data-allow-clear="<?= $this->center_model->isAdmin() ?>">
                                    <option></option>
                                    <?php
                                    $list = $this->db->where('type', 'center')->get('centers')->result();
                                    foreach ($list as $row) {
                                        $selected = $center_id == $row->id ? 'selected' : '';

                                        if(isset($exam['id']) && !empty($exam['center_id'])) {
                                            $selected = $exam['center_id'] == $row->id ? 'selected' : '';
                                        }

                                        echo '<option value="' . $row->id . '" '.( isset($row->wallet) ? 'data-wallet="'.$row->wallet.'"' : '').' ' . $selected . ' data-kt-rich-content-subcontent="' . $row->institute_name . '"
                                    data-kt-rich-content-icon="' . $row->image . '">' . $row->name . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                           
                            <div class="form-group col-md-4" >
                                <label class="form-label required">Course</label>
                                <?php $courseDuration = ''; $courseDurationType = '';  ?>
                                <select class="form-select" id="course_id" name="course_id" data-control="select2"
                                    data-placeholder="Select a Course" data-allow-clear="true">
                                    <option></option>
                                    <?php
                                        if(!empty($exam['center_id']) && !empty($exam['course_id'])){

                                            $list = $this->center_model->get_assign_courses($exam['center_id']);
                                            foreach ($list->result() as $row){

                                                $selected = $exam['course_id'] == $row->course_id ? 'selected' : '';
                                                echo '<option ' . $selected . ' value="' . $row->course_id . '" data-course_fee="'.$row->course_fee.'" data-kt-rich-content-subcontent="' . $row->duration .' '.$row->duration_type.'">' . $row->course_name . '</option>';

                                                if($type == "A" && !empty($exam['course_id']) && $selected != ''){ 
                                                    $courseDuration = $row->duration; $courseDurationType = $row->duration_type;
                                                }
                                            } 

                                        } else if($this->center_model->isCenter()) {
                                            $list = $this->center_model->get_assign_courses($this->center_model->loginId());
                                            foreach ($list->result() as $row){
                                                echo '<option value="' . $row->course_id . '" data-course_fee="'.$row->course_fee.'" data-kt-rich-content-subcontent="' . $row->duration .' '.$row->duration_type.'">' . $row->course_name . '</option>';
                                            } 
                                        }

                                    
                                    ?>
                                </select>
                                <?php if($type == "A" && !empty($exam['course_id'])){ ?>
                                    <input type="hidden" name="cduration" value="<?= $courseDuration ?>">
                                    <input type="hidden" name="cduration_type" value="<?= $courseDurationType ?>">
                                <?php } ?>
                                
                            </div>
                            
                            <div class="form-group col-md-4" >
                                <label class="form-label required">Batch</label>
                                <select class="form-select" id="batch_id" name="batch_id" data-control="select2"
                                    data-placeholder="Select a Batch" data-allow-clear="true">
                                    <option></option>
                                    <?php
                                        if(!empty($exam['batch_id'])){
                                            $list = $this->center_model->get_center_course_batches($exam['center_id'],$exam['course_id']);
                                            foreach ($list->result() as $row){

                                                $selected = $exam['batch_id'] == $row->id ? 'selected' : '';
                                                echo '<option ' . $selected . ' value="' . $row->id . '" data-kt-rich-content-subcontent="' . $row->duration.'">' . $row->batch_name . '</option>';
                                            } 
                                        } 
                                    ?>
                                </select>
                            </div>

                            <div class="form-group col-md-4 mb-4" >
                                <label class="form-label required">Student</label>
                                <select class="form-select" id="student_id" name="student_id[]" data-control="select2" data-placeholder="Select Student" data-allow-clear="true" multiple="true">
                                    <option></option>
                                    <?php
                                        if(!empty($exam['student_id'])){
                                            $students = json_decode($exam['student_id'],true);
                                            $list = $this->student_model->get_student_via_batch($exam['batch_id']);
                                            foreach ($list->result() as $row){

                                                $selected = in_array($row->student_id,$students) ? 'selected' : '';
                                                echo '<option ' . $selected . ' value="' . $row->student_id . '" data-kt-rich-content-subcontent="' . $row->roll_no.'" data-kt-rich-content-icon="' . base_url().'upload/'.$row->image.'">' . $row->student_name . '</option>';
                                            } 
                                        } 
                                    ?>
                                </select>
                            </div>

                            <div class="form-group col-md-4 mb-4">
                                <label for="" class="form-label required">Select Session</label>
                                <select name="session_id" id="session_id" class="form-select" data-control="select2"
                                    data-placeholder="Select Session ">
                                    <option></option>
                                    <?php
                                    $getSession = $this->db->get('session');
                                    foreach($getSession->result() as $session){
                                        $selected = $exam['session_id'] == $session->id ? 'selected' : '';
                                        echo '<option value="'.$session->id.'" ' . $selected . '>'.$session->title.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="col-md-12 mb-4">
                                <div class="form-group">
                                    <label for="" class="form-label required">Exam Title</label>
                                    <textarea name="exam_title" id="exam-editor" rows="2" placeholder="Enter Title"
                                        class="exam-editor form-control"><?= isset($exam['exam_title']) ? $exam['exam_title'] : '' ?></textarea>
                                </div>
                            </div>

                            <div class="col-md-12 mb-4">
                                <div class="form-group">
                                    <label for="" class="form-label">Description</label>
                                    <textarea name="description" id="" rows="2" placeholder="Enter Description"
                                        class="form-control exam-editor "><?= isset($exam['description']) ? $exam['description'] : '' ?></textarea>
                                </div>
                            </div>
                            <?php if($type == "A" && !empty($exam['course_id']) ){ ?>
                                <div class="form-group col-md-4 mb-4" >
                                    <label class="form-label required">Passing Percentage</label>
                                    <input type="number" class="form-control" id="passing_percentage" name="passing_percentage" placeholder="Passing Percentage" value="<?= isset($exam['passing_percentage']) ? $exam['passing_percentage'] : '' ?>">
                                </div>

                                <div class="form-group col-md-4 mb-4" >
                                    <label class="form-label required">Duration (Hours)</label>
                                    <input type="number" max="24" min="1" class="form-control" id="duration" name="duration" placeholder="Duration (Hours)" value="<?= isset($exam['duration']) ? $exam['duration'] : '' ?>">
                                </div>

                                <div class="form-group col-md-4 mb-4" >
                                    <label class="form-label required">Attemp Count</label>
                                    <select class="form-select" id="attemp_count" name="attemp_count" data-control="select2" data-placeholder="Select Attemp Count" data-allow-clear="true">
                                        <option value="1" <?= ((isset($exam['attemp_count']) && intval($exam['attemp_count']) == 1) || !isset($exam['attemp_count'])) ? 'selected="selected"' : '' ?> >1</option>
                                        <option value="2" <?= (isset($exam['attemp_count']) && intval($exam['attemp_count']) == 2)  ? 'selected="selected"' : '' ?> >2</option>
                                        <option value="3" <?= (isset($exam['attemp_count']) && intval($exam['attemp_count']) == 3)  ? 'selected="selected"' : '' ?> >3</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4 mb-4" >
                                    <label class="form-label required">Start Date</label>
                                    <input type="text" class="form-control date-with-notime" id="start_date" name="start_date" placeholder="dd/mm/yyyy" autocomplete="off" value="<?= (isset($exam['start_date']) && $exam['start_date'] != '0000-00-00') ? date('d-m-Y',strtotime($exam['start_date'])) : '' ?>" >
                                </div>

                                <div class="form-group col-md-4 mb-4" >
                                    <label class="form-label required">End Date</label>
                                    <input type="text" class="form-control date-with-notime" id="end_date" name="end_date" placeholder="dd/mm/yyyy" autocomplete="off" value="<?= (isset($exam['end_date']) && $exam['end_date'] != '0000-00-00') ? date('d-m-Y',strtotime($exam['end_date'])) : '' ?>">
                                </div>

                                <div class="form-group col-md-4 mb-4" >
                                    <label class="form-label required">Negative Marking</label>
                                    <select class="form-select" id="negative_marking" name="negative_marking" data-control="select2" data-placeholder="Select Negative Marking" data-allow-clear="true">
                                       <option value="0" <?= ((isset($exam['negative_marking']) && intval($exam['negative_marking']) == 0) || !isset($exam['negative_marking'])) ? 'selected="selected"' : '' ?> >No</option>
                                        <option value="1" <?= (isset($exam['negative_marking']) && intval($exam['negative_marking']) == 1)  ? 'selected="selected"' : '' ?> >Yes</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4 mb-4" >
                                    <label class="form-label required">Random Question</label>
                                    <select class="form-select" id="random_question" name="random_question" data-control="select2" data-placeholder="Select Random Question" data-allow-clear="true">
                                        <option value="0" <?= ((isset($exam['random_question']) && intval($exam['random_question']) == 0) || !isset($exam['random_question'])) ? 'selected="selected"' : '' ?> >No</option>
                                        <option value="1" <?= (isset($exam['random_question']) && intval($exam['random_question']) == 1)  ? 'selected="selected"' : '' ?> >Yes</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4 mb-4" >
                                    <label class="form-label required">Expiry Days</label>
                                    <input type="number" class="form-control" id="expiry_days" name="expiry_days" placeholder="Expiry Days" value="<?= isset($exam['expiry_days']) ? $exam['expiry_days'] : '' ?>">
                                </div>
                                <div class="form-group col-md-4 mb-4" >
                                    <label class="form-label required">Option Shuffle</label>
                                    <select class="form-select" id="option_shuffle" name="option_shuffle" data-control="select2" data-placeholder="Select Option Shuffle" data-allow-clear="true">
                                        <option value="0" <?= ((isset($exam['option_shuffle']) && intval($exam['option_shuffle']) == 0) || !isset($exam['option_shuffle'])) ? 'selected="selected"' : '' ?> >No</option>
                                        <option value="1" <?= (isset($exam['option_shuffle']) && intval($exam['option_shuffle']) == 1)  ? 'selected="selected"' : '' ?> >Yes</option>
                                    </select>
                                </div>

                                <?php $spAr = []; ?>
                                <div class="row">
                                    <table>
                                        <tr>
                                            <th>Subject</th>
                                            <th>Paper</th>
                                        </tr>
                                    
                                <?php $listSubject = $this->db->select('s.*,sm.subject_name')->from('subjects as s')->join('subject_master as sm', 's.subject_id = sm.id', 'left')->where('s.isDeleted', 0)->where('s.course_id', $exam['course_id'])->get();
                                    foreach ($listSubject->result() as $s){ 
                                        if($s->subject_type == 'theory' || $s->subject_type == 'practical'){
                                            if($s->subject_type == 'theory'){
                                                $listPaper = $this->db->select('qp.*')->from('question_paper as qp')->where('qp.subject_id', $s->subject_id);
                                                $listPaper = $listPaper->where('qp.paper_type', 'theortical');
                                            } else if($s->subject_type == 'practical'){
                                                $listPaper = $this->db->select('qp.*')->from('question_paper as qp')->where('qp.subject_id', $s->subject_id);
                                                $listPaper = $listPaper->where('qp.paper_type', 'practical');
                                            }
                                            $listPaper = $listPaper->get();
                                            $listPaper1 = $listPaper;
                                            ?>
                                            <tr>
                                                <td>
                                                    <label class="form-label required"><?= $s->subject_name ?></label>
                                                    
                                                    <input type="hidden" name="s_id[]" id="s_id<?= $s->subject_id ?>" value="<?= $s->subject_id ?>">
                                                </td>
                                                <td>
                                                    <?php foreach ($listPaper1->result() as $p){ 
                                                        $spAr[$s->subject_id][$p->id] = $p->total_marks;
                                                    } ?>
                                                    <select name="p_id[]" id="p_id<?= $s->subject_id ?>">
                                                        <option value="">Select Paper</option>
                                                        <?php foreach ($listPaper->result() as $p){ ?>
                                                            <option value="<?= $p->id ?>"><?= $p->paper_name ?> - <?= $p->paper_type ?>(<?= $p->total_marks ?>)</option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php } else { ?>
                                            <?php 
                                            $listPaper = $this->db->select('qp.*')->from('question_paper as qp')->where('qp.subject_id', $s->subject_id);
                                            $listPaper = $listPaper->get();
                                            $listPaper1 = $listPaper;
                                            ?>
                                            <tr>
                                                <td>
                                                    <label class="form-label required"><?= $s->subject_name ?> (Theortical)</label>
                                                    
                                                    <input type="hidden" name="s_id[]" id="s_id<?= $s->subject_id ?>" value="<?= $s->subject_id ?>">
                                                </td>
                                                <td>
                                                    <?php foreach ($listPaper1->result() as $p){ 
                                                        $spAr[$s->subject_id][$p->id] = $p->total_marks;
                                                    } ?>
                                                    <select name="p_id[]" id="p_id<?= $s->subject_id ?>">
                                                        <option value="">Select Paper</option>
                                                        <?php foreach ($listPaper->result() as $p){ if($p->paper_type == 'theortical') { ?>
                                                            <option value="<?= $p->id ?>"><?= $p->paper_name ?> - <?= $p->paper_type ?>(<?= $p->total_marks ?>)</option>
                                                        <?php } } ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="form-label required"><?= $s->subject_name ?> (Practical)</label>
                                                    
                                                    <input type="hidden" name="s_id[]" id="s_id<?= $s->subject_id ?>" value="<?= $s->subject_id ?>">
                                                </td>
                                                <td>
                                                    <select name="p_id[]" id="p_id<?= $s->subject_id ?>">
                                                        <option value="">Select Paper</option>
                                                        <?php foreach ($listPaper->result() as $p){ if($p->paper_type == 'practical') { ?>
                                                            <option value="<?= $p->id ?>"><?= $p->paper_name ?> - <?= $p->paper_type ?>(<?= $p->total_marks ?>)</option>
                                                        <?php } } ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                    </table>
                                </div>
                                <input type="hidden" name="s_p" value='<?= json_encode($spAr); ?>' />
                            <?php } ?>
                        </div>
                    </div>
                    <div class="card-footer">
                            <?php if($type == ""){ ?>
                                {send_button}
                            <?php } else if($type == "E"){ ?>
                                {update_button}
                            <?php } else if($type == "A"){ ?>
                                {appr_button}
                            <?php } else if($type == "R"){ ?>
                                {rejt_button}
                            <?php } ?>

                            
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php } ?>
    <div class="col-md-12 mt-5">
        <div class="{card_class}">
            <div class="card-header">
                <h3 class="card-title">List Request(s)</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="requestTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Exam Title</th>
                                <th>Center</th>
                                <th>Course</th>
                                <th>Batch</th>
                                <!-- <th>Start Date</th>
                                <th>End Date</th> -->
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>