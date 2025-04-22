<?php
if ($this->center_model->isAdminOrCenter()) {
    ?>
    <form id="send_notification_new" action="" class="send-notification-new">
        <input type="hidden" id="receiver_user" name="receiver_user" value="<?php echo $ntype; ?>">
        <div class="col-md-12">
            <div class="{card_class}">
                <div class="card-header">
                    <h3 class="card-title"> <?= $this->ki_theme->keen_icon('notification') ?> Send Notification</h3>
                    <div class="card-toolbar">
                        {send_notification}
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        
                        <?php if($this->center_model->isAdmin() && $ntype == 'center'){ 
                            $center_id      = 0;
                            $center_type    = 'selected';
                            $boxClass       = '';
                            if ($this->center_model->isCenter()) {
                                $center_id = $this->center_model->loginId();
                                $this->db->where('id', $center_id);
                                $boxClass = 'd-none';
                            } 
                            ?>
                            <div class="col-md-6 form-group mt-4 <?= $boxClass ?>">
                                <label class="form-label required">Center Type</label>
                                <select class="form-select" name="center_type" id="center_type" data-control="select2"
                                    data-placeholder="Select Center Type"
                                    data-allow-clear="<?= $this->center_model->isAdmin() ?>">
                                    <option value="selected" <?php if($center_type == 'selected') { echo "selected='selected'"; } ?> >To Selected Center</option>
                                    <option value="all" >All Center</option>
                                    <option value="0" >Active Center</option>
                                    <option value="1" >Inactive Center</option>
                                </select>
                            </div>
                            
                            <div id="center_id_div" class="col-md-6 form-group <?= $boxClass ?> mt-4">
                                <label class="form-label required">Center</label>

                                <select class="form-select" name="center_id" id="center_id" data-control="select2"
                                    data-placeholder="Select a Center"
                                    data-allow-clear="<?= $this->center_model->isAdmin() ?>">
                                    <option></option>
                                    <?php
                                    $list = $this->db->where('type', 'center')->get('centers')->result();
                                    //$list = $this->center_model->get_center(0,'center')->result();
                                    foreach ($list as $row) {
                                        $selected = $center_id == $row->id ? 'selected' : '';
                                        echo '<option value="' . $row->id . '" ' . $selected . ' data-kt-rich-content-subcontent="' . $row->institute_name . '"
                                    data-kt-rich-content-icon="' . $row->image . '">' . $row->name . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        <?php } ?>

                        <?php if($this->center_model->isAdmin() && $ntype == 'student'){ 
                            $student_id      = 0;
                            $student_type    = 'selected';
                            $clist = $this->db->where('type', 'center')->get('centers')->result();
                            $centerList = [];
                            foreach ($clist as $row) {
                                $centerList[$row->id] = $row->institute_name;
                            }
                            ?>
                            <div class="col-md-6 form-group mt-4">
                                <label class="form-label required">Student Type</label>
                                <select class="form-select" name="student_type" id="student_type" data-control="select2"
                                    data-placeholder="Select Student Type">
                                    <option value="selected" <?php if($student_type == 'selected') { echo "selected='selected'"; } ?> >To Selected Student</option>
                                    <option value="all" >All Students</option>
                                    <option value="1" >Approve Students</option>
                                    <option value="0" >Pending Students</option>
                                    <option value="2" >Cancel Students</option>
                                    <option value="center" >Centerwise Students</option>
                                    <option value="course" >Coursewise Students</option>
                                    <option value="batch" >Batchwise Students</option>
                                </select>
                            </div>
                            <div id="studentDiv" class="form-group col-md-6 mt-4">
                                <label class="form-label required">Select Student</label>
                                <select name="student_id" id="student_id" data-control="select2" data-placeholder="Select Student" class="form-select" data-allow-clear="<?= $this->center_model->isAdmin() ?>">
                                    <option></option>
                                    <?php
                                    $list = $this->db->select('s.image,s.roll_no,s.name,s.id,c.course_name,c.id as course_id,c.duration,c.duration_type,ce.institute_name as center_name,ce.id as center_id')->from('students as s')->join("course as c", "s.course_id = c.id ", 'left')->join('centers as ce', 'ce.id = s.center_id', 'left')->get()->result();

                                    //$list = $this->center_model->get_center(0,'center')->result();
                                    foreach ($list as $student) { if(intval($student->course_id) > 0) { ?>
                                        <option 
                                            value="<?php echo $student->id; ?>"
                                            data-kt-rich-content-subcontent="<?php echo $student->roll_no .' ('.$centerList[$student->center_id] . ')'; ?>"
                                            data-kt-rich-content-icon="<?php echo $student->image; ?>"
                                            data-course_duration="<?php echo $student->duration; ?>"
                                            data-course_duration_type="<?php echo $student->duration_type; ?>"
                                            data-course_id="<?php echo $student->course_id; ?>"
                                            data-center_id="<?php echo $student->center_id; ?>"
                                            data-course_name="<?php echo $student->course_name; ?>" >
                                            <?php echo $student->name; ?>
                                        </option>
                                    <?php } } ?>
                                </select>
                            </div>
                            <div id="centerDiv" class="col-md-6 form-group mt-4" style="display: none;">
                                <label class="form-label required">Center</label>
                                <select class="form-select" name="center_id" id="center_id" data-control="select2"
                                    data-placeholder="Select a Center" data-allow-clear="<?= $this->center_model->isAdmin() ?>">
                                    <option></option>
                                    <?php
                                    foreach ($clist as $row) {
                                        echo '<option value="' . $row->id . '" data-kt-rich-content-subcontent="' . $row->institute_name . '"
                                    data-kt-rich-content-icon="' . $row->image . '">' . $row->name . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div id="courseDiv" class="form-group col-md-6 mt-4" style="display: none;">
                                <label class="form-label required">Select Course</label>
                                <select name="course_id" id="course_id" data-control="select2" data-placeholder="Select Course" class="form-select">
                                    <option></option>
                                    <?php
                                        $list = $this->db->where('status', 1)->get('course');
                                        foreach ($list->result() as $c)
                                        echo "<option value='$c->id' data-duration='$c->duration' data-durationType='$c->duration_type'>$c->course_name</option>";
                                    ?>
                                </select>
                            </div>
                            <div id="batchDiv" class="form-group col-md-6 mt-4" style="display: none;">
                                <label class="form-label required">Select Batch</label>
                                <select name="batch_id" id="batch_id" data-control="select2" data-placeholder="Select Batch" class="form-select">
                                    <option></option>
                                    <?php
                                        $list = $this->db->get('batch');
                                        foreach ($list->result() as $c)
                                        echo "<option value='$c->id' >$c->batch_name</option>";
                                    ?>
                                </select>
                            </div>
                        <?php } ?>

                        <?php if($this->center_model->isAdmin() && $ntype == 'staff'){ 
                            $staff_id      = 0;
                            $staff_type    = 'selected';
                            $clist = $this->db->where('type', 'center')->get('centers')->result();
                            $centerList = [];
                            foreach ($clist as $row) {
                                $centerList[$row->id] = $row->institute_name;
                            }

                            $statusList = [0 => 'Active', 1 => 'Inactive'];

                            ?>
                            <div class="col-md-6 form-group mt-4">
                                <label class="form-label required">Staff Type</label>
                                <select class="form-select" name="staff_type" id="staff_type" data-control="select2"
                                    data-placeholder="Select Staff Type">
                                    <option value="selected" <?php if($staff_type == 'selected') { echo "selected='selected'"; } ?> >To Selected Staff</option>
                                    <option value="all" >All Staff</option>
                                    <option value="0" >Active Staff</option>
                                    <option value="1" >Inactive Staff</option>
                                    <option value="center" >Centerwise Staff</option>
                                    <option value="role" >Rolewise Staff</option>
                                    <option value="center_role" >CenterRolewise Staff</option>
                                </select>
                            </div>
                            <div id="staffDiv" class="form-group col-md-6 mt-4">
                                <label class="form-label required">Select Staff</label>
                                <select name="staff_id" id="staff_id" data-control="select2" data-placeholder="Select Staff" class="form-select" data-allow-clear="<?= $this->center_model->isAdmin() ?>">
                                    <option></option>
                                    <?php
                                    $list = $this->db->where('type', 'staff')->get('centers')->result();
                                    foreach ($list as $row) {
                                        echo '<option value="' . $row->id . '" data-kt-rich-content-subcontent="' . $row->role .' ('.$centerList[$row->parent_center_id] . ')"
                                    data-kt-rich-content-icon="' . $row->image . '">' . $row->name . ' ('.$statusList[$row->isDeleted] .')</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div id="centerDiv" class="col-md-6 form-group mt-4" style="display: none;">
                                <label class="form-label required">Center</label>
                                <select class="form-select" name="center_id" id="center_id" data-control="select2"
                                    data-placeholder="Select a Center" data-allow-clear="<?= $this->center_model->isAdmin() ?>">
                                    <option></option>
                                    <?php
                                    foreach ($clist as $row) {
                                        echo '<option value="' . $row->id . '" data-kt-rich-content-subcontent="' . $row->institute_name . '"
                                    data-kt-rich-content-icon="' . $row->image . '">' . $row->name . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div id="roleDiv" class="form-group col-md-6 mt-4" style="display: none;">
                                <label class="form-label required">Select Role</label>
                                <select name="role" id="role" data-control="select2" data-placeholder="Select Role" class="form-select">
                                    <option></option>
                                    <option value="">Select Role</option>
                                    <option value="Trainer" >Trainer</option>
                                    <option value="Counselor/Receptionist" >Counselor/Receptionist</option>
                                    <option value="Accountant" >Accountant</option>
                                    <option value="Center Head" >Center Head</option>
                                    <option value="Marketing" >Marketing</option>
                                    <option value="Placement Cell" >Placement Cell</option>
                                </select>
                            </div>
                        <?php } ?>

                        <?php if($this->center_model->isCenter() && $ntype == 'student'){ 
                            $student_id      = 0;
                            $student_type    = 'selected';
                            $center_id = 0;
                            if ($this->center_model->isCenter()) {
                                $center_id = $this->center_model->loginId();
                            } 
                            ?>
                            <div class="col-md-6 form-group mt-4">
                                <label class="form-label required">Student Type</label>
                                <select class="form-select" name="student_type" id="student_type" data-control="select2"
                                    data-placeholder="Select Student Type">
                                    <option value="selected" <?php if($student_type == 'selected') { echo "selected='selected'"; } ?> >To Selected Student</option>
                                    <option value="all" >All Students</option>
                                    <option value="1" >Approve Students</option>
                                    <option value="0" >Pending Students</option>
                                    <option value="2" >Cancel Students</option>
                                    <option value="course" >Coursewise Students</option>
                                    <option value="batch" >Batchwise Students</option>
                                </select>
                            </div>
                            <div id="studentDiv" class="form-group col-md-6 mt-4">
                                <label class="form-label required">Select Student</label>
                                <select name="student_id" id="student_id" data-control="select2" data-placeholder="Select Student" class="form-select" data-allow-clear="<?= $this->center_model->isCenter() ?>">
                                    <option></option>
                                    <?php
                                    $list = $this->db->select('s.image,s.roll_no,s.name,s.id,c.course_name,c.id as course_id,c.duration,c.duration_type,ce.institute_name as center_name,ce.id as center_id')->from('students as s')->join("course as c", "s.course_id = c.id ", 'left')->join('centers as ce', 'ce.id = s.center_id', 'left')->where('s.center_id', $center_id)->get()->result();

                                    foreach ($list as $student) { ?>
                                        <option 
                                            value="<?php echo $student->id; ?>"
                                            data-kt-rich-content-subcontent="<?php echo $student->roll_no; ?>"
                                            data-kt-rich-content-icon="<?php echo $student->image; ?>"
                                            data-course_duration="<?php echo $student->duration; ?>"
                                            data-course_duration_type="<?php echo $student->duration_type; ?>"
                                            data-course_id="<?php echo $student->course_id; ?>"
                                            data-center_id="<?php echo $student->center_id; ?>"
                                            data-course_name="<?php echo $student->course_name; ?>" >
                                            <?php echo $student->name; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            
                            <div id="courseDiv" class="form-group col-md-6 mt-4" style="display: none;">
                                <label class="form-label required">Select Course</label>
                                <select name="course_id" id="course_id" data-control="select2" data-placeholder="Select Course" class="form-select">
                                    <option></option>
                                    <?php
                                        $list = $this->db->select('c.id,c.duration,c.duration_type,c.course_name')->from('course as c')->join("center_courses as cc", "cc.course_id = c.id ", 'left')->where('c.status', 1)->where('cc.center_id', $center_id)->get();
                                        foreach ($list->result() as $c)
                                        echo "<option value='$c->id' data-duration='$c->duration' data-durationType='$c->duration_type'>$c->course_name</option>";
                                    ?>
                                </select>
                            </div>
                            <div id="batchDiv" class="form-group col-md-6 mt-4" style="display: none;">
                                <label class="form-label required">Select Batch</label>
                                <select name="batch_id" id="batch_id" data-control="select2" data-placeholder="Select Batch" class="form-select">
                                    <option></option>
                                    <?php
                                        $list = $this->db->get('batch');
                                        foreach ($list->result() as $c)
                                        echo "<option value='$c->id' >$c->batch_name</option>";
                                    ?>
                                </select>
                            </div>
                        <?php } ?>

                        <?php if($this->center_model->isCenter() && $ntype == 'staff'){ 
                            $staff_id      = 0;
                            $staff_type    = 'selected';
                            $center_id = 0;
                            if ($this->center_model->isCenter()) {
                                $center_id = $this->center_model->loginId();
                            } 

                            $statusList = [0 => 'Active', 1 => 'Inactive'];

                            ?>
                            <div class="col-md-6 form-group mt-4">
                                <label class="form-label required">Staff Type</label>
                                <select class="form-select" name="staff_type" id="staff_type" data-control="select2"
                                    data-placeholder="Select Staff Type">
                                    <option value="selected" <?php if($staff_type == 'selected') { echo "selected='selected'"; } ?> >To Selected Staff</option>
                                    <option value="all" >All Staff</option>
                                    <option value="0" >Active Staff</option>
                                    <option value="1" >Inactive Staff</option>
                                    <option value="role" >Rolewise Staff</option>
                                </select>
                            </div>
                            <div id="staffDiv" class="form-group col-md-6 mt-4">
                                <label class="form-label required">Select Staff</label>
                                <select name="staff_id" id="staff_id" data-control="select2" data-placeholder="Select Staff" class="form-select" data-allow-clear="<?= $this->center_model->isCenter() ?>">
                                    <option></option>
                                    <?php
                                    $list = $this->db->where('type', 'staff')->where('parent_center_id', $center_id)->get('centers')->result();
                                    foreach ($list as $row) {
                                        echo '<option value="' . $row->id . '" data-kt-rich-content-subcontent="' . $row->role .'" data-kt-rich-content-icon="' . $row->image . '">' . $row->name . ' ('.$statusList[$row->isDeleted] .')</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div id="roleDiv" class="form-group col-md-6 mt-4" style="display: none;">
                                <label class="form-label required">Select Role</label>
                                <select name="role" id="role" data-control="select2" data-placeholder="Select Role" class="form-select">
                                    <option></option>
                                    <option value="">Select Role</option>
                                    <option value="Trainer" >Trainer</option>
                                    <option value="Counselor/Receptionist" >Counselor/Receptionist</option>
                                    <option value="Accountant" >Accountant</option>
                                    <option value="Center Head" >Center Head</option>
                                    <option value="Marketing" >Marketing</option>
                                    <option value="Placement Cell" >Placement Cell</option>
                                </select>
                            </div>
                        <?php } ?>

                        <div class="col-md-6 mt-4">
                            <div class="form-group">
                                <label for="notify_type" class="form-label required">Notification Type</label>
                                <select type="text" name="notify_type" class="form-control" data-control="select2"
                                    placeholder="Enter Title">
                                    <option value="primary">Normal</option>
                                    <option value="success">Success</option>
                                    <option value="warning">Warning</option>
                                    <option value="danger">Alert</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mt-4">
                            <div class="form-group">
                                <label for="title" class="form-label required">Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Enter Title">
                            </div>
                        </div>

                        <div class="col-md-12 mt-4">
                            <div class="form-group">
                                <label for="" class="foem-label">Message</label>
                                <textarea name="message" id="" class="aryaeditor"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php
}
?>
<div class="col-md-12 mt-4">
    <div class="{card_class} card-image">
        <div class="card-header">
            <h3 class="card-title"> <?= $this->ki_theme->keen_icon('notification') ?> List Notification(s)</h3>

        </div>
        <div class="card-body">
            <?php
            if(!empty($ntype)){
                $this->db->where('receiver_user', $ntype);
            } 

            $get = $this->db->where('sender_id', $this->session->userdata('admin_id'))->order_by('id', 'DESC')->get('manual_notifications');
            if ($get->num_rows()) {
                ?>
                <style>
                    .unseen.primary {
                        background-color: #3e97ff82;
                    }

                    .unseen.success {
                        background-color: #2e7f5375;
                    }

                    .unseen.warning {
                        background-color: #836a1191;
                    }

                    .unseen.danger {
                        background-color: #8d243da3;
                    }
                </style>
                <div class="table-responsive">
                    <table class="table-bordered table" id="notification-table">
                        <thead>
                            <tr>
                                <th width="30%">Time</th>
                                <th width="50%">Title</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $isAdmin = $this->student_model->isAdmin();
                            foreach ($get->result() as $row) {
                                echo '<tr data-id="' . $row->id . '" class="' . ($row->seen ? 'seen' : 'unseen') .'">
                                        <td>' . date('d-m-Y h:i A', $row->send_time) . '<br>
                                                <div class="d-flex align-items-center me-3">
                                                    ' .
                                                    ($isAdmin ?
                                                        ($row->seen ? label($row->seen . ' Times Seen', 'primary') : label('Unseen', 'danger'))
                                                        : ''
                                                    ) . '

                                                </div>
                                            </div>
                                        </td>
                                        <td>' . $row->title . '<br>' . label(ucfirst($row->notify_type), $row->notify_type) . '</td>
                                        <td>
                                        <div class="btn-group">
                                    <button class="btn btn-sm btn-xs btn-primary view-notification" data-user="center" data-type="' . $row->notify_type . '"><i class="fa fa-eye"></i></button>';
                                /*if ($isAdmin) {
                                    echo '<button class="btn btn-xs btn-sm btn-danger"><i class="fa fa-trash"></i></button>';
                                }*/
                                echo '</div></td>
                                     </tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php
            } else {
                echo alert('Notification Not Found..', 'danger');
            }
            ?>
        </div>
    </div>
</div>