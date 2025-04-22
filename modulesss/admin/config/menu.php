<?php
$ci = &get_instance();
$loginTypes = $ci->session->userdata();

if($loginTypes['admin_type'] == 'admin'){
    $config['dashboard'] = array(
        'menu' => array(
            array(
                'label' => 'Dashboard', 
                'type' => 'dashboard', 
                'url' => 'admin'
            ),
            array(
                'label' => 'Center Area',
                'type' => 'center_information',
                'icon' => array('profile-user', 3),
                'submenu' => array(
                    array(
                        'label' => 'Add Center',
                        'type' => 'add_center',
                        'icon' => array('add-item', 4),
                        'url' => 'center/add',
                    ),
                    array(
                        'label' => 'List Center',
                        'type' => 'list_center',
                        'icon' => array('tablet-text-down', 4),
                        'url' => 'center/list',
                    ),
                    array(
                        'label' => 'Pending Centers',
                        'type' => 'list_center',
                        'icon' => array('tablet-text-down text-warning', 4),
                        'url' => 'center/pending-list',
                    ),
                    array(
                        'label' => 'Deleted Centers',
                        'type' => 'deleted_center_list',
                        'icon' => array('tablet-text-down text-danger', 4),
                        'url' => 'center/deleted-list'
                    ),
                    array(
                        'label' => 'Center Certificate',
                        'type' => 'center_certificate',
                        'icon' => array('notepad', 5),
                        'url' => 'center/generate-certificate',
                    )
                )
            ),
            array(
                'label' => 'Course Area',
                'type' => 'course_area',
                'icon' => array('book', 2),
                'submenu' => array(
                    array(
                        'label' => 'Category',
                        'type' => 'course_category',
                        'icon' => array('note-2', 4),
                        'url' => 'course/category',
                    ),
                    array(
                        'label' => 'Manage Course',
                        'type' => 'manage_course',
                        'icon' => array('book', 4),
                        'url' => 'course/manage'
                    ),
                    array(
                        'label' => 'Manage Subject',
                        'type' => 'manage_subject',
                        'icon' => array('book', 4),
                        'url' => 'course/manage-subject',
                    ),
                    array(
                        'label' => 'Manage Course Subjects',
                        'type' => 'manage_course_subjects',
                        'icon' => array('book-open', 4),
                        'url' => 'course/manage-course-subjects'
                    ),
                    array(
                        'label' => 'Arrange Subjects',
                        'condition' => OnlyForAdmin(),
                        'type' => 'arrange_subject',
                        'icon' => array('book-open', 4),
                        'url' => 'course/arrange-subjects'
                    ),
                    array(
                        'label' => 'Assign Courses',
                        'type' => 'assign_courses_with_center',
                        'icon' => array('arrow-circle-right', 2),
                        'url' => 'center/assign-courses',
                    )
                )
            ),
            array(
                'label' => 'Academics',
                'type' => 'academics',
                'icon' => array('teacher', 2),
                'submenu' => array(
                    array(
                        'label' => 'Batch',
                        'type' => 'batch_area',
                        'icon' => array('book', 4),
                        'url' => 'academic/batch'
                    ),
                    array(
                        'label' => 'Session',
                        'type' => 'session_area',
                        'icon' => array('book', 4),
                        'url' => 'academic/session',
                    ),
                    /*array(
                        'label' => 'Occupation',
                        'type' => 'occupation_area',
                        'icon' => array('office-bag', 4),
                        'url' => 'academic/occupation',
                    ),*/
                    array(
                        'label' => 'Classes Plan',
                        'type' => 'classes_plan',
                        'icon' => array('book', 4),
                        'url' => 'academic/classes-plan'
                    )
                )
            ),
            array(
                'label' => 'Enquiry',
                'type' => 'enquiry',
                'icon' => array('devices', 5),
                'submenu' => array(
                    array(
                        'label' => 'Student Enquiry',
                        'type' => 'batch_area',
                        'icon' => array('book', 4),
                        'url' => 'student/enquiry'
                    ),
                    array(
                        'label' => 'List Enquiry(s)',
                        'type' => 'list_enquiry',
                        'icon' => array('book', 4),
                        'url' => 'student/list_enquiry',
                    )
                )
            ),
            array(
                'label' => 'Master Franchise',
                'icon' => array('people', 5),
                'type' => 'master_franchise',
                'submenu' => array(
                    array(
                        'label' => 'Add Master Franchise',
                        'type' => 'add_master_franchise',
                        'icon' => array('plus', 2),
                        'url' => 'center/add-master-franchise',
                    ),
                    array(
                        'label' => 'List Master Franchise',
                        'type' => 'list_master_franchise',
                        'icon' => array('notepad', 2),
                        'url' => 'center/list-master-franchise',
                    ),
                    array(
                        'label' => 'Payout Request',
                        'type' => 'payout_request',
                        'icon' => array('book', 4),
                        'url' => 'center/payout_request',
                    )
                )
            ),
            array(
                'label' => 'Students',
                'type' => 'student_information',
                'icon' => array('profile-user', 3),
                'submenu' => array(
                    array(
                        'label' => 'Student Admission',
                        'type' => 'student_admission',
                        'icon' => array('plus', 3),
                        'url' => 'student/admission',
                    ),
                    array(
                        'label' => 'Student Details',
                        'type' => 'student_details',
                        'icon' => array('shield-search', 3),
                        'url' => 'student/search',
                    ),
                    array(
                        'label' => 'Passout Student',
                        'type' => 'passout_students',
                        'icon' => array('bookmark-2', 3),
                        'url' => 'student/passout-student-list',
                    ),
                    array(
                        'label' => 'List Student(s)',
                        'type' => 'all_students',
                        'icon' => array('people', 5),
                        'url' => 'student/all',
                        'submenu' => array(
                            array(
                                'label' => 'Approved List',
                                'type' => 'approved_students',
                                'icon' => array('user-tick text-success', 0),
                                'url' => 'student/approve-list',
                            ),
                            array(
                                'label' => 'Pending List',
                                'type' => 'pending_students',
                                'icon' => array('arrow-circle-left text-warning', 5),
                                'url' => 'student/pending-list',
                            ),
                            array(
                                'label' => 'Cancel List',
                                'type' => 'cancel_students',
                                'icon' => array('cross-circle text-danger', 2),
                                'url' => 'student/cancel-list',
                            )
                        )
                    ),
                    array(
                        'label' => 'List By Center',
                        'type' => 'student_list_by_center',
                        'icon' => array('people', 5),
                        'url' => 'student/list-by-center',
                    )
                )
            ),
            array(
                'label' => 'Attendance',
                'type' => 'attendance',
                'icon' => array('double-check', 2),
                'submenu' => array(
                    array(
                        'label' => 'Student Attendance',
                        'type' => 'student_attendance',
                        'icon' => array('double-check-circle', 4),
                        'url' => 'student/attendance',
                    ),
                    array(
                        'label' => 'Attendance Report',
                        'type' => 'attendance_by_date',
                        'icon' => array('calendar-tick', 6),
                        'url' => 'student/attendance-report',
                    )
                )
            ),
            array(
                'label' => 'Fee',
                'type' => 'fees_collection',
                'icon' => array('bill', 6),
                'submenu' => array(
                    array(
                        'label' => 'Collect Fee',
                        'type' => 'collect_student_fee',
                        'icon' => array('double-check-circle', 4),
                        'url' => 'student/collect-fees',
                    )
                )
            ),
            array(
                'label' => 'Exam(S)',
                'type' => 'exams',
                'icon' => array('note-2', 4),
                'submenu' => array(
                    array(
                        'url' => 'exam/questions-and-paper',
                        'label' => 'Questions And Paper',
                        'type' => 'questions_and_paper',
                        'icon' => array('note-2', 4),
                        'condition' => OnlyForAdmin()
                    ),
                    array(
                        'url' => 'exam/request',
                        'label' => 'Exam Request',
                        'type' => 'create_online_exam',
                        'icon' => array('plus', 2),
                        'condition' => (OnlyForAdmin() or OnlyForCenter()),
                    ),
                     array(
                        'label' => 'Student Exam(S)',
                        'type' => 'student_exams',
                        'icon' => array('note-2', 4),
                        'url' => 'exam/student-exams',
                        'condition' => (OnlyForAdmin() or OnlyForCenter()),
                    ),
                    array(
                        'label' => 'Generate Admit Card',
                        'condition' => OnlyForAdmin(),
                        'type' => 'generate_student_admit_card',
                        'icon' => array('add-notepad', 4),
                        'url' => 'student/generate-admit-card',
                    ),
                    array(
                        'label' => 'List Admit Card(s)',
                        'type' => 'list_student_admit_cards',
                        'icon' => array('tablet-text-up', 3),
                        'url' => 'student/list-admit-card',
                    )
                )
            ),
            array(
                'label' => 'Marksheet & Certificate',
                'type' => 'student_marksheet',
                'icon' => array('notepad', 5),
                'submenu' => array(
                    array(
                        'label' => 'Create Marksheet',
                        'type' => 'generate_student_marksheet',
                        'condition' => OnlyForAdmin(),
                        'icon' => array('add-notepad', 4),
                        'url' => 'student/create-marksheet',
                    ),
                    array(
                        'label' => 'Create Certificate',
                        'type' => 'generate_student_certiificate',
                        'condition' => OnlyForAdmin(),
                        'icon' => array('add-notepad', 4),
                        'url' => 'student/create-certificate',
                    ),
                    array(
                        'label' => 'List Marksheet',
                        'type' => 'list_student_marksheet',
                        'icon' => array('tablet-text-up', 3),
                        'url' => 'student/list-marksheet',
                    ),
                    array(
                        'label' => 'List Certificate',
                        'type' => 'get_student_certificate',
                        'icon' => array('tablet-text-up', 3),
                        'url' => 'student/list-certificate',
                    )
                )
            ),
            array(
                'label' => 'Trainers',
                'icon' => array('people', 5),
                'type' => 'staff',
                'submenu' => array(
                    array(
                        'label' => "View Trainer's List",
                        'type' => 'list_staff',
                        'icon' => array('notepad', 2),
                        'url' => 'center/list-staff',
                    ),
                    array(
                        'label' => "Manage Trainers",
                        'type' => 'add_staff',
                        'icon' => array('plus', 2),
                        'url' => 'center/add-staff',
                    ),
                    array(
                        'label' => "Trainers Performance",
                        'type' => 'trainers_report',
                        'icon' => array('notepad', 5),
                        'url' => 'center/trainers-report',
                    )
                )
            ),
            array(
                'label' => 'Notification',
                'icon' => array('notification', 5),
                'type' => 'notification',
                'submenu' => array(
                    array(
                        'label' => 'To Centers',
                        'type' => 'notification',
                        'icon' => array('profile-user', 3),
                        'url' => 'center/notification?ntype=center',
                    ),
                    array(
                        'label' => 'To Students',
                        'type' => 'notification',
                        'icon' => array('profile-user', 3),
                        'url' => 'center/notification?ntype=student',
                    ),
                    array(
                        'label' => 'To Staff',
                        'type' => 'notification',
                        'icon' => array('people', 5),
                        'url' => 'center/notification?ntype=staff',
                    )
                )
            ),
            array(
                'label' => 'Request(s)',
                'icon' => array('notification', 5),
                'type' => 'notification',
                'submenu' => array(
                    array(
                        'label' => 'Course Request',
                        'type' => 'course_request',
                        'icon' => array('book', 4),
                        'url' => 'course/course_request'
                    ),
                    array(
                        'url' => 'exam/request',
                        'label' => 'Exam Request',
                        'type' => 'create_online_exam',
                        'icon' => array('plus', 2)
                    ),
                     array(
                        'label' => 'Student Details Request',
                        'type' => 'student_request',
                        'icon' => array('note-2', 4),
                        'url' => 'student/student_request',
                    ),
                     array(
                        'label' => 'Student Exam(S)',
                        'type' => 'student_exams',
                        'icon' => array('note-2', 4),
                        'url' => 'exam/student-exams',
                    ),
                    array(
                        'label' => 'Marksheet & Certificate Request',
                        'type' => 'generate-marksheet-certificate-request',
                        'icon' => array('add-notepad', 4),
                        'url' => 'student/generate-marksheet-certificate-request',
                    )
                )
            ),
            array(
                'label' => 'Downloads',
                'icon' => array('notepad', 5),
                'type' => 'downloads',
                'url' => 'student/downloads',
            ),
            array(
                'label' => 'Financial',
                'icon' => array('financial-schedule', 5),
                'type' => 'financial',
                'url' => 'center/financial',
            ),
            array(
                'label' => 'Reports',
                'icon' => array('calendar-tick', 5),
                'type' => 'reports',
                'submenu' => array(
                    array(
                        'label' => 'Attendance Report',
                        'type' => 'attendance_by_date',
                        'icon' => array('calendar-tick', 6),
                        'url' => 'student/attendance-report',
                    ),
                    array(
                        'label' => 'Wallet Report',
                        'type' => 'wallet_report',
                        'icon' => array('wallet', 5),
                        'url' => 'center/wallet-report',
                    ),
                    array(
                        'label' => "Trainer's Report",
                        'type' => 'trainers_report',
                        'icon' => array('notepad', 5),
                        'url' => 'center/trainers-report',
                    ),
                    array(
                        'label' => "Trainer View Report",
                        'type' => 'trainer_view_report',
                        'icon' => array('notepad', 5),
                        'url' => 'center/trainer_view_report',
                    )
                )
            )
        )
    );

    $config['payment_setting'] = array(
        'menu' => array(
            array(
                'label' => 'Payment Setting',
                'type' => 'center_information',
                'icon' => array('financial-schedule', 4),
                'condition' => OnlyForAdmin(),
                'submenu' => array(
                    array(
                        'label' => 'Student',
                        'type' => 'student_payment_setting',
                        'icon' => array('bank', 2),
                        'url' => 'payment/student-payment-setting',
                    ),
                    array(
                        'label' => 'Center',
                        'type' => 'center_payment_setting',
                        'icon' => array('bank', 2),
                        'url' => 'payment/center-payment-setting',
                    )
                )
            )
        )
    );

    /*$config['job_board'] = array(
        'menu' => array(
            array(
                'label' => 'Job Borad',
                'type' => 'job_board',
                'icon' => array('briefcase', 4),
                'condition' => OnlyForAdmin(),
                'submenu' => array(
                    array(
                        'label' => 'Manage Job Category',
                        'type' => 'job_category',
                        'icon' => array('file', 2),
                        'url' => 'employer/job-category',
                    ),
                    array(
                        'label' => 'Manage Job Skill',
                        'type' => 'job_skill',
                        'icon' => array('file', 2),
                        'url' => 'employer/job-skill',
                    ),
                    array(
                        'label' => 'Manage Mapping',
                        'type' => 'manage_mapping',
                        'icon' => array('file', 2),
                        'url' => 'employer/manage-mapping',
                    )
                )
            )
        )
    );*/

    $config['cms_setting'] = array(
        'menu' => array(
            /*array(
                'label' => 'Manage Role User',
                'icon' => array('people', 5),
                'type' => 'manage_role_user',
                'condition' => OnlyForAdmin(),
                'submenu' => array(
                    array(
                        'label' => 'Role Category',
                        'type' => 'manage_role_category',
                        'icon' => array('chart', 2),
                        'url' => 'admin/manage-role-category',
                    ),
                    array(
                        'label' => 'Manage User',
                        'type' => 'manage_user',
                        'icon' => array('people', 5),
                        'url' => 'admin/manage-role-account'
                    )
                )
            ),*/
            array(
                'label' => 'Setting',
                'condition' => OnlyForAdmin(),
                'type' => 'cms_setting',
                'icon' => array('setting-2', 4),
                'url' => 'cms/setting'
            ),
            array(
                'label' => 'Gallery Image',
                'condition' => OnlyForAdmin(),
                'type' => 'gallery_setting',
                'icon' => array('picture', 4),
                'url' => 'cms/gallery-images'
            ),
            array(
                'label' => 'Slider',
                'condition' => OnlyForAdmin(),
                'type' => 'slider_setting',
                'icon' => array('picture', 4),
                'url' => 'cms/slider'
            ),
            array(
                'label' => 'Page Area',
                'condition' => OnlyForAdmin(),
                'type' => 'page_area',
                'icon' => array('file', 3),
                'submenu' => array(
                    array(
                        'label' => 'Add Pages',
                        'type' => 'add_pages',
                        'icon' => array('add-item', 4),
                        'url' => 'cms/add-page',
                    ),
                    array(
                        'label' => 'List Pages',
                        'type' => 'list_pages',
                        'icon' => array('tablet-text-down', 4),
                        'url' => 'cms/list-pages',
                    ),
                    array(
                        'label' => 'Menu Section',
                        'type' => 'cms_menu_section',
                        'icon' => array('menu', 4),
                        'url' => 'cms/menu-section'
                    ),

                )
            ),
            array(
                'label' => 'Contact Us Enquiry',
                'condition' => OnlyForAdmin(),
                'type' => 'enquiry_data',
                'icon' => array('tablet-text-down', 4),
                'url' => 'cms/enquiry-data'
            ),
        )
    );

    $staticMenus = array(
        array(
            'label' => 'Forms',
            'type' => 'static_forms',
            'icon' => array('file', 4),
            'url' => 'cms/forms',
            'condition' => OnlyForAdmin(),
        )
    );

    if (file_exists(THEME_PATH . 'config/menu.php')) { {
            require THEME_PATH . 'config/menu.php';
            $staticMenus[] = $menu;
            unset($menu);
        }
    }

    $config['fix_properties'] = array(
        'condition' => OnlyForAdmin(),
        'menu' => $staticMenus
    );

    $config['settingss'] = array(
        'menu' => array(
            array(
                'label' => 'Logout',
                'type' => 'logout',
                'icon' => array('logout', 5),
                'url' => 'admin/sign-out',
            )
        )
    );
}

$all_permission = [];
$all_permission['dashboard'] = array(
                'label' => 'Dashboard', 
                'type' => 'dashboard', 
                'url' => 'admin'
            );
$all_permission['course_area'] = array(
            'label' => 'Course Area',
            'type' => 'course_area',
            'icon' => array('book', 2),
            'submenu' => array(
                array(
                    'label' => 'Manage Course',
                    'type' => 'manage_course',
                    'icon' => array('book', 4),
                    'url' => 'course/manage'
                ),
                array(
                    'label' => 'List Pending Course',
                    'type' => 'course_request',
                    'icon' => array('book', 4),
                    'url' => 'course/course_request'
                )/*,
                array(
                    'label' => 'Manage Course Subjects',
                    'type' => 'manage_course_subjects',
                    'icon' => array('book-open', 4),
                    'url' => 'course/manage-course-subjects'
                )*/
            )
        );
$all_permission['academics'] = array(
            'label' => 'Academics',
            'type' => 'academics',
            'icon' => array('teacher', 2),
            'submenu' => array(
                array(
                    'label' => 'Batch',
                    'type' => 'batch_area',
                    'icon' => array('book', 4),
                    'url' => 'academic/batch'
                ),
                array(
                    'label' => 'Classes Plan',
                    'type' => 'classes_plan',
                    'icon' => array('book', 4),
                    'url' => 'academic/classes-plan'
                )
            )
        );
$all_permission['enquiry'] = array(
            'label' => 'Enquiry',
            'type' => 'enquiry',
            'icon' => array('devices', 5),
            'submenu' => array(
                array(
                    'label' => 'Student Enquiry',
                    'type' => 'batch_area',
                    'icon' => array('book', 4),
                    'url' => 'student/enquiry'
                ),
                array(
                    'label' => 'List Enquiry(s)',
                    'type' => 'list_enquiry',
                    'icon' => array('book', 4),
                    'url' => 'student/list_enquiry',
                )
            )
        );
$all_permission['student_information'] = array(
            'label' => 'Students',
            'type' => 'student_information',
            'icon' => array('profile-user', 3),
            'submenu' => array(
                array(
                    'label' => 'Student Admission',
                    'type' => 'student_admission',
                    'icon' => array('plus', 3),
                    'url' => 'student/admission',
                ),
                array(
                    'label' => 'Student Details',
                    'type' => 'student_details',
                    'icon' => array('shield-search', 3),
                    'url' => 'student/search',
                ),
                 array(
                    'label' => 'Student Details Request',
                    'type' => 'student_request',
                    'icon' => array('note-2', 4),
                    'url' => 'student/student_request',
                ),
                array(
                    'label' => 'Passout Student',
                    'type' => 'passout_students',
                    'icon' => array('bookmark-2', 3),
                    'url' => 'student/passout-student-list',
                ),
                array(
                    'label' => 'List Student(s)',
                    'type' => 'all_students',
                    'icon' => array('people', 5),
                    'url' => 'student/all',
                    'submenu' => array(
                        array(
                            'label' => 'Approved List',
                            'type' => 'approved_students',
                            'icon' => array('user-tick text-success', 0),
                            'url' => 'student/approve-list',
                        ),
                        array(
                            'label' => 'Pending List',
                            'type' => 'pending_students',
                            'icon' => array('arrow-circle-left text-warning', 5),
                            'url' => 'student/pending-list',
                        ),
                        array(
                            'label' => 'Cancel List',
                            'type' => 'cancel_students',
                            'icon' => array('cross-circle text-danger', 2),
                            'url' => 'student/cancel-list',
                        )
                    )
                ),
            )
        );
$all_permission['attendance'] = array(
            'label' => 'Attendance',
            'type' => 'attendance',
            'icon' => array('double-check', 2),
            'submenu' => array(
                array(
                    'label' => 'Student Attendance',
                    'type' => 'student_attendance',
                    'icon' => array('double-check-circle', 4),
                    'url' => 'student/attendance',
                ),
                array(
                    'label' => 'Attendance Report',
                    'type' => 'attendance_by_date',
                    'icon' => array('calendar-tick', 6),
                    'url' => 'student/attendance-report',
                )
            )
        );
$all_permission['fees_collection'] = array(
            'label' => 'Fee',
            'type' => 'fees_collection',
            'icon' => array('bill', 6),
            'submenu' => array(
                array(
                    'label' => 'Collect Fee',
                    'type' => 'collect_student_fee',
                    'icon' => array('double-check-circle', 4),
                    'url' => 'student/collect-fees',
                )
            )
        );
$all_permission['exams'] = array(
            'label' => 'Exam(S)',
            'type' => 'exams',
            'icon' => array('note-2', 4),
            'submenu' => array(
                array(
                    'url' => 'exam/request',
                    'label' => 'Exam Request',
                    'type' => 'create_online_exam',
                    'icon' => array('plus', 2),
                    'condition' => (OnlyForAdmin() or OnlyForCenter()),
                ),
                 array(
                    'label' => 'Student Exam(S)',
                    'type' => 'student_exams',
                    'icon' => array('note-2', 4),
                    'url' => 'exam/student-exams',
                    'condition' => (OnlyForAdmin() or OnlyForCenter()),
                )
            )
        );
$all_permission['student_marksheet'] = array(
            'label' => 'Marksheet & Certificate',
            'type' => 'student_marksheet',
            'icon' => array('notepad', 5),
            'submenu' => array(
                array(
                    'label' => 'Generate Marksheet & Certificate',
                    'type' => 'generate_marksheet_certificate',
                    'icon' => array('add-notepad', 4),
                    'url' => 'student/generate-marksheet-certificate',
                ),
                array(
                    'label' => 'List Marksheet & Certificate Request',
                    'type' => 'generate-marksheet-certificate-request',
                    'icon' => array('add-notepad', 4),
                    'url' => 'student/generate-marksheet-certificate-request',
                ),
                array(
                    'label' => 'List Admit Card(s)',
                    'type' => 'list_student_admit_cards',
                    'icon' => array('tablet-text-up', 3),
                    'url' => 'student/list-admit-card',
                ),
                array(
                    'label' => 'List Marksheet',
                    'type' => 'list_student_marksheet',
                    'icon' => array('tablet-text-up', 3),
                    'url' => 'student/list-marksheet',
                ),
                array(
                    'label' => 'List Certificate',
                    'type' => 'get_student_certificate',
                    'icon' => array('tablet-text-up', 3),
                    'url' => 'student/list-certificate',
                )
            )
        );
$all_permission['staff'] = array(
            'label' => 'Staff',
            'icon' => array('people', 5),
            'type' => 'staff',
            'submenu' => array(
                array(
                    'label' => 'Add Staff',
                    'type' => 'add_staff',
                    'icon' => array('plus', 2),
                    'url' => 'center/add-staff',
                ),
                array(
                    'label' => 'List Staff ',
                    'type' => 'list_staff',
                    'icon' => array('notepad', 2),
                    'url' => 'center/list-staff',
                )
            )
        );
$all_permission['notification'] = array(
            'label' => 'Notification',
            'icon' => array('notification', 5),
            'type' => 'notification',
            'submenu' => array(
                array(
                    'label' => 'To Students',
                    'type' => 'notification',
                    'icon' => array('profile-user', 3),
                    'url' => 'center/notification?ntype=student',
                ),
                array(
                    'label' => 'To Staff',
                    'type' => 'notification',
                    'icon' => array('people', 5),
                    'url' => 'center/notification?ntype=staff',
                )
            )
        );
$all_permission['downloads'] = array(
                'label' => 'Downloads',
                'icon' => array('notepad', 5),
                'type' => 'downloads',
                'url' => 'student/downloads',
            );
$all_permission['reports'] = array(
            'label' => 'Reports',
            'icon' => array('calendar-tick', 5),
            'type' => 'reports',
            'submenu' => array(
                array(
                    'label' => 'Specific Student Report',
                    'type' => 'student_details',
                    'icon' => array('shield-search', 3),
                    'url' => 'student/search',
                ),
                array(
                    'label' => 'Pending Fees',
                    'type' => 'fee_status',
                    'icon' => array('notepad', 5),
                    'url' => 'student/fee_status',
                ),
                array(
                    'label' => "Trainer's Rating",
                    'type' => 'trainers_report',
                    'icon' => array('notepad', 5),
                    'url' => 'center/trainers-report',
                ),
                array(
                    'label' => 'Fees Collection Report',
                    'type' => 'fee_statuss',
                    'icon' => array('notepad', 5),
                    'url' => 'student/fee_statuss',
                ),
                array(
                    'label' => 'Wallet Transections',
                    'type' => 'history',
                    'icon' => array('wallet', 5),
                    'url' => 'admin/wallet-history',
                ),
                array(
                    'label' => "Trainer View Report",
                    'type' => 'trainer_view_report',
                    'icon' => array('notepad', 5),
                    'url' => 'center/trainer_view_report',
                )
            )
        );
$all_permission['profile'] = array(
            'label' => 'Profile',
            'icon' => array('profile-user', 5),
            'type' => 'profile',
            'submenu' => array(
                array(
                    'label' => 'Profile',
                    'type' => 'profile',
                    'icon' => array('profile-user', 3),
                    'url' => 'admin/profile',
                ),
                array(
                    'label' => 'Logout',
                    'type' => 'logout',
                    'icon' => array('logout', 5),
                    'url' => 'admin/sign-out',
                )
            )
        );
$all_permission['logout'] = array(
                        'label' => 'Logout',
                        'type' => 'logout',
                        'icon' => array('logout', 5),
                        'url' => 'admin/sign-out',
                    );
if($loginTypes['admin_type'] == 'center' && empty($loginTypes['staff_id'])){
    $config['dashboard']['menu'][] = $all_permission['dashboard'];
    $config['dashboard']['menu'][] = $all_permission['course_area'];
    $config['dashboard']['menu'][] = $all_permission['academics'];
    $config['dashboard']['menu'][] = $all_permission['enquiry'];
    $config['dashboard']['menu'][] = $all_permission['student_information'];
    $config['dashboard']['menu'][] = $all_permission['attendance'];
    $config['dashboard']['menu'][] = $all_permission['fees_collection'];
    $config['dashboard']['menu'][] = $all_permission['exams'];
    $config['dashboard']['menu'][] = $all_permission['student_marksheet'];
    $config['dashboard']['menu'][] = $all_permission['staff'];
    $config['dashboard']['menu'][] = $all_permission['notification'];
    $config['dashboard']['menu'][] = $all_permission['downloads'];
    $config['dashboard']['menu'][] = $all_permission['reports'];
    $config['dashboard']['menu'][] = $all_permission['profile'];
}

if($loginTypes['admin_type'] == 'center' && !empty($loginTypes['staff_id'])){
    $config['dashboard']['menu'][] = $all_permission['dashboard'];
    foreach($loginTypes['permission'] as $key => $value){
        $config['dashboard']['menu'][] = $all_permission[$value];
    }
    $config['dashboard']['menu'][] = $all_permission['logout'];
}

if($loginTypes['admin_type'] == 'master_franchise'){
    $config['dashboard']['menu'][] = $all_permission['dashboard'];

    $config['dashboard']['menu'][] = array(
                        'label' => 'List Center',
                        'type' => 'list_center',
                        'icon' => array('tablet-text-down', 4),
                        'url' => 'center/list',
                    );
    $config['dashboard']['menu'][] = array(
                    'label' => 'List Student(s)',
                    'type' => 'approved_students',
                    'icon' => array('people', 5),
                    'url' => 'student/approve-list'
                );

    $config['dashboard']['menu'][] = array(
                'label' => 'Trainers',
                'icon' => array('people', 5),
                'type' => 'list_staff',
                'url' => 'center/list-staff'
            );

    $config['dashboard']['menu'][] = array(
                    'label' => 'Courses',
                    'type' => 'manage_course',
                    'icon' => array('book', 4),
                    'url' => 'course/manage'
                );

    $config['dashboard']['menu'][] = array(
                    'url' => 'exam/request',
                    'label' => 'Exam',
                    'type' => 'create_online_exam',
                    'icon' => array('plus', 2)
                );

    $config['dashboard']['menu'][] = array(
                    'label' => 'Financial',
                    'icon' => array('financial-schedule', 5),
                    'type' => 'financial',
                    'submenu' => array(
                        array(
                            'label' => 'Financial Summary ',
                            'icon' => array('financial-schedule', 5),
                            'type' => 'financial',
                            'url' => 'center/financial',
                        ),
                        array(
                            'label' => 'Payout Request',
                            'type' => 'payout_request',
                            'icon' => array('book', 4),
                            'url' => 'center/payout_request',
                        )
                    )
                );

    /*$config['dashboard']['menu'][] = array(
                    'label' => 'Job Board',
                    'icon' => array('briefcase', 5),
                    'type' => 'Jobboard',
                    'submenu' => array(
                        array(
                            'label' => 'Employers',
                            'icon' => array('people', 5),
                            'type' => 'employers',
                            'submenu' => array(
                                array(
                                    'label' => 'Add Employer',
                                    'icon' => array('people', 5),
                                    'type' => 'employer_mgmt',
                                    'url' => 'employer/employer-mgmt',
                                ),
                                array(
                                    'label' => 'List Employers',
                                    'type' => 'list',
                                    'icon' => array('book', 4),
                                    'url' => 'employer/list',
                                )
                            )
                        ),
                        array(
                            'label' => 'Jobs',
                            'type' => 'jobs',
                            'icon' => array('briefcase', 4),
                            'submenu' => array(
                                array(
                                    'label' => 'Add Jobs',
                                    'icon' => array('briefcase', 5),
                                    'type' => 'job_mgmt',
                                    'url' => 'employer/job_mgmt',
                                ),
                                array(
                                    'label' => 'List Jobs',
                                    'type' => 'list_jobs',
                                    'icon' => array('book', 4),
                                    'url' => 'employer/list-jobs',
                                )
                            )
                        )
                    )
                );*/

    $config['dashboard']['menu'][] = $all_permission['downloads'];
    $config['dashboard']['menu'][] = $all_permission['profile'];
}
?>