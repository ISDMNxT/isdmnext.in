<div class="row">
    <div class="col-md-12">
        <div class="{card_class}">
            <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                data-bs-target="#kt_docs_card_collapsible">
                <h3 class="card-title">Trainer Report Details</h3>
                <div class="card-toolbar rotate-180">
                    <i class="ki-duotone ki-down fs-1"></i>
                </div>
            </div>
            <div id="kt_docs_card_collapsible" class="collapse show">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="list_center" class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">

                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Subject</th>
                                    <th>Date</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                <?php foreach($data as $value) { ?>
                                    <tr>
                                        <td><?= $value->student_name.'('.$value->roll_no.')' ?></td>
                                        <td><?= $value->class_name ?></td>
                                        <td><?= $value->subject_name ?></td>
                                        <td><?= $value->plan_date ?></td>
                                        <td><?= $value->rating ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>