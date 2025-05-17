document.addEventListener('DOMContentLoaded', function () {
    const center_profile_box = $('#profile-box');
    const assign_form_and_display_box = $('#assign_form_and_display_box');
    const center_select_box = $('#select-center');
    const category_select_box = $('#select-category');

    // Init Select2
    center_select_box.select2({
        placeholder: "Select a Center",
        templateSelection: optionFormatSecond,
        templateResult: optionFormatSecond,
    });

    category_select_box.select2({
        placeholder: "Select Course Category"
    });

    function fetchCenterCourses() {
        center_profile_box.html('');
        assign_form_and_display_box.html('');

        var center_id = center_select_box.val();
        var category_id = category_select_box.val();

        if (!center_id) return;

        $.AryaAjax({
            url: 'center/get-course-assign-form',
            data: { id: center_id, category_id: category_id }
        }).then(function (rr) {
            var center_name = $('#select-center option:selected').data('kt-rich-content-subcontent');
            center_profile_box.html(rr.profile_html);
            assign_form_and_display_box.html(rr.html).find('#list-center-courses').DataTable({
                paging: true,
                pageLength: 10,
                searching: false,
                ordering: false,
                info: false,
                dom: 'lfrtip'
            });

            scrollToDiv(assign_form_and_display_box);

            assign_form_and_display_box.find(".assign-to-center").change(function () {
                var that = this,
                    course_id = $(this).val(),
                    label = $(this).closest('label'),
                    course_name = $(label).find(".course-name").text(),
                    course_duration = $(label).find('.course-duration').text(),
                    isDeleted = $(this).is(':checked') ? 0 : 1,
                    course_fee = $(this).data('amount');

                if ($(this).is(':checked')) {
                    SuccessSound();
                    $.AryaAjax({
                        data: { center_id, course_id, course_fee, isDeleted, type: 'one' },
                        url: 'center/assign-course'
                    }).then(function () {
                        fetchCenterCourses(); // Reload with category intact
                    });
                } else {
                    Swal.fire({
                        title: 'Notice',
                        icon: 'warning',
                        html: 'Are you sure for remove course from selected center',
                        showCancelButton: true,
                        confirmButtonText: 'ok',
                        cancelButtonText: 'Cancel',
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.AryaAjax({
                                data: { center_id, course_id, isDeleted, type: 'one' },
                                url: 'center/assign-course'
                            }).then(function () {
                                fetchCenterCourses();
                            });
                        } else {
                            $(that).prop('checked', true);
                        }
                    });
                }
            });

            assign_form_and_display_box.find(".assign-to-all").change(function () {
                if ($(this).prop("checked")) {
                    Swal.fire({
                        title: 'Notice',
                        icon: 'warning',
                        html: 'Are you sure you want to add all courses from selected category to this center?',
                        showCancelButton: true,
                        confirmButtonText: 'ok',
                        cancelButtonText: 'Cancel',
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.AryaAjax({
                                data: { center_id, category_id, type: 'all' },
                                url: 'center/assign-course'
                            }).then(function () {
                                fetchCenterCourses();
                            });
                        } else {
                            $(this).prop('checked', false);
                        }
                    });
                }
            });
        });
    }

    // Trigger on either center or category change
    center_select_box.on('change', fetchCenterCourses);
    category_select_box.on('change', fetchCenterCourses);
});
