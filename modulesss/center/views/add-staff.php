<div class="row">
    <div class="col-md-12">
        <form id="add_staff_form" action="" method="POST" autocomplete="off">
            <div class="{card_class}">
                <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                    data-bs-target="#kt_docs_card_collapsible">
                    <h3 class="card-title">Staff Form</h3>
                    <div class="card-toolbar rotate-180">
                        <i class="ki-duotone ki-down fs-1"></i>
                    </div>
                </div>
                <div id="kt_docs_card_collapsible" class="collapse show">
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" id="staff_id" name="staff_id" value="<?php if(!empty($data['id'])) { echo $data['id']; } ?>">
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Name</label>
                                <input type="text" id="name" name="name" class="form-control"
                                    placeholder="Enter Name" value="<?php if(!empty($data['id'])) { echo $data['name']; } ?>">
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
                            
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12" <?php if(!empty($data['id'])) { ?> style="display: none;" <?php } ?>>
                                <label class="form-label required">Password</label>
                                <input type="text" id="password" name="password" class="form-control" placeholder="Enter Password" value="<?php if(!empty($data['id'])) { echo $data['password']; } ?>">
                            </div>
                            
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Role</label>
                                <select class="form-select" id="role" name="role" data-control="select2"
                                    data-placeholder="Select a Role">
                                    <option value="">Select Role</option>
                                    <option value="Trainer" <?php if(!empty($data['id']) && $data['role'] == "Trainer") { echo "selected='selected'"; } ?> >Trainer</option>
                                    <option value="Counselor/Receptionist" <?php if(!empty($data['id']) && $data['role'] == "Counselor/Receptionist") { echo "selected='selected'"; } ?>>Counselor/Receptionist</option>
                                    <option value="Accountant" <?php if(!empty($data['id']) && $data['role'] == "Accountant") { echo "selected='selected'"; } ?>>Accountant</option>
                                    <option value="Center Head" <?php if(!empty($data['id']) && $data['role'] == "Center Head") { echo "selected='selected'"; } ?>>Center Head</option>
                                    <option value="Marketing" <?php if(!empty($data['id']) && $data['role'] == "Marketing") { echo "selected='selected'"; } ?>>Marketing</option>
                                    <option value="Placement Cell" <?php if(!empty($data['id']) && $data['role'] == "Placement Cell") { echo "selected='selected'"; } ?>>Placement Cell</option>
                                </select>
                            </div>

                            <div class="row" id="permissionDiv" <?php $per = []; if(!empty($data['id'])) { echo 'style="display: block;"'; $per = json_decode($data['permission'],true); } else { echo 'style="display: none;"'; } ?> >
                                <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                    <label class="form-label required">Role Permission</label>
                                    <table>
                                        <?php foreach($permission as $key => $value){ ?>
                                        <tr>
                                            <td><input type="checkbox" name="permission[]" value="<?php echo $value['type'] ?>" <?php if(in_array($value['type'], $per)) { echo 'checked="true"';  } ?> >&nbsp;<?php echo $value['label'] ?></td>
                                        </tr>
                                        <?php } ?>
                                    </table>
                                </div>
                                
                            </div>

                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Upload Photo</label>
                                <input type="file" id="image" name="image" class="form-control" value="<?php if(!empty($data['id'])) { echo $data['image']; } ?>">
                                <?php if(!empty($data['id'])) { ?> 
                                    <br>
                                    <img src="<?= base_url() ?>upload/<?php if(!empty($data['id'])) { echo $data['image']; } ?>" width="100px;">
                                <?php } ?>
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