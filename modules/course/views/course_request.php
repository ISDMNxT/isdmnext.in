<div class="row">
    <div class="col-md-12">
        <div class="{card_class}">
            <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse" data-bs-target="#list">
                <h3 class="card-title"><?php if($this->center_model->isAdmin()) { ?>List Course Request<?php } else { ?>List Pending Course<?php } ?></h3>
                <div class="card-toolbar rotate-180">
                    <i class="ki-duotone ki-down fs-1"></i>
                </div>
            </div>
            <div id="list" class="collapse show">
                <div class="card-body">

                    <div class="table-responsive">
                        <!--begin::Datatable-->
                        <table id="course_list" class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <?php if($this->center_model->isAdmin()) { ?>
                                    <th>Course Name</th>
                                    <th>Course Category</th>
                                    <th>Duration</th>
                                    <th width=12%>Course Fee</th>
                                    <th width=12%>Royality Fee</th>
                                    <th width=12%>Status</th>
                                    <th class="text-end min-w-150px">Actions</th>
                                    <?php } else { ?>
                                        <th>Course Name</th>
                                        <th>Course Category</th>
                                        <th>Duration</th>
                                        <th width=12%>Status</th>
                                    <?php } ?>
                                    
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                            </tbody>
                        </table>
                        <!--end::Datatable-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>