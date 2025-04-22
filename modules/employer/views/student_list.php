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
		                    ->from('send_interview_request')
		                    ->where('employer_id', $this->center_model->loginId())
		                    ->where('job_id', $studentList[0]['job_id'])
		                    ->get()->result_array();
		?>
    	<?php foreach($studentList as $key => $value) { $temp = []; $temp['student_id'] = $value['student_id']; $temp['student_name'] = $value['student_name']; $temp['job_id'] = $value['job_id']; $temp['center_id'] = $value['institute_id']; $temp['employer_id'] = $this->center_model->loginId();
    		$sIRDisplay = true;
    		$isFinalApproved = false;
    		$finalStatus = 'N/A';
    		if(count($job_list) > 0){
    			foreach($job_list as $key1 => $value1) {
    				if($value['student_id'] == $value1['student_id'] && $value['job_id'] == $value1['job_id']){
    					$sIRDisplay = false;
    					$finalStatus = ucfirst($value1['student_status']);
    				}

    				if($value['student_id'] == $value1['student_id'] && $value['job_id'] == $value1['job_id'] && $value1['center_status'] == 'approved' && $value1['student_status'] == 'approved' ){
    					$isFinalApproved = true;
    				}
    			}
    		}

    	 ?>
    		<tr data-param='<?=json_encode($temp)?>'>
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
		                <a class="send_request" style="cursor: pointer;">Interview Request</a>
                	</div>
                	<?php } ?>
            	</td>
            </tr>
    	<?php } ?>
	</tbody>
</table>