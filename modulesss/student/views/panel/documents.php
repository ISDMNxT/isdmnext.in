<div class="row">

    <div class="col-md-12">
        <div class="{card_class}">
            <div class="card-header">
                <h3 class="card-title">Documents</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover table-bordered mb-0" data-id="{student_id}">
                    <thead>
                        <tr>
                            <th class="w-50">Document</th>
                            <th class="w-50">View</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Adhaar Details</th>
                            <td>
                                <div class="btn-group">
                                    <?php if($this->center_model->isCenter()) { ?>
                                        <?php if(!empty($change_details['id'])) { ?>
                                            <?php if(intval($change_details['status']) == 2) { ?>
                                                <label class="btn btn-active-info btn-sm border-info border border-1" for="adhar">
                                                    <input type="file" name="adhar_card" class="d-none upload-student-docs"
                                                        accept="image/*,.pdf" id="adhar">
                                                    <i class="fa fa-cloud-upload"></i>
                                                    Change
                                                </label>
                                            <?php } else if(intval($change_details['status']) == 3 || intval($change_details['status']) == 4) { ?>
                                            <a class="btn btn-info btn-xs btn-sm student_request" data-id="{student_id}" >Send Request To Change</a>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <a class="btn btn-info btn-xs btn-sm student_request" data-id="{student_id}" >Send Request To Change</a>
                                        <?php } ?>
                                    <?php } else if($this->center_model->isAdmin()) { ?>
                                        <label class="btn btn-active-info btn-sm border-info border border-1" for="adhar">
                                            <input type="file" name="adhar_card" class="d-none upload-student-docs"
                                                accept="image/*,.pdf" id="adhar">
                                            <i class="fa fa-cloud-upload"></i>
                                            Change
                                        </label>
                                    <?php } ?>
                                    
                                    <?php
                                    if (file_exists('upload/' . $student_aadhar) && $student_aadhar) {
                                        ?>
                                        <a href="{base_url}upload/{student_aadhar}" target="_blank"
                                            class="btn btn-sm btn-active-primary border-primary border border-1">
                                            <i class="fa fa-eye"></i>
                                            View
                                        </a>
                                        <?php
                                    } else {
                                        echo badge('Adhar Card Not Found.', 'danger');
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <?php
                        $decodes = [];
                        if ($student_docs != null) {
                            $decodes = json_decode($student_docs, true);
                        }
                        // pre($decodes);
                        $uploadDocuments = $this->ki_theme->project_config('upload_ducuments');
                        foreach ($uploadDocuments as $key => $doc) {
                            $value = isset($decodes[$key]) ? $decodes[$key] : '';
                            echo '<tr>
                                                <th>';
                            echo empty($doc) ? '<b class="text-danger">UNKNOWN FILE</b>' : $doc;
                            echo '</th>
                                                <td>';

                            if($this->center_model->isCenter()) { 
                                if(!empty($change_details['id'])) { 
                                    if(intval($change_details['status']) == 2) { 
                                        echo '<div class="btn-group">
                                                    <label class="btn btn-active-info btn-sm border-info border border-1"
                                                        for="'.$key.'">
                                                        <input type="file" name="' . $key . '" class="d-none upload-student-docs"
                                                            accept="image/*,.pdf" id="'.$key.'">
                                                        <i class="fa fa-cloud-upload"></i>
                                                        Change
                                                    </label>
                                                    ';  
                                    } else if(intval($change_details['status']) == 3 || intval($change_details['status']) == 4 ) { 
                                    echo '<a class="btn btn-info btn-xs btn-sm student_request" data-id="{student_id}" >Send Request To Change</a>';
                                    } 
                                } else { 
                                    echo '<a class="btn btn-info btn-xs btn-sm student_request" data-id="{student_id}" >Send Request To Change</a>';
                                }
                            } else if($this->center_model->isAdmin()) { 
                                echo '<div class="btn-group">
                                                    <label class="btn btn-active-info btn-sm border-info border border-1"
                                                        for="'.$key.'">
                                                        <input type="file" name="' . $key . '" class="d-none upload-student-docs"
                                                            accept="image/*,.pdf" id="'.$key.'">
                                                        <i class="fa fa-cloud-upload"></i>
                                                        Change
                                                    </label>
                                                    ';        
                             } 
                                                
                            // echo $value;
                            if (file_exists('upload/' . $value) && $value) {
                                echo $this->ki_theme
                                    ->with_icon('eye', 3)
                                    ->with_pulse('primary')
                                    ->outline_dashed_style('primary')
                                    ->set_class('btn-sm')
                                    ->set_attribute([
                                        'target' => '_blank'
                                    ])
                                    ->add_action('View', 'upload/' . $value);
                            } else {
                                //echo badge($doc . ' not Found.', 'danger');
                            }
                            echo '
                                            </div>
                                                </td>
                                        </tr>';
                        }


                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>