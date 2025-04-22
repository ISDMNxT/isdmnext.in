<div class="row">
    <div class="col-md-12">
        <form id="add_employer_form" action="" method="POST" autocomplete="off">
            <div class="{card_class}">
                <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                    data-bs-target="#kt_docs_card_collapsible">
                    <h3 class="card-title">Employer Mgmt</h3>
                    <div class="card-toolbar rotate-180">
                        <i class="ki-duotone ki-down fs-1"></i>
                    </div>
                </div>
                <div id="kt_docs_card_collapsible" class="collapse show">
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="center_number" value="<?= time() ?>">
                            <input type="hidden" name="type" value="employer">
                            <input type="hidden" name="wallet" value="0">
                            <input type="hidden" name="valid_upto" value="<?php echo '31-12-2035'; ?>">
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Employer Head Name</label>
                                <input type="text" name="name" class="form-control"
                                    placeholder="Enter Employer Head Name">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Employer Name</label>
                                <input type="text" name="institute_name" class="form-control"
                                    placeholder="Enter Employer Name">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Designation of employer head</label>
                                <input type="text" name="qualification_of_center_head" class="form-control"
                                    placeholder="Enter Designation of employer head">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Employer Code</label>
                                <input type="text" name="rollno_prefix" class="form-control"
                                    placeholder="Enter Employer Code">
                            </div>
                            <div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12">
                                <label class="form-label required">Employer Full Address</label>
                                <textarea class="form-control" name="center_full_address"
                                    placeholder="Employer Full Address"></textarea>
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Upload Image of employer head</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Pincode</label>
                                <input class="form-control" name="pincode" placeholder="Enter Pincode">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Select State </label>
                                <select class="form-select get_city" name="state_id" data-control="select2"
                                    data-placeholder="Select a State">
                                    <option value="">--Select--</option>
                                    <option></option>
                                    <?php
                                    $state = $this->db->order_by('STATE_NAME', 'ASC')->get('state');
                                    if ($state->num_rows()) {
                                        foreach ($state->result() as $row)
                                            echo '<option value="' . $row->STATE_ID . '">' . $row->STATE_NAME . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12 form-group-city">
                                <label class="form-label required">Select Distric <span id="load"></span></label>
                                <select class="form-select list-cities" name="city_id" data-control="select2"
                                    data-placeholder="Select a Distric">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Whatsapp Number</label>
                                <input type="text" name="whatsapp_number" class="form-control"
                                    placeholder="Enter Whatsapp Number">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Contact Number</label>
                                <input type="text" name="contact_number" class="form-control"
                                    placeholder="Enter Contact Number">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">E-Mail ID</label>
                                <input type="email" name="email_id" class="form-control" placeholder="Enter E-Mail ID">
                            </div>
                            <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
                                <label class="form-label required">Password</label>
                                <input type="text" name="password" class="form-control" placeholder="Enter">
                            </div>

                            <div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12">
                                <label class="form-label required">Website URL</label>
                                <input type="text" name="website" class="form-control"
                                    placeholder="Enter Website URL">
                            </div>

                            <div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12">
                                <label class="form-label required">About Employer</label>
                                <textarea class="aryaeditor" name="about_company" ></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card card-body">
                        <h4>Upload Documents</h4>
                        <div class="row">
                            <div class="col-md-3 mb-4">
                                <div class="form-control">
                                    <label for="signature" class="form-label required">Signature</label>
                                </div>
                            </div>
                            <div class="col-md-9 mb-4">
                                <div class="form-group">
                                    <input type="file" class="form-control" name="signature" id="signature">
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <div class="form-control">
                                    <label for="logo" class="form-label required">Logo</label>
                                </div>
                            </div>
                            <div class="col-md-9 mb-2">
                                <div class="form-group">
                                    <input type="file" class="form-control" name="logo" id="logo">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    {publish_button}
                </div>
            </div>

        </form>
    </div>
</div>