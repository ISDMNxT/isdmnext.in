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
                            <div class="col-md-5" <?php if($this->center_model->isCenter()) { ?> style="display: none" <?php } ?>>
                                <div class="form-group">
                                    <label for="course" class="form-label required">Select Center</label>
                                    <?php
                                    $center_id = 0;
                                    if ($this->center_model->isCenter()) {
                                        $center_id = $this->center_model->loginId();
                                        $this->db->where('id', $center_id);
                                    }
                                    ?>
                                    <select class="form-select" id="centre_id" name="center_id" data-control="select2"
                                        data-placeholder="Select a Center"
                                        data-allow-clear="<?= $this->center_model->isAdmin() ?>">
                                        <option></option>
                                        <?php
                                        $list = $this->db->where('type', 'center')->get('centers')->result();
                                        foreach ($list as $row) {
                                            $selected = $center_id == $row->id ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" '.( isset($row->wallet) ? 'data-wallet="'.$row->wallet.'"' : '').' ' . $selected . ' data-kt-rich-content-subcontent="' . $row->institute_name . '"
                                        data-kt-rich-content-icon="' . $row->image . '">' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="course" class="form-label required">Select Course</label>
                                    <select class="form-select" name="course_id" id="course_id" data-control="select2"
                                    data-placeholder="Select a Course" data-allow-clear="true">
                                    <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="course" class="form-label required">Select Batch</label>
                                    <select class="form-select" name="batch_id" id="batch_id" data-control="select2"
                                        data-placeholder="Select a Batch">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="attendance_date" class="form-label required">Attendance Date</label>
                                    <input type="text" class="form-control attendance-date" value="<?=$this->ki_theme->date() ?>"
                                        id="attendance_date" name="attendance_date"
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
