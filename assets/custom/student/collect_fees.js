document.addEventListener('DOMContentLoaded', function (e) {
    const batch_Box = $('#batch_id');
    const liststudentBox = $('select[name="student_id"]');
    const viewStructure = $('.view-structure');
    var temp_student_id = '';
    //assets/media/student.png
    batch_Box.on('change', function () {
        var batch_id = $(this).val();
        viewStructure.html('');
        if (!batch_id) {
            $(liststudentBox[1]).html('<option></option>');
            return false;
        }
        $.AryaAjax({
            url: 'student/fetch-student-via-batch',
            data: { batch_id },
            loading_message: 'Fetch Students'
        }).then(function (data) {
            // console.log(data);
            $(liststudentBox[1]).html('<option></option>');
            if (data.data) {
                toastr.success(`Total ${data.data.length} stduents found.`);
                var img = '';
                $.each(data.data, function (index, myData) {
                    img = myData.image == null ? 'NULL' : myData.image;
                    $(liststudentBox[1]).append(`<option value="${myData.student_id}"  data-kt-rich-content-subcontent="${myData.roll_no}" data-kt-rich-content-icon="${img}" >${myData.student_name}</option>`);
                });
                $(liststudentBox[1]).select2({
                    templateSelection: optionFormatSecond,
                    templateResult: optionFormatSecond
                });
            }
            else {
                toastr.error('Students are not Found in this batch.');
            }
        });
    });

    liststudentBox.on('change', function (e) {
        if($(this).hasClass('first')){
            $(liststudentBox[1]).html('<option></option>');
            batch_Box.val('').trigger('change');
        }
        else{
            $(liststudentBox[0]).html('<option></option>')
        }

        var student_id = $(this).val();
        if(student_id != ''){
            var student_id = $(this).val();
            var studentName = $(this).find('option:selected').html();
            // console.log(studentName);
            if (!student_id) {
                toastr.warning('Please Select A Student.');
                viewStructure.html('');
                return false;
            }
            temp_student_id = student_id;
        } else {
            student_id = temp_student_id;
        }

        $.AryaAjax({
            url: 'payment/student-fee-structure',
            data: { student_id },
            loading_message: `Fetch ${badge(studentName)} Student Fee Structure..`
        }).then(function (d) {
            viewStructure.html(d.html).find('form').submit(function (e) {
                e.preventDefault();
                const form          = document.getElementById('my-fee-form');
                const validator     = MyFormValidation(form);
                var ttl             = 1;
                if (ttl) {
                    validator.validate().then(function (status) {
                        if (status == 'Valid') {
                            var formData = new FormData(form);
                            $.AryaAjax({
                                url: 'payment/collect-fee',
                                data: new FormData(form)
                                
                            }).then(function (r) {
                                if (r.status) {
                                    Swal.fire('Fees Submitted Successfully...');
                                    liststudentBox.trigger('change');
                                }
                            }).catch(function (r) {
                                console.warn(r.myerror);
                            });
                        }
                        else
                            toastr.error('Something went wrong.');
                    });
                }
            });
            var stickyElement = document.querySelector("#myfee-form");
            var sticky = new KTSticky(stickyElement);
            $('.current-date').flatpickr({
                maxDate: 'today',
                dateFormat: dateFormat
            });
        }).catch(function (d) {
            if (d) {
                console.warn(d.myerror);
            }
        });
    });

    select2Student(liststudentBox[0]);

    $(document).on('change', '.set-fee > input', function () {
        // console.log(form);
        var Boxclass = $(this).attr('id'),
            TempBox = $('.temp-list').find('tbody'),
            tr = $(this).closest('tr'),
            td = $(tr).find('td'),
            th = $($(tr).find('th')[0]).clone();


        get_month = th.text(),
            get_month_year = $(td[0]).html(),
            fee = $(td[1]).html(),
            // cal_fee = $('.per-month-fee').val(),
            ttl = Number($('.enter-fee').val()),
            name = $(this).attr('name'),
            type = $(this).closest('tr').data('type'),
            cal_fee = $(this).val();

        cal_fee = Number(cal_fee);
        log(type);
        var readonly = '';
        if (name == 'admission_fee' || type == 'exam_fee') {
            fee = get_month_year;
            get_month_year = 'One Time';
            readonly = 'readonly';
        }

        if(type == 'exam_fee'){
            get_month_year = `${tr.data('index')} Exam Fee`;
        }



        if ($(this).is(':checked')) {
            if(TempBox.find('tr').length == 0){
                TempBox.append(`<tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Fee</th>
                        <th>Discount</th>
                    </tr>`);
            }
            // ttl += cal_fee ;
            get_month_year = badge(get_month_year);
            TempBox.append(`<tr class="${Boxclass}">
                <td><b >${get_month}</b></td>
                <td>${get_month_year}</td>
                <td class=" form-group">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-rupee"></i></span>
                    <input type="text" class="form-control form-control-sm temp-amount" ${readonly} aria-label="Amount (to the nearest INR)" name="amount[${name}]" data-type="${type}" data-index="${name}" max="${cal_fee}" value="${cal_fee}" autocomplete="off">
                </div>
                </td>
                <td class=" form-group">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-rupee"></i></span>
                    <input type="text" class="form-control form-control-sm disount-temp-amount" ${readonly} aria-label="Discount Amount (to the nearest INR)" name="discount[${name}]" data-type="${type}" data-index="${name}" value="0" autocomplete="off">
                </div>
                </td>
            </tr>`).find('.temp-amount,.disount-temp-amount').on('keyup', cal);
        }
        else {
            // ttl -= cal_fee;

            TempBox.find(`.${Boxclass}`).remove();
            if(TempBox.find('tr').length == 1){
                TempBox.html(``);
            }
        }
        // $('.enter-fee').val(ttl);
        cal();
    })
    
    function filterAmount(amount){
        var cleanedValue = amount.replace(/[^0-9.]/g, ''); // Remove non-numeric characters except dot (.)

        // Ensure there is at most one dot in the cleaned value
        cleanedValue = cleanedValue.replace(/(\..*)\./g, '$1');
        return cleanedValue;
    }

    function cal() {
        var cal_fee = $('.per-month-fee').val();
        var ttl = 0;
        var discount = 0;
        $('.temp-amount').each(function (i,v) {
            var tempAmount = Number( filterAmount(($(this).val())) );
            var dicAmount = Number( filterAmount(($( $('.disount-temp-amount')[i] ).val())) );
            
            ttl += tempAmount ;
            ($(this).val(tempAmount));
            discount += dicAmount;//
            $( $('.disount-temp-amount')[i] ).val(dicAmount);
        });
        $('.ttl-discount').text(discount);
        $('.ttl-payable').text(ttl - discount);
        $('.enter-fee').val(ttl);
        $('.ttl-amount').text(ttl);
    }

    $(document).on('click', '.add-installment-record', function () {
        var main_fee_id = $(this).data('main_fee_id');
        $.AryaAjax({
            url: 'website/add-installment',
            data: { main_fee_id },
        }).then((r) => {
            ki_modal.find('.modal-dialog').addClass('modal-lg');
            myModel('Add Installment', r.html, 'website/save-installment').then((r) => {
                if (r.status) {
                    Swal.fire('Installment Added Successfully...');
                    liststudentBox.trigger('change');
                    select2Student(liststudentBox[0]);
                } else {
                    Swal.fire(r.error);
                }
            });
        });
    });

    $(document).on('click', '.edit-fee-record', function () {
        var fee_id = $(this).data('fee_id');
        $.AryaAjax({
            url: 'website/edit-fee-record',
            data: { fee_id },
        }).then((r) => {
            ki_modal.find('.modal-dialog').addClass('modal-lg');
            myModel('Edit Fee Record', r.html, 'website/update-fee-record').then((r) => {
                if (r.status) {
                    Swal.fire(r.html);
                    liststudentBox.trigger('change');
                    select2Student(liststudentBox[0]);
                } else {
                    Swal.fire(r.error);
                }
            });
        });
    });
    $(document).on('click', '.delete-fee-record', function () {
        var fee_id = $(this).data('fee_id');
        SwalWarning('Confirmation!', 'Are you sure for delete fee record', true, 'Yes').then((e) => {

            if (e.isConfirmed) {
                $.AryaAjax({
                    url: 'website/delete-fee-record',
                    data: { fee_id },
                    
                }).then((r) => {
                    Swal.fire('Record Deleted Successfully...');
                    liststudentBox.trigger('change');
                    select2Student(liststudentBox[0]);
                });
            }
        })
    });
});
