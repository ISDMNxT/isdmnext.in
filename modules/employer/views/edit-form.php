<?php
if($valid_upto){
    $valid_upto = date('Y-m-d', strtotime($valid_upto));
}
?>
<form action="center/update">
    <input type="hidden" name="id" value="{id}">
    <div class="row">
        <input type="hidden" name="center_number" value="<?= time() ?>">
        <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
            <label class="form-label required">Employer Head Name</label>
            <input type="text" value="{name}" name="name" class="form-control" placeholder="Enter Employer Head Name">
        </div>
        <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
            <label class="form-label required">Employer Name</label>
            <input type="text" value="{institute_name}" name="institute_name" class="form-control"
                placeholder="Enter Employer Name">
        </div>
        <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
            <label class="form-label required">Designation of employer head</label>
            <input type="text" value="{qualification_of_center_head}" name="qualification_of_center_head"
                class="form-control" placeholder="Enter Designation of employer head">
        </div>
        <div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12">
            <label class="form-label required">Employer Full Address</label>
            <textarea class="form-control" name="center_full_address"
                placeholder="Employer Full Address">{center_full_address}</textarea>
        </div>
        <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
            <label class="form-label required">Pincode</label>
            <input class="form-control" value="{pincode}" name="pincode" placeholder="Enter Pincode">
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
                        echo '<option value="' . $row->STATE_ID . '" ' . ($row->STATE_ID == $state_id ? 'selected' : '') . '>' . $row->STATE_NAME . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12 form-group-city">
            <label class="form-label required">Select Distric <span id="load"></span></label>
            <select class="form-select list-cities" name="city_id" data-control="select2"
                data-placeholder="Select a Distric">
                <option></option>
                <?php
                $getCities = $this->SiteModel->get_city(['STATE_ID' => $state_id]);
                if ($getCities->num_rows()) {
                    foreach ($getCities->result() as $city) {
                        echo '<option value="' . $city->DISTRICT_ID . '"  ' . ($city->DISTRICT_ID == $city_id ? 'selected' : '') . '>' . $city->DISTRICT_NAME . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
            <label class="form-label required">Whatsapp Number</label>
            <input type="text" name="whatsapp_number" value="{whatsapp_number}" class="form-control"
                placeholder="Enter Whatsapp Number">
        </div>
        <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
            <label class="form-label required">Contact Number</label>
            <input type="text" name="contact_number" value="{contact_number}" class="form-control"
                placeholder="Enter Contact Number">
        </div>
        <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
            <label class="form-label required">E-Mail ID</label>
            <input type="email" name="email" value="{email}" class="form-control" placeholder="Enter E-Mail ID">
        </div>
        <!-- <div class="form-group mb-4 col-lg-3 col-xs-12 col-sm-12">
            <label class="form-label required">Valid Upto</label>
            <input type="date" name="valid_upto" value="<?=$valid_upto?>" class="form-control selectdate"
                placeholder="Select A Date">
        </div> -->
        <div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12">
            <label class="form-label required">Website URL</label>
            <input type="text" name="website" value="{website}" class="form-control"
                placeholder="Enter Website URL">
        </div>

        <div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12">
            <label class="form-label required">About Employer</label>
            <textarea class="form-control" name="about_company"
                placeholder="About Employer">{about_company}</textarea>
        </div>
        <div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12">
            <label class="form-label required">Profile Status</label>
            <select class="form-select" name="status" data-control="select2" data-placeholder="Select an option">
                <option value="0" <?= $status == '0' ? 'selected' : '' ?>>Un-Verified</option>
                <option value="1" <?= $status == '1' ? 'selected' : '' ?>>Verified</option>
            </select>
        </div>
        
    </div>
</form>
