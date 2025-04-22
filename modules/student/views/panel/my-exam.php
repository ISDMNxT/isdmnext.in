<?php
$get = $this->student_model->get_switch(
    'student_exams',
    [
        'id' => $this->student_model->studentId()
    ]
);
// echo $this->db->last_query();
if ($get->num_rows()) {
    echo '<div class="row">';
    foreach ($get->result() as $row) {
        $paperStatus = $row->paper_status;
        //pre($row);
        ?>
        <div class="col-md-6">
            <a href="javascript:void(0)" class="card border-hover-primary <?= ($paperStatus == 1 && $row->paper_type == 'theortical') ? 'ready' : '' ?>"
                data-id="<?= $row->assign_exam_id ?>">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-9 ">
                    <!--begin::Card Title-->
                    <div class="card-title m-0">
                        <!--begin::Avatar-->
                        <!-- <div class="symbol symbol-50px w-50px bg-light me-7">
                            <img src="{base_url}upload/{image}" alt="image" class="p-3">
                        </div> -->
                        <!-- <div class="fs-1 fw-bolder text-muted">
                            {student_name} (<?= $row->roll_no ?>)
                        </div> -->
                        <div class="fs-2 fw-bolder text-dark">
                            <?= $row->subject_name ?> (<?= ucfirst($row->paper_type) ?>)
                        </div>
                        <br/>
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <?php if ($paperStatus == 1) { ?>
                                <span class="badge badge-light-danger fw-bold me-auto px-4 py-3">Pending</span>
                            <?php } else if ($paperStatus == 2) { ?>
                                <span class="badge badge-light-success fw-bold me-auto px-4 py-3">Done</span>
                            <?php } ?>
                        </div>
                        <!--end::Card toolbar-->
                        
                        <!--end::Avatar-->
                    </div>
                    <!--end::Car Title-->
                    
                </div>
                <!--end:: Card header-->
                <!--begin:: Card body-->
                <div class="card-body p-9 ribbon ribbon-end ribbon-clip">
                    <div class="ribbon-label" style="top:-30px">
                        Start Date: <?=date('d-m-Y',strtotime($row->start_date)) ?>
                        <span class="ribbon-inner bg-info"></span>
                    </div>

                    <div class="ribbon-label" style="top:15px">
                        End Date: <?=date('d-m-Y',strtotime($row->end_date)) ?>
                        <span class="ribbon-inner bg-info"></span>
                    </div>

                    <div class="ribbon-label text-dark" style="top:60px">
                        Total Marks: <?=$row->paper_total_marks?>
                        <span class="ribbon-inner bg-secondary"></span>
                    </div>

                   
                    
                    <div class="d-flex flex-wrap mb-5">
                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-7 mb-3">
                            <div class="fs-6 text-gray-800 fw-bold">
                                <?=$row->exam_title?>
                            </div>
                            <div class="fw-semibold text-gray-500">Exam</div>
                        </div>

                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-7 mb-3">
                            <div class="fs-6 text-gray-800 fw-bold">
                                <?=$row->course_name?><br/><?=$row->duration?>&nbsp;<?=$row->duration_type?>
                            </div>
                            <div class="fw-semibold text-gray-500">Course</div>
                        </div>

                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-7 mb-3">
                            <div class="fs-6 text-gray-800 fw-bold">
                                <?=$row->paper_duration?> Minuts
                            </div>
                            <div class="fw-semibold text-gray-500">Duration</div>
                        </div>

                        <?php if ($paperStatus == 2) { ?>
                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-7 mb-3">
                                <div class="fs-6 text-gray-800 fw-bold">
                                    <?=date('d-m-Y h:i A',strtotime($row->attempt_time)) ?>
                                </div>
                                <div class="fw-semibold text-gray-500">Attempt Date</div>
                            </div>
                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 mb-3 me-7">
                                <div class="fs-6 text-gray-800 fw-bold">
                                <?php if($row->percentage == 'NaN' || empty($row->percentage)) { $row->percentage = 0; } if(!empty($row->percentage)) { echo round($row->percentage); } else { echo '0'; } ?> %
                                </div>
                                <div class="fw-semibold text-gray-500">Percentage</div>
                            </div>
                        <?php } ?>


                    </div>
                </div>
                <!--end:: Card body-->
            </a>
        </div>
        <?php
    }
    echo '</div>';
} else
    echo $this->ki_theme->item_not_found('Not Found', 'Exam not found.');


?>