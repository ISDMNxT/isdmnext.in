<div class="row">
    <form action="" class="update-student-batch-and-roll-no">
        <input type="hidden" name="student_id" value="{student_id}">
        <div class="col-md-12">
            <div class="{card_class}">
                <div class="card-header">
                    <h3 class="card-title"> <?= $this->ki_theme->keen_icon('pencil') ?> Update Details</h3>
                    <div class="card-toolbar">
                        <?php if($this->center_model->isCenter()) { ?>
                            <?php if(!empty($change_details['id'])) { ?>
                                <?php if(intval($change_details['status']) == 2) { ?>
                                    <?php
                                        echo $this->ki_theme
                                        ->with_icon('pencil')
                                        ->with_pulse('success')
                                        ->outline_dashed_style('success')
                                        ->button('Update', 'submit');
                                    ?>
                                <?php } else if(intval($change_details['status']) == 3 || intval($change_details['status']) == 4 ) { ?>
                                <a class="btn btn-info btn-xs btn-sm student_request" data-id="{student_id}" >Send Request To Change Details</a>
                                <?php } ?>
                            <?php } else { ?>
                                <a class="btn btn-info btn-xs btn-sm student_request" data-id="{student_id}" >Send Request To Change Details</a>
                            <?php } ?>
                        <?php } else if($this->center_model->isAdmin()) { ?>
                            <?php
                                echo $this->ki_theme
                                ->with_icon('pencil')
                                ->with_pulse('success')
                                ->outline_dashed_style('success')
                                ->button('Update', 'submit');
                            ?>
                        <?php } ?>
   
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="roll_no" class="form-label required">Roll Number</label>
                                <input type="text" name="roll_no" class="form-control" placeholder="Enter Roll Number"
                                    value="{roll_no}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="roll_no" class="form-label required">Batch</label>
                                <select class="form-select" name="batch_id" data-control="select2"
                                    data-placeholder="Select a Course">
                                    <option></option>
                                    <?php
                                    $listBatch = $this->db->get('batch');
                                    foreach ($listBatch->result() as $row) {
                                        echo '<option value="' . $row->id . '" ' . (@$batch_name == $row->batch_name ? 'selected' : '') . '>' . $row->batch_name . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mt-4">
                            <div class="form-group">
                                <label for="" class="foem-label">Course</label>
                                <select name="course_id" data-control="select2" data-placeholder="Select Course"
                                    id="course" class="form-select">
                                    <option></option>
                                    <?php

                                    $list = $this->center_model->get_assign_courses($institute_id);
                                    foreach ($list->result() as $c)
                                        echo "<option value='$c->course_id' ".($c->course_id == $course_id ? 'selected' : '') ." data-kt-rich-content-subcontent='{$c->duration} {$c->duration_type}'>$c->course_name</option>";
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mt-4">
                            <div class="form-group">
                                <label for="" class="foem-label">Admission Date</label>
                                <input type="text" name="admission_date" class="form-control current-date flatpickr-input" value="{admission_date}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>