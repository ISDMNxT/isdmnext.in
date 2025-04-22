<style>
        .card:hover {
            border: 2px solid #007bff !important;
        }
    </style>

    <?php
        $package        = $this->db->select('*')->from('emp_packages')->where('status', '1')->where('isDeleted', '0')->get()->result_array();
        $emp_details    = $this->db->select('package_id')->from('emp_packages_trans')->where('status', '1')->where('employer_id', $this->center_model->loginId())->get()->result_array();
        $package_id = 0;
        if(!empty($emp_details[0]['package_id'])){
            $package_id = $emp_details[0]['package_id'];
        }
    ?>
<?php if(empty($package_id)) { ?>
<div class="row">
    <div class="col-md-12">
        <div class="{card_class}">
            <div class="container py-5">
                <h2 class="text-center mb-4">Choose Your Hiring Plan</h2>
                <div class="row justify-content-center">
                    <?php foreach($package as $key => $value) { ?>
                    <div class="col-md-4">
                        <div class="card text-center p-3">
                            <h3><?= $value['packages'] ?></h3>
                            <?php echo $value['packages_details']; ?>
                            <button class="btn btn-primary subscribe-btn" data-plan="<?= $value['packages'] ?>" data-id="<?= $value['id'] ?>">Subscribe</button>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div id="subscription-message" class="text-center mt-4"></div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<?php if(!empty($package_id)) { ?>
<div class="row">
    <div class="col-md-12">
        <div class="{card_class}">
            <div class="container py-5">
                <h2 class="text-center mb-4">Your Selected Plan</h2>
                <div class="row justify-content-center">
                    <?php foreach($package as $key => $value) { if($package_id == $value['id']) { ?>
                    <div class="col-md-4">
                        <div class="card text-center p-3">
                            <h3><?= $value['packages'] ?></h3>
                            <?php echo $value['packages_details']; ?>
                        </div>
                    </div>
                    <?php } } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>