<div class="row">
    <div class="col-md-12">
        <form id="manage_packages" action="" method="POST" autocomplete="off">
            <div class="card">
                <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                    data-bs-target="#job_post_form">
                    <h3 class="card-title">Manage Packages</h3>
                    <div class="card-toolbar rotate-180">
                        <i class="ki-duotone ki-down fs-1"></i>
                    </div>
                </div>
                <div id="job_post_form" class="collapse show">
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" id="packages_id" name="packages_id" value="<?php if(!empty($form['id'])){ echo $form['id']; } else { echo '0'; } ?>">
                            <div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12">
                                <label class="form-label required">Packages Name</label>
                                <input type="text" name="packages" class="form-control" placeholder="Enter Packages Name" value="<?php if(!empty($form['packages'])){ echo $form['packages']; } ?>">
                            </div>

                            <div class="form-group mb-4 col-lg-12 col-xs-12 col-sm-12">
                                <label class="form-label required">Packages Details</label>
                                <textarea name="packages_details" class="form-control" placeholder="Enter Packages Details"><?php if(!empty($form['packages_details'])){ echo $form['packages_details']; } ?></textarea>
                            </div>
                            
                            <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
                                <label class="form-label required">Send Job Interview Request Charges/Student</label>
                                <input type="number" name="interview_charges" class="form-control" min="1" placeholder="Send Job Interview Request Charges/Student" value="<?php if(!empty($form['interview_charges'])){ echo $form['interview_charges']; } ?>">
                            </div>

                            <div class="form-group mb-4 col-lg-6 col-xs-12 col-sm-12">
                                <label class="form-label required">Receive Student Interested Request Charges/Student</label>
                                <input type="number" name="apply_charges" class="form-control" min="1" placeholder="Send Job Interview Request Charges/Student" value="<?php if(!empty($form['apply_charges'])){ echo $form['apply_charges']; } ?>">
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