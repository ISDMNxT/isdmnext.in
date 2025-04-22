<div class="row">
    <?php if ($this->student_model->isStudent() && table_exists('manual_notifications')) { ?>
        <?php 
            $receiver_user  = 'student';
            $receiver_id    = $this->session->userdata('student_id');
            $cnotifications = $this->ki_theme->get_manual_notification(['receiver_id'=>$receiver_id,'receiver_user'=>$receiver_user]);
            foreach($cnotifications as $key => $notiArray){
        ?>    
                <!-- Notification Item -->
                <div id="dash_noti_<?= $notiArray['id'] ?>" class="alert alert-<?= $notiArray['notify_type'] ?> d-flex align-items-center justify-content-between" role="alert">
                    <div>
                        <strong><?= ucfirst($notiArray['notify_type']) ?>!</strong> <?= $notiArray['title'] ?></span>
                    </div>
                    <div class="d-flex align-items-center" data-id="<?= $notiArray['id'] ?>" data-user="<?= $receiver_user ?>" data-type="<?= $notiArray['notify_type'] ?>">
                        <!-- View Button -->
                        <button type="button" class="btn btn-link p-0 me-3 view-notification-dash" aria-label="View">
                            <i class="bi bi-eye"></i>
                        </button>
                        <!-- Close Button -->
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
    <?php } } ?>
</div>
<style>
    .container {
        padding: 20px;
    }

    h1 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
    }

    /* Dashboard Grid */
    .dashboard {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    /* Dashboard Card */
    .card {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s, box-shadow 0.2s;
        text-align: center;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .card i {
        font-size: 40px;
        color: #007bff;
        margin-bottom: 10px;
    }

    .card h2 {
        margin: 10px 0;
        font-size: 24px;
        color: #333;
    }

    .card p {
        font-size: 16px;
        color: #666;
    }

    .link-section a:hover {
        color: #0056b3;
    }

            /* Progress Bar Container */
    .progress-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 800px;
        margin: 50px auto;
        position: relative;
    }

    .progress-step {
        text-align: center;
        position: relative;
        z-index: 1;
    }

    .circle {
        width: 50px;
        height: 50px;
        background-color: #e0e0e0;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #555;
        font-size: 18px;
        font-weight: bold;
        margin: 0 auto 10px;
        transition: background 0.3s, color 0.3s;
    }

    /* Line between steps */
    .progress-line {
        height: 5px;
        flex: 1;
        background-color: #e0e0e0;
        margin-top: 22px;
        transition: background 0.3s ease-in-out;
    }

    /* Completed State */
    .progress-step.completed .circle {
        background-color: #007bff;
        color: #fff;
    }

    .progress-line.completed {
        background-color: #007bff;
    }

    /* Progress Title */
    .progress-title {
        font-size: 14px;
        color: #666;
        margin-top: 5px;
    }
</style>
<div class="container">
    <?php

        $studentArray = $this->db->select('s.image,s.roll_no,s.name,s.id,c.course_name,c.id as course_id,c.duration,c.duration_type,ce.institute_name as center_name,ce.id as center_id,s.batch_id')->from('students as s')->join("course as c", "s.course_id = c.id ", 'left')->join('centers as ce', 'ce.id = s.center_id', 'left')->where('s.id', $this->session->userdata('student_id'))->get()->result_array();

        $feeRecords = $this->db->select('SUM(total_amount) as total_amount,SUM(paid_amount) as paid_amount,SUM(pending_amount) as pending_amount')
        ->from('student_fees as s')
        ->where('s.student_id', $this->session->userdata('student_id'))->get()->result_array();

        $examRecords = $this->db->select('e.*')
        ->from('exams_master_trans as es')
        ->join('exams_master as e', "e.id = es.exam_master_id and e.status = 1 and e.is_admin_approved = 2 and e.isDeleted = 0")
        ->where('es.student_id',$this->session->userdata('student_id'))->where('es.status',1)->group_by('e.id')->get()->result_array();

        $markRecords = $this->db->select('es.*')
        ->from('marksheets as es')
        ->where('es.student_id',$this->session->userdata('student_id'))->where('es.center_id',$studentArray[0]['center_id'])->where('es.course_id',$studentArray[0]['course_id'])->get()->result_array();

        $certificateRecords = $this->student_model->student_certificates(['student_id' => $this->session->userdata('student_id')]);

        $marksheetPercentage = 'NA';
        $certificate = 'NA';
        $certiticate_id = 0;
        if(!empty($markRecords[0]['id'])){
            $get = $this->student_model->marksheet(['id' => $markRecords[0]['id']]);

            if ($get->num_rows()) {
                $row = $get->row();
                $course_id = $row->course_id;
                $center_id = $row->institute_id;
                $result_id = $row->result_id;
                
                $get_subect_numers = $this->student_model->marksheet_marks($result_id);
                $per = $ob_ttl = 0;
                $ttltmaxm = $ttlpmaxm = 0;
                if ($ttl_subject = $get_subect_numers->num_rows()) {
                    foreach ($get_subect_numers->result() as $mark) {
                        $tmm = $mark->theory_max_marks ? $mark->theory_max_marks : '00';
                        $pmm = $mark->practical_max_marks ? $mark->practical_max_marks : '00';
                        $ttltmaxm += $tmm;
                        $ttlpmaxm += $pmm;
                        $ob_ttl += $mark->ttl;
                    }
                    $per = number_format((($ob_ttl / ($ttltmaxm + $ttlpmaxm)) * 100), 2);
                }

                $marksheetPercentage = $per.'% Overall';
            }
        }

        if ($certificateRecords->num_rows()) {
            foreach ($certificateRecords->result() as $row) {
                $certiticate_id = $row->certiticate_id;
                $certificate    = 'Available';
            }
        }


        
    ?>
    <!-- Dashboard Cards -->
    <div class="dashboard">
        <div class="card">
            <i class="fas fa-book"></i>
            <h2>Courses</h2>
            <p>1 Ongoing Course</p>
        </div>
        <div class="card">
            <i class="fas fa-credit-card"></i>
            <h2>Fee</h2>
            <p><span data-kt-countup="true" data-kt-countup-value="<?= $feeRecords[0]['paid_amount'] ?>" data-kt-countup-prefix='{inr}'>0</span> Paid</p>
        </div>
        <div class="card">
            <i class="fas fa-exclamation-circle"></i>
            <h2>Outstanding Fee</h2>
            <p><span data-kt-countup="true" data-kt-countup-value="<?= $feeRecords[0]['pending_amount'] ?>" data-kt-countup-prefix='{inr}'>0</span> Pending</p>
        </div>
        <div class="card">
            <i class="fas fa-clipboard-list"></i>
            <h2>Ongoing Exams</h2>
            <p><?= count($examRecords) ?> Exams</p>
        </div>
        <?php if(!empty($markRecords[0]['id'])){ ?>
        <a href="{base_url}marksheet/<?= $this->ki_theme->encrypt( $markRecords[0]['id'] ) ?>" target="_blank">
        <?php } ?>
        <div class="card">
            <i class="fas fa-chart-line"></i>
            <h2>Result</h2>
            <p><?= $marksheetPercentage ?></p>
        </div>
        <?php if(!empty($markRecords[0]['id'])){ ?>
        </a>
        <?php } ?>
        <?php if(!empty($certiticate_id)){ ?>
        <a href="{base_url}certificate/<?= $this->ki_theme->encrypt( $certiticate_id ) ?>" target="_blank">
        <?php } ?>
        <div class="card">
            <i class="fas fa-certificate"></i>
            <h2>Certificate</h2>
            <p><?= $certificate ?></p>
        </div>
        <?php if(!empty($certiticate_id)){ ?>
        </a>
        <?php } ?>

        <a href="https://ilearning.co.in/register" target="_blank">
            <div class="card">
                <i class="fas fa-user-plus"></i>
                <h2>Register</h2>
                <p>For Online Courses</p>
            </div>
        </a>

        <a href="https://innovativeschool.in/book-a-free-demo/" target="_blank">
            <div class="card">
                <i class="fas fa-user-plus"></i>
                <h2>Register</h2>
                <p>For School Studies</p>
            </div>
        </a>
    </div>

    <!-- Progress Bar Section -->
    <div class="progress-section">
        <div class="progress-container">
            <!-- Step 1 -->
            <div class="progress-step completed">
                <div class="circle"><i class="fas fa-user-plus"></i></div>
                <div class="progress-title">Admission</div>
            </div>

            <div class="progress-line completed"></div>
            <div class="progress-step completed">
                <div class="circle"><i class="fas fa-user"></i></div>
                <div class="progress-title">Profile Creation</div>
            </div>

            <div class="progress-line <?php if(!empty($markRecords[0]['id'])){ ?>completed<?php } ?>"></div>
            <div class="progress-step <?php if(!empty($markRecords[0]['id'])){ ?>completed<?php } ?>">
                <div class="circle"><i class="fas fa-file-alt"></i></div>
                <div class="progress-title">Exam</div>
            </div>

            <div class="progress-line <?php if(!empty($certiticate_id)){ ?>completed<?php } ?>"></div>
            <div class="progress-step <?php if(!empty($certiticate_id)){ ?>completed<?php } ?>">
                <div class="circle"><i class="fas fa-award"></i></div>
                <div class="progress-title">Certification</div>
            </div>
        </div>
    </div>
</div>