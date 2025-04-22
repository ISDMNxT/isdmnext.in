<div class="row">
    <div class="col-md-12">
        <form id="add_franchise_form" action="" method="POST" autocomplete="off">
            <div class="{card_class}">
                <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                    data-bs-target="#kt_docs_card_collapsible">
                    <h3 class="card-title">Master Franchise Form</h3>
                    <div class="card-toolbar rotate-180">
                        <i class="ki-duotone ki-down fs-1"></i>
                    </div>
                </div>
                <div id="kt_docs_card_collapsible" class="collapse show">
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" id="franchise_id" name="franchise_id" value="<?php if(!empty($data['id'])) { echo $data['id']; } ?>">
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Name</label>
                                <input type="text" id="name" name="name" class="form-control"
                                    placeholder="Enter Name" value="<?php if(!empty($data['id'])) { echo $data['name']; } ?>">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Master Franchise Name</label>
                                <input type="text" id="institute_name" name="institute_name" class="form-control"
                                    placeholder="Enter Master Franchise Name" value="<?php if(!empty($data['id'])) { echo $data['institute_name']; } ?>">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Contact Number</label>
                                <input type="text" id="contact_number" name="contact_number" class="form-control"
                                    placeholder="Enter Contact Number" value="<?php if(!empty($data['id'])) { echo $data['contact_number']; } ?>">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">E-Mail</label>
                                <input type="email" id="email_id" name="email_id" class="form-control" placeholder="Enter E-Mail" value="<?php if(!empty($data['id'])) { echo $data['email']; } ?>">
                            </div>

                            <div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12">
                                <label class="form-label required">Address</label>
                                <textarea id="center_full_address" name="center_full_address" class="form-control" placeholder="Enter Address"><?php if(!empty($data['id'])) { echo $data['center_full_address']; } ?></textarea>
                            </div>

                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Pincode</label>
                                <input class="form-control" name="pincode" placeholder="Enter Pincode" value="<?php if(!empty($data['id'])) { echo $data['pincode']; } ?>">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Select State </label>
                                <select class="form-select get_city" name="state_id" data-control="select2"
                                    data-placeholder="Select a State">
                                    <option value="" >--Select--</option>
                                    <option></option>
                                    <?php 
                                    $state = $this->db->order_by('STATE_NAME', 'ASC')->get('state');
                                    if ($state->num_rows()) {
                                        foreach ($state->result() as $row){
                                            $sel = "";
                                            if(!empty($data['id']) && (intval($row->STATE_ID) == intval($data['state_id']))) { $sel = "selected='selected'"; } 
                                            echo '<option value="' . $row->STATE_ID . '" '.$sel.' >' . $row->STATE_NAME . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12 form-group-city">
                                <label class="form-label required">Select Distric <span id="load"></span></label>
                                <input type="hidden" id="tdistrict" value="<?php if(!empty($data['id'])) { echo $data['city_id']; } ?>">
                                <select class="form-select list-cities" name="city_id" data-control="select2"
                                    data-placeholder="Select a City">
                                    <option></option>

                                </select>
                            </div>

                            
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12" <?php if(!empty($data['id'])) { ?> style="display: none;" <?php } ?>>
                                <label class="form-label required">Password</label>
                                <input type="text" id="password" name="password" class="form-control" placeholder="Enter Password" value="<?php if(!empty($data['id'])) { echo $data['password']; } ?>">
                            </div>
                            
                            <div class="row" <?php $per = []; if(!empty($data['id'])) { $per = json_decode($data['permission'],true); } ?> >
                                <div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12">
                                    <label class="form-label required">Assign Institutes</label>
                                    <table>
                                        <?php 
                                        $list = $this->center_model->get_all_center()->result();
                                        foreach($list as $key => $row){ ?>
                                        <tr>
                                            <td><input type="checkbox" name="permission[]" value="<?php echo $row->id ?>" <?php if(in_array($row->id, $per)) { echo 'checked="true"';  } ?> >&nbsp;<?php echo $row->institute_name ?></td>
                                        </tr>
                                        <?php } ?>
                                    </table>
                                </div>
                                
                            </div>

                            <div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12">
                                <label class="form-label required">Upload Logo</label>
                                <input type="file" id="image" name="image" class="form-control" value="<?php if(!empty($data['id'])) { echo $data['image']; } ?>">
                                <?php if(!empty($data['id'])) { ?> 
                                    <br>
                                    <img src="<?= base_url() ?>upload/<?php if(!empty($data['id'])) { echo $data['image']; } ?>" width="100px;">
                                <?php } ?>
                            </div>

                            <div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12">
                                <label class="form-label required">Wallet Recharge Transaction Commission (%)</label>
                                <input type="number" id="earning_percent" name="earning_percent" class="form-control"
                                    placeholder="Enter Wallet Recharge Transaction Percent(%)" value="<?php if(!empty($data['id'])) { echo $data['earning_percent']; } ?>">
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="card-footer">
                    <?php if(!empty($data['id'])) { ?>
                        {update_button}
                    <?php } else { ?>
                        {publish_button}
                    <?php } ?>
                </div>
            </div>

        </form>

    </div>
</div>