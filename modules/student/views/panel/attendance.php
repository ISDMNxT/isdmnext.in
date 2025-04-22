<div class="row">
    <div class="col-md-12">
        <form action="" id="add_form">
            <div class="{card_class}">
                <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                    data-bs-target="#kt_docs_card_collapsible">
                    <h3 class="card-title">Select Criteria</h3>
                    <div class="card-toolbar rotate-180">
                        <i class="ki-duotone ki-down fs-1"></i>
                    </div>
                </div>
                <div id="kt_docs_card_collapsible" class="collapse show">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <?php
                                    if(!empty($this->session->userdata('student_id'))){
                                        $receiverArray = $this->db->select('s.image,s.roll_no,s.name,s.id,c.course_name,c.id as course_id,c.duration,c.duration_type,ce.institute_name as center_name,ce.id as center_id,s.batch_id')->from('students as s')->join("course as c", "s.course_id = c.id ", 'left')->join('centers as ce', 'ce.id = s.center_id', 'left')->where('s.id', $this->session->userdata('student_id'))->get()->result_array();

                                        if(!empty($receiverArray[0]['id']) && !empty($receiverArray[0]['center_id']) && !empty($receiverArray[0]['course_id']) && !empty($receiverArray[0]['batch_id'])) {
                                    ?>
                                    <input type="hidden" name="student_id" id="student_id" value="<?php echo $receiverArray[0]['id']; ?>">
                                    <input type="hidden" name="center_id" id="center_id" value="<?php echo $receiverArray[0]['center_id']; ?>">
                                    <input type="hidden" name="course_id" id="course_id" value="<?php echo $receiverArray[0]['course_id']; ?>">
                                    <input type="hidden" name="batch_id" id="batch_id" value="<?php echo $receiverArray[0]['batch_id']; ?>">
                                    <?php } } ?>
                                    <label for="attendance_date" class="form-label required">Attendance Date</label>
                                    <input type="text" class="form-control attendance-date" value="<?=$this->ki_theme->date() ?>" id="attendance_date" name="attendance_date"
                                        placeholder="Select Attendance Date">
                                </div>
                            </div>
                            <div class="col-md-2 text-end">
                                <div class="form-group pt-8">
                                    {search_button}
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-12 mt-10 view-list">
       
    </div>
</div>
