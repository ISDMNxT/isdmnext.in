<div class="row">
    <div class="col-md-12">
        <form id="admit_card_form">
            <div class="{card_class}">
                <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                    data-bs-target="#kt_docs_card_collapsible">
                    <h3 class="card-title">Search</h3>
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
                            <div class="form-group mb-4 col-md-4 col-xs-12 col-sm-12 <?=$boxClass?>">
                                <label class="form-label required">Center</label>

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
                            <div class="form-group col-md-4">
                                <label class="form-label required">Select Student</label>
                                <select name="student_id" id="student_id" data-control="select2"
                                    data-placeholder="Select Stduent" class="form-select">
                                    <option></option>
                                    ?>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row mt-10">
    <div class="col-md-12">
        <div class="{card_class}">
            <div class="card-header">
                <h3 class="card-title">List Student Exam(s)</h3>
            </div>
            <div class="div card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="list-student-exams">
                        <thead>
                            <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                <th>#</th>
                                <th>Student</th>
                                <th>Subject</th>
                                <th>Center</th>
                                <th>Course</th>
                                <th>Exam</th>
                                <th>Attempt Time</th>
                                <th>Result</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script id="formTemplate" type="text/x-handlebars-template">
    <input type="hidden" name="id" value="{{id}}">
    <div class="form-group mb-4">
        <label class="form-label">Subject Name</label>
        : {{subject_name}}
    </div>
    <div class="form-group mb-4">
        <label class="form-label required">Description</label>
        <textarea name="description" placeholder="Description" class="form-control"></textarea>
    </div>
    <div class="form-group mb-4">
        <label class="form-label required">Marks</label>
        <input type="number" name="marks" class="form-control" placeholder="Marks" value="">
    </div>
</script>
