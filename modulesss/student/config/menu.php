<?php
$config['dashboard'] = array(
    'menu' => array(
        array(
            'label' => 'Dashboard',
            'type' => 'dashboard',
            'url' => 'student/dashboard'
        ),
        array(
            'label' => 'Attendance',
            'type' => 'attendancereport',
            'url' => 'student/attendancereport',
            'icon' => array('double-check-circle', 3)
        ),
        array(
            'label' => 'Classes Report',
            'type' => 'classes_report',
            'url' => 'student/classes-report',
            'icon' => array('notepad', 3)
        ),
        array(
            'label' => 'Study Material',
            'type' => 'study_material',
            'icon' => array('notepad', 3),
            'submenu' => array(
                array(
                    'label' => "View Course Study Material",
                    'type' => 'view_course_study_material',
                    'icon' => array('plus', 2),
                    'url' => 'student/view_course_study_material',
                )/*,
                array(
                    'label' => "Assignments",
                    'type' => 'assignments',
                    'icon' => array('plus', 2),
                    'url' => 'student/assignments',
                )*/
            )
        ),
        array(
            'label' => 'Downloads',
            'icon' => array('notepad', 5),
            'type' => 'downloadss',
            'url' => 'student/downloadss',
        ),
        array(
            'label' => 'Notification',
            'icon' => array('notification', 5),
            'type' => 'notification',
            'url' => 'student/notification',
        ),
        array(
            'label' => 'Fee',
            'type' => 'fee_record',
            'url' => 'student/profile/fee-record',
            'icon' => array('notepad', 5)
        ),
        array(
            'label' => 'Exam & Results',
            'icon' => array('notepad', 5),
            'type' => 'exam_area',
            'submenu' => array(
                array(
                    'label' => 'Exam Area',
                    'icon' => array('notepad', 5),
                    'type' => 'exam_area',
                    'url' => 'student/my-exam'
                ),
                array(
                    'label' => "Course Certificates",
                    'type' => 'certificate',
                    'url' => 'student/certificate',
                    'icon' => array('notepad', 5)
                ),
                array(
                    'label' => 'Marksheet',
                    'type' => 'marksheet',
                    'url' => 'student/marksheets',
                    'icon' => array('notepad', 5)
                ),
                /*array(
                    'label' => "Extra Certificates",
                    'type' => 'extra_certificate',
                    'url' => 'student/extra-certificate',
                    'icon' => array('notepad', 5)
                ),*/
                array(
                    'label' => 'Admit Card',
                    'type' => 'marksheet',
                    'url' => 'student/admit-card',
                    'icon' => array('user-square', 3)
                )
            )
        ),
        array(
            'label' => 'ID Card',
            'type' => 'id_card',
            'url' => 'student/id-card',
            'icon' => array('user-square', 3),
            'condition' => CHECK_PERMISSION('STUDENT_ID_CARD')
        )
    )
);

$config['profile'] = array(
    'menu' => array(
        array(
            'label' => 'Profile',
            'type' => 'profile',
            'icon' => array('user-square', 3),
            'submenu' => array(
                array(
                    'label' => 'Profile',
                    'type' => 'profile',
                    'icon' => array('user-square', 3),
                    'url' => 'student/profile'
                ),
                /*array(
                    'label' => "My Resume",
                    'type' => 'myresume',
                    'url' => 'student/myresume',
                    'icon' => array('notepad', 5)
                ),*/
                array(
                    'label' => 'Logout',
                    'type' => 'marksheet',
                    'url' => 'student/sign-out',
                    'icon' => array('box-arrow-right', 5)
                )
            )
        )
    )
);