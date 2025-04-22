<?php 
$config['dashboard'] = array(
    'menu' => array(
        array('label' => 'Dashboard', 'type' => 'dashboard', 'url' => 'admin')
    )
);

$config['academics'] = array(
    'menu' => array(
        array(
            'label' => 'Course Area',
            'condition' => (OnlyForAdmin() or OnlyForCenter()),
            'type' => 'course_area',
            'icon' => array('book', 2),
            'submenu' => array(
                array(
                    'label' => 'Category',
                    'condition' => OnlyForAdmin(),
                    'type' => 'course_category',
                    'icon' => array('note-2', 4),
                    'url' => 'course/category',
                ),
                array(
                    'label' => 'Manage Course',
                    'type' => 'manage_course',
                    'icon' => array('book', 4),
                    'url' => 'course/manage',
                ),
                array(
                    'label' => 'Manage Subject',
                    'condition' => OnlyForAdmin(),
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
                /*
                array(
                    'label' => 'Manage Course Fee',
                    'type' => 'manage_course_fee',
                    'icon' => array('bill', 4),
                    'url' => 'course/manage-fees',
                ),
                */
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
                    'url' => 'academic/batch',
                ),
                array(
                    'label' => 'Session',
                    'condition' => OnlyForAdmin(),
                    'type' => 'session_area',
                    'icon' => array('book', 4),
                    'url' => 'academic/session',
                ),
                array(
                    'label' => 'Occupation',
                    'condition' => OnlyForAdmin(),
                    'type' => 'occupation_area',
                    'icon' => array('office-bag', 4),
                    'url' => 'academic/occupation',
                )
            )
        )
    )
);

$config['enquiry'] = array(
    'condition' => OnlyForCenter(),
    'menu' => array(
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
                    'type' => 'session_area',
                    'icon' => array('book', 4),
                    'url' => 'student/listenquiry',
                )
            )
        )
    )
);

$config['coupon_menu'] = array(
    'condition' => CHECK_PERMISSION('REFERRAL_ADMISSION') && OnlyForAdmin(),
    'menu' => array(
        array(
            'label' => 'Student Coupon(S)',
            'type' => 'student_coupons',
            'icon' => array('devices', 5),
            'url' => 'student/coupons'
        )
    )
);

$config['menu'] = array(
    'menu' => array(
        array(
            'label' => 'Student Information',
            'type' => 'student_information',
            'icon' => array('profile-user', 3),
            'submenu' => array(
                array(
                    'label' => 'Student ID Card',
                    'condition' => CHECK_PERMISSION('STUDENT_ID_CARD'),
                    'type' => 'student_id_card',
                    'icon' => array('user-square', 3),
                    'url' => 'student/get-id-card'
                ),
                array(
                    'label' => 'Student Details',
                    'type' => 'student_details',
                    'icon' => array('shield-search', 3),
                    'url' => 'student/search',
                ),
                array(
                    'label' => 'Student Admission',
                    'type' => 'student_admission',
                    'icon' => array('plus', 3),
                    'url' => 'student/admission',
                ),
                array(
                    'label' => 'Passout Student',
                    'type' => 'passout_students',
                    'icon' => array('bookmark-2', 3),
                    'url' => 'student/passout-student-list',
                ),
                array(
                    'label' => 'List By Center',
                    'type' => 'student_list_by_center',
                    'icon' => array('people', 5),
                    'url' => 'student/list-by-center',
                    'condition' => OnlyForAdmin()
                ),
                array(
                    'label' => 'List Student(s)',
                    'type' => 'all_students',
                    'icon' => array('people', 5),
                    'url' => 'student/all',
                    'submenu' => array(
                        array(
                            'label' => 'Pending List',
                            'type' => 'pending_students',
                            'icon' => array('arrow-circle-left text-warning', 5),
                            'url' => 'student/pending-list',
                        ),
                        array(
                            'label' => 'Approved List',
                            'type' => 'approved_students',
                            'icon' => array('user-tick text-success', 0),
                            'url' => 'student/approve-list',
                        ),
                        array(
                            'label' => 'Cancel List',
                            'type' => 'cancel_students',
                            'icon' => array('cross-circle text-danger', 2),
                            'url' => 'student/cancel-list',
                        )
                    )
                ),
                // array(
                //     'label' => 'List By Center',
                //     'type' => 'list_by_center',
                //     'condition' => OnlyForAdmin(),
                //     'icon' => array('tablet-text-down', 4),
                //     'url' => 'student/list-by-center',
                // )
            )
        ),
        array(
            'label' => 'Fees Collection',
            'type' => 'fees_collection',
            'icon' => array('bill', 6),
            'condition' => (OnlyForAdmin() or OnlyForCenter()),
            'submenu' => array(
                array(
                    'label' => 'Collect Fee',
                    'type' => 'collect_student_fee',
                    'icon' => array('double-check-circle', 4),
                    'url' => 'student/collect-fees',
                )/*,
                array(
                    'label' => 'Search Fee Payment',
                    'type' => 'search_fee_payment',
                    'icon' => array('calendar-tick', 6),
                    'url' => 'student/search-fees-payment',
                )*/
            )
        ),
        array(
            'label' => 'Attendance',
            'type' => 'attendance',
            'icon' => array('double-check', 2),
            'condition' => (OnlyForAdmin() or OnlyForCenter()),
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
            'label' => 'Admit Card',
            'type' => 'stduent_admit_card',
            'icon' => array('notepad', 5),
            'submenu' => array(
                array(
                    'label' => 'Generate Admit Card',
                    'condition' => OnlyForAdmin(),
                    'type' => 'generate_student_admit_card',
                    'icon' => array('add-notepad', 4),
                    'url' => 'student/generate-admit-card',
                ),
                /*array(
                    'label' => 'Get Admit Card',
                    'type' => 'get_student_admit_card',
                    'icon' => array('tablet-text-up', 3),
                    'url' => 'student/get-admit-card',
                ),*/
                array(
                    'label' => 'List Admit Card(s)',
                    'type' => 'list_student_admit_cards',
                    'icon' => array('tablet-text-up', 3),
                    'url' => 'student/list-admit-card',
                )
            )
        ),
        array(
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
                // array(
                //     'label' => 'Get Result',
                //     'type' => 'get_student_marksheet',
                //     'icon' => array('tablet-text-up', 3),
                //     'url' => 'student/get-marksheet',
                // ),
                array(
                    'label' => 'List Marksheet',
                    'type' => 'list_student_marksheet',
                    'icon' => array('tablet-text-up', 3),
                    'url' => 'student/list-marksheet',
                ),
                array(
                    'label' => 'Create Certificate',
                    'type' => 'generate_student_certiificate',
                    'condition' => OnlyForAdmin(),
                    'icon' => array('add-notepad', 4),
                    'url' => 'student/create-certificate',
                ),
                array(
                    'label' => 'Get Certificate',
                    'type' => 'get_student_certificate',
                    'icon' => array('tablet-text-up', 3),
                    'url' => 'student/get-certificate',
                )
            )
        )/*,
        array(
            'label' => 'Student Certificate',
            'type' => 'stduent_certificate',
            'icon' => array('notepad', 5),
            'submenu' => array(
                array(
                    'label' => 'Generate Certificate',
                    'type' => 'generate_student_certiificate',
                    'icon' => array('add-notepad', 4),
                    'url' => 'student/generate-certificate',
                ),
                array(
                    'label' => 'Get Certificate',
                    'type' => 'get_student_certificate',
                    'icon' => array('tablet-text-up', 3),
                    'url' => 'student/get-certificate',
                )
            )
        ),
        array(
            'label' => 'Study Material',
            'type' => 'study_material',
            'icon' => array('message-text', 3),
            'url' => 'student/manage-study-material'
        )*/
    )
);

$config['exam_menu'] = array(
    'menu' => array(
        array(
            'url' => 'exam/questions-and-paper',
            'label' => 'Questions And Paper',
            'type' => 'questions_and_paper',
            'icon' => array('note-2', 4),
            'condition' => OnlyForAdmin()
        )/*,
        array(
            'label' => 'Assign Exam',
            'type' => 'assign_exam',
            'icon' => array('plus-circle', 2),
            // 'url' => 'exam/assign',
            'submenu' => array(
                array(
                    'url' => 'exam/assign-to-center',
                    'label' => 'To Center',
                    'icon' => array('cheque', 7),
                    'type' => 'assign_exam_to_center'
                ),
                array(
                    'url' => 'exam/assign-to-student',
                    'label' => 'To Student',
                    'icon' => array('cheque', 7),
                    'type' => 'assign_exam_to_student'
                )
            )
        ),
        array(
            'label' => 'Student Exam(S)',
            'type' => 'student_exams',
            'icon' => array('note-2', 4),
            'url' => 'exam/student-exams'
        )*/
    )
);

$config['center_area'] = array(
    'condition' => OnlyForAdmin(),
    'menu' => array(
        array(
            'label' => 'Center Information',
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
                    'label' => 'Assign Courses',
                    'type' => 'assign_courses_with_center',
                    'icon' => array('arrow-circle-right', 2),
                    'url' => 'center/assign-courses',
                ),
                array(
                    'label' => 'Pending Centers',
                    'type' => 'list_center',
                    'icon' => array('tablet-text-down text-warning', 4),
                    'url' => 'center/pending-list',
                ),
                array(
                    'label' => 'List Center',
                    'type' => 'list_center',
                    'icon' => array('tablet-text-down', 4),
                    'url' => 'center/list',
                ),
                array(
                    'label' => 'Deleted Center List',
                    'type' => 'deleted_center_list',
                    'icon' => array('tablet-text-down text-danger', 4),
                    'url' => 'center/deleted-list'
                )
            )
        ),
        array(
            'label' => 'Center Certificate',
            'type' => 'center_certificate',
            'icon' => array('notepad', 5),
            'url' => 'center/generate-certificate',
            /*
            'submenu' => array(
                array(
                    'label' => 'Center Certificate',
                    'type' => 'generate_center_certiificate',
                    'icon' => array('add-notepad', 4),
                    'url' => 'center/generate-certificate',
                ),
                array(
                    'label' => 'Get Certificate',
                    'type' => 'get_center_certificate',
                    'icon' => array('tablet-text-up', 3),
                    'url' => 'center/get-certificate',
                )
            )\
            */
        ),
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

$config['cms_setting'] = array(
    'menu' => array(
        array(
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
        ),
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
            'label' => 'Enquiry Data',
            'condition' => OnlyForAdmin(),
            'type' => 'enquiry_data',
            'icon' => array('tablet-text-down', 4),
            'url' => 'cms/enquiry-data'
        ),
        /*
        array(
            'label' => 'Gallery',
            'type' => 'gallery_section',
            'icon' => array('picture', 3),
            'submenu' => array(
                array(
                    'label' => 'Image Gallery',
                    'type' => 'image_gallery',
                    'icon' => array('picture', 4),
                    'url' => 'cms/image-gallery',
                ),
                array(
                    'label' => 'Video Gallery',
                    'type' => 'list_center',
                    'icon' => array('youtube', 4),
                    'url' => 'cms/video-gallery',
                )
            )
        ),
        */
    )
);

$config['setting'] = array(
    'menu' => array(
        array(
            'label' => 'Profile',
            'url' => 'admin/profile',
            'condition' => OnlyForCenter(),
        )
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
)

?>