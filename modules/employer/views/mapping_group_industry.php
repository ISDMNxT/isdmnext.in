<div class="row">
    <div class="col-md-12">
        <div class="{card_class}">
            <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                data-bs-target="#list_job_role_div">
                <h3 class="card-title">Mapping Group Industry</h3>
                <div class="card-toolbar rotate-180">
                    <i class="ki-duotone ki-down fs-1"></i>
                </div>
            </div>
            <div id="list_job_role_div" class="collapse show">
                <form id="manage_mapping_group">
                    <div class="form-group mb-4 col-md-4 col-xs-12 col-sm-12 mt-4" style="margin-left: 25px;margin-bottom: -25px;" >
                        <label class="form-label required">Select Group</label>
                        <select class="form-select searchCenter" name="group_id" id="group_id" data-control="select2" data-placeholder="Select Group" >
                            <option></option>
                            <?php
                            $list = $this->employer_model->get_all_group()->result();
                            foreach ($list as $row) {
                                echo '<option value="' . $row->id . '" >' . $row->group . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="card-body">
                        <label class="form-label required">Select Industry</label>
                        <div id="list_job_role" class="row">
                        <?php
                            $job_industry = $this->db->select('*')->from('industry')->where('status',1)->get()->result_array();
                            $html = '';
                            foreach($job_industry as $key => $val){
                                $html .='<div class="form-group col-md-6 mt-2">
                                            <input type="checkbox" name="industry[]" id="industry_'.$val['id'].'" class="form-check-input group_industry" value="'.$val['id'].'"  />
                                            <label class="form-label">'.$val['industry'].'</label>
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

