<div class="row">
    <?php if (!$this->center_model->isMaster()) { ?> 
    <div class="col-md-12 mb-4">
        <form id="add_course">
            <div class="{card_class}">
                <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                    data-bs-target="#kt_docs_card_collapsible">
                    <h3 class="card-title">Add Course</h3>
                    <div class="card-toolbar rotate-180">
                        <i class="ki-duotone ki-down fs-1"></i>
                    </div>
                </div>
                <div id="kt_docs_card_collapsible" class="collapse show">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-3 mb-4">
                                <label class="form-label required">Select Category</label>
                                <select name="category_id" data-control="select2"
                                    data-placeholder="Select Course Category" class="form-control">
                                    <option value="">Select Course Category</option>
                                    <?php
                                    $list = $this->db->order_by('title', 'ASC')->get('course_category');
                                    foreach ($list->result() as $row) {
                                        echo "<option value='{$row->id}'>{$row->title}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3 mb-4">
                                <label class="form-label required">Course Name</label>
                                <input type="text" name="course_name" class="form-control"
                                    placeholder="Course name">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-label required">Course duration</label>
                                <div class="input-group">
                                    <input type="text" name="duration" class="form-control" placeholder="Duration"
                                        autocomplete="off">
                                    <span class="input-group-text" id="basic-addon2"
                                        style="width:140px;padding:5px!important">
                                        <select name="duration_type" data-control="select2"
                                            data-placeholder="Duration Type" class="form-control">
                                            <?php
                                            foreach ($this->ki_theme->course_duration() as $key => $value)
                                                echo "<option value='{$key}'>{$value}</option>";
                                            ?>
                                        </select>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group col-md-3 mb-4">
                                <label class="form-label">Course Fee</label>
                                <input type="number" name="fees" class="form-control" placeholder="Course Fee">
                            </div>
                            <?php if ($this->center_model->isAdmin()) { ?>
                            <div class="form-group col-md-3 mb-4">
                                <label class="form-label">Royality Fee</label>
                                <input type="number" name="royality_fees" class="form-control" placeholder="Royality Fee">
                            </div>
                            <?php } ?>
                        </div>

                    </div>
                    <div class="card-footer">
                        <?php if ($this->center_model->isAdmin()) { ?>
                        {publish_button}
                        <?php } else { ?>
                        {sr_button}
                        <?php } ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php } ?> 
    <div class="col-md-12">
        <div class="{card_class}">
            <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse" data-bs-target="#list">
                <h3 class="card-title">List Course(s)</h3>
                <div class="card-toolbar rotate-180">
                    <i class="ki-duotone ki-down fs-1"></i>
                </div>
            </div>

            <div id="list" class="collapse show">
                <div class="form-group col-md-3 mt-4" style="margin-left: 25px;margin-bottom: -25px;" >
                    <label class="form-label required">Search Category</label>
                    <select name="categorySearch" data-control="select2"
                        data-placeholder="Select Course Category" class="form-control categorySearch">
                        <option value="0" selected="selected">All</option>
                        <?php
                        $list = $this->db->order_by('title', 'ASC')->get('course_category');
                        foreach ($list->result() as $row) {
                            echo "<option value='{$row->id}'>{$row->title}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Datatable-->
                        <table id="course_list" class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">

                                    <th>Course Name</th>
                                    <th>Course Category</th>
                                    <th>Duration</th>
                                    <th width=12%>Course Fee</th>
                                    <th width=12%>Royality Fee</th>
                                    <th width=12%>Status</th>
                                    <th class="text-end min-w-150px">Actions</th>
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

    <?php if ($this->center_model->isAdmin()) { ?> 
    <div class="col-md-12 mt-4">
        <div class="{card_class}">
            <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse" data-bs-target="#list">
                <h3 class="card-title">Deleted List Course(s)</h3>
                <div class="card-toolbar rotate-180">
                    <i class="ki-duotone ki-down fs-1"></i>
                </div>
            </div>
            <div id="list" class="collapse show">
                <div class="card-body">

                    <div class="table-responsive">
                        <!--begin::Datatable-->
                        <table id="deleted_course_list" class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">

                                    <th>Course Name</th>
                                    <th>Course Category</th>
                                    <th>Duration</th>
                                    <th width=12%>Fee</th>
                                    <th width=12%>Royality Fee</th>
                                    <th width=12%>Status</th>
                                    <th class="text-end min-w-150px">Actions</th>
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
    <?php } ?>
</div>
<?php if ($this->center_model->isCenter()) { ?>
    <script id="formTemplate" type="text/x-handlebars-template">
    <input type="hidden" name="id" value="{{course_id}}">
    <div class="form-group col-md-12 mb-4">
        <label class="form-label required">Select Category</label>
        <select name="category_id" data-control="select2"
            data-placeholder="Select Course Category" class="form-control">
            <option value="">Select Course Category</option>
            {{#each categories}}
                <option value="{{id}}" {{#if (eq ../category_id id)}}selected{{/if}}>{{title}}</option>
            {{/each}}
        </select>
    </div>
    <div class="form-group col-md-12 mb-4">
        <label class="form-label required">Course Name</label>
        <input type="text" name="course_name" class="form-control" placeholder="Course name" value="{{course_name}}">
    </div>
    <div class="form-group col-md-12 mb-4">
        <label class="form-label required">Course Fee</label>
        <input type="number" name="fees" class="form-control" placeholder="Course Fee" value="{{fees}}">
    </div>
    <div class="form-group col-md-12 mb-4">

        <label class="form-label required">Course duration</label>
        <div class="input-group">
            <input type="text" name="duration" class="form-control" placeholder="Duration"
                autocomplete="off" value="{{duration}}">
            <span class="input-group-text" id="basic-addon2" style="width:140px;padding:5px!important">
                <select name="duration_type" data-control="select2" data-placeholder="Duration Type" class="form-control">
                    <option value="month" {{#if (eq duration_type 'month')}}selected{{/if}}>Month</option>
                    <option value="year" {{#if (eq duration_type 'year')}}selected{{/if}}>Year</option>
                </select>
            </span>
        </div>
    </div>
</script>
<?php } ?>
<?php if ($this->center_model->isAdmin()) { ?>
<script id="formTemplate" type="text/x-handlebars-template">
    <input type="hidden" name="id" value="{{course_id}}">
    <div class="form-group col-md-12 mb-4">
        <label class="form-label required">Select Category</label>
        <select name="category_id" data-control="select2"
            data-placeholder="Select Course Category" class="form-control">
            <option value="">Select Course Category</option>
            {{#each categories}}
                <option value="{{id}}" {{#if (eq ../category_id id)}}selected{{/if}}>{{title}}</option>
            {{/each}}
        </select>
    </div>
    <div class="form-group col-md-12 mb-4">
        <label class="form-label required">Course Name</label>
        <input type="text" name="course_name" class="form-control" placeholder="Course name" value="{{course_name}}">
    </div>
    <div class="form-group col-md-12 mb-4">
        <label class="form-label required">Course Fee</label>
        <input type="number" name="fees" class="form-control" placeholder="Course Fee" value="{{fees}}">
    </div>
    <div class="form-group col-md-12 mb-4">

        <label class="form-label required">Course duration</label>
        <div class="input-group">
            <input type="text" name="duration" class="form-control" placeholder="Duration"
                autocomplete="off" value="{{duration}}">
            <span class="input-group-text" id="basic-addon2" style="width:140px;padding:5px!important">
                <select name="duration_type" data-control="select2" data-placeholder="Duration Type" class="form-control">
                    <option value="month" {{#if (eq duration_type 'month')}}selected{{/if}}>Month</option>
                    <option value="year" {{#if (eq duration_type 'year')}}selected{{/if}}>Year</option>
                </select>
            </span>
        </div>
    </div>
    <div class="form-group col-md-12 mb-4">
        <label class="form-label">Royality Fee</label>
        <input type="number" name="royality_fees" class="form-control" placeholder="Course Fee" value="{{royality_fees}}">
    </div>
</script>
<?php } ?>

