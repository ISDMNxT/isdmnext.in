<table class="w-100 table table-striped table-bordered border-primary bg-light-primary">
    <thead>
        <tr>
        	<th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Detail's</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    	<?php
		  $job_list = $this->db->select('*')
		                    ->from('received_interview_request')
		                    ->where('employer_id', $this->center_model->loginId())
		                    ->where('job_id', $studentList[0]['job_id'])
		                    ->get()->result_array();
		?>
    	<?php foreach($studentList as $key => $value) { 
    		$sIRDisplay = false;
    		$isFinalApproved = true;
    		$finalStatus = '';
    		if(count($job_list) > 0){
    			foreach($job_list as $key1 => $value1) {
    				if($value['student_id'] == $value1['student_id'] && $value['job_id'] == $value1['job_id']){
                        if($value1['status'] == 'applied'){
                            $sIRDisplay = true;
                        } else {
                            $sIRDisplay = false;
                        }
                        $temp = []; 
                        $temp['student_id'] = $value1['student_id']; 
                        $temp['job_id']     = $value1['job_id'];
                        $temp['id']         = $value1['id']; 
                        $temp['status']     = 'shortlisted'; 

                        $temp1 = []; 
                        $temp1['student_id'] = $value1['student_id']; 
                        $temp1['job_id']     = $value1['job_id'];
                        $temp1['id']         = $value1['id']; 
                        $temp1['status']     = 'rejected'; 
    					
    					$finalStatus = ucfirst($value1['status']);
    				}
    			}
    		}

    	 ?>
    		<tr data-shortlist='<?=json_encode($temp)?>' data-reject='<?=json_encode($temp1)?>'>
    			<td><?= intval($key)+1 ?></td>
	            <td><?= $value['student_name'] ?></td>
	            <td><?php if($isFinalApproved) { echo $value['email']; } else { echo maskEmail($value['email']); } ?></td>
	            <td><?php if($isFinalApproved) { echo $value['contact_number']; } else { echo maskMobileNumber($value['contact_number']); } ?></td>
	            <td>
	            	<span><label><b>Industries</b></label><p><?= $value['industries'] ?></p></span>
	            	<span><label><b>Languages</b></label><p><?= $value['pan_languages'] ?></p></span>
	            	<span><label><b>Key Skills</b></label><p><?= $value['key_skills'] ?></p></span>
	            	<span><label><b>Experience</b></label><p><?= $value['experience'] ?> Year</p></span>
	            </td>
	            <td><?php echo $finalStatus; ?></td>
	            <td>
	            	<?php if($sIRDisplay) { ?>
	            	<div class="btn-group">
		                <a class="shortlist_request badge badge-success" style="cursor: pointer;">Shortlist</a>&nbsp;
                        <a class="reject_request badge badge-danger" style="cursor: pointer;">Reject</a>
                	</div>
                	<?php } ?>
            	</td>
            </tr>
    	<?php } ?>
	</tbody>
</table>