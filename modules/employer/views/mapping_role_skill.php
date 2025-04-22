<div class="row">
    <div class="col-md-12">
        <div class="{card_class}">
            <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                data-bs-target="#list_job_role_div">
                <h3 class="card-title">Mapping Role Skill</h3>
                <div class="card-toolbar rotate-180">
                    <i class="ki-duotone ki-down fs-1"></i>
                </div>
            </div>
            <div id="list_job_role_div" class="collapse show">
                <form id="manage_mapping">
                    <div class="form-group mb-4 col-md-4 col-xs-12 col-sm-12 mt-4" style="margin-left: 25px;margin-bottom: -25px;" >
                        <label class="form-label required">Select Role</label>
                        <select class="form-select searchCenter" name="role_id" id="role_id" data-control="select2" data-placeholder="Select Role" >
                            <option></option>
                            <?php
                            $list = $this->employer_model->get_all_role()->result();
                            foreach ($list as $row) {
                                echo '<option value="' . $row->id . '" >' . $row->role . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="card-body">
                        <label class="form-label required">Select Skill's</label>
                        <div id="list_job_role" class="row">
                        <?php
                            $job_skill = $this->db->select('*')->from('job_skill')->where('status',1)->get()->result_array();
                            $html = '';
                            foreach($job_skill as $key => $val){
                                $html .='<div class="form-group col-md-3 mt-2">
                                            <input type="checkbox" name="skill[]" id="skill_'.$val['id'].'" class="form-check-input group_skill" value="'.$val['id'].'"  />
                                            <label class="form-label">'.$val['skill'].'</label>
                                        </div>';
                            }
                            echo $html;
                        ?>
                        </div>
                    </div>
                    <div class="card-footer">
                        {publish_button}
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

