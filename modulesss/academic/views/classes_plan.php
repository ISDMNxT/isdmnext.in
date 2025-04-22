<div class="row">
    <div class="col-md-12">
        <form action="" id="class_plan" name="class_plan" enctype="multipart/form-data">
            <div class="{card_class}">
                <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                    data-bs-target="#kt_docs_card_collapsible">
                    <h3 class="card-title"><i class="fa fa-plus text-dark fw-bold fs-1"></i> &nbsp;Class Plan</h3>
                    <div class="card-toolbar rotate-180">
                        <i class="ki-duotone ki-down fs-1"></i>
                    </div>
                </div>
                <div id="kt_docs_card_collapsible" class="collapse show">
                    <div class="card-body">
                        <div class="row">
                            <?php
                            $center_id = 0;
                            $boxClass = '';
                            if ($this->center_model->isCenter()) {
                                $center_id = $this->center_model->loginId();
                                $this->db->where('id', $center_id);
                                $boxClass = 'd-none';
                            }

                            ?>
                            <div class="form-group mb-4 col-md-6 <?= $boxClass ?>">
                                <label for="center_id" class="form-label required">Center</label>

                                <select class="form-select" name="center_id" id="center_id" data-control="select2"
                                    data-placeholder="Select a Center"
                                    data-allow-clear="<?= $this->center_model->isAdmin() ?>">
                                    <option></option>
                                    <?php
                                    $list = $this->center_model->get_center(0,'center')->result();
                                    foreach ($list as $row) {
                                        $selected = $center_id == $row->id ? 'selected' : '';
                                        echo '<option value="' . $row->id . '" ' . $selected . ' data-kt-rich-content-subcontent="' . $row->institute_name . '"
                                    data-kt-rich-content-icon="' . $row->image . '">' . $row->name . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="course_id" class="form-label required">Course</label>
                                    <select name="course_id" data-control="select2" data-placeholder="Select Course"
                                        id="course_id" class="form-select">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="batch_id" class="form-label required">Batch</label>
                                    <select name="batch_id" data-control="select2" data-placeholder="Select Batch"
                                        id="batch_id" class="form-select">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="subject_id" class="form-label required">Subject Name</label>
                                    <select name="subject_id" data-control="select2" data-placeholder="Select Subject"
                                        id="subject_id" class="form-select">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="title" class="form-label required">Title</label>
                                    <input type="text" class="form-control" name="title" placeholder="Title" autocomplete="off">
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4" <?php  if(!empty($this->session->userdata('staff_id'))) { ?> style="display:none;" <?php } ?>>
                                <div class="form-group">
                                    <label for="staff_id" class="form-label required">Trainer</label>
                                    <select name="staff_id" data-control="select2" data-placeholder="Select Trainer"
                                        id="staff_id" class="form-select">
                                        <option <?php if(!empty($this->session->userdata('staff_id'))) { ?> value="<?php echo $this->session->userdata('staff_id'); ?>" selected="selected" <?php } ?> ><?php if(!empty($this->session->userdata('staff_id'))) { ?>TRAINER<?php } ?></option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="type" class="form-label required">Type</label>
                                    <select name="type" data-control="select2" data-placeholder="Select Type"
                                        id="type" class="form-select">
                                        <option></option>
                                        <option value="Theory">Theory</option>
                                        <option value="Practical">Practical</option>
                                        <option value="Test">Test</option>
                                        <option value="Assignment">Assignment</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="plan_date" class="form-label required">Date</label>
                                    <input type="text" class="form-control nextDate" name="plan_date" placeholder="Date" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-12 mb-4">
                                <div class="form-group">
                                    <label for=description"" class="form-label required">Description</label>
                                    <textarea name="description" id="description" rows="2" placeholder="Enter Description"
                                        class="form-control "></textarea>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="file" class="form-label mt-4">Notes</label>
                                    <input type="file" class="form-control" id="notes" name="notes" accept=".pdf,.ppt,.doc">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        {publish_button}
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="col-md-12 mt-4">
    <div class="{card_class}">
        <div class="card-header">
            <h3 class="card-title"><i class="fa fa-list text-dark fw-bold fs-1"></i> &nbsp; List All Class Plans</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="list-master-class">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Center</th>
                            <th>Course</th>
                            <th>Subject</th>
                            <th>Title</th>
                            <th>Trainer</th>
                            <th>Date</th>
                            <th>Notes</th>
                            <th width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>