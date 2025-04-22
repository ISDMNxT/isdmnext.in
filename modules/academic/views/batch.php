<div class="row">
    <!--
    <div class="col-md-5">
        <form id="btach_add">
            <div class="{card_class}">
                <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                    data-bs-target="#kt_docs_card_collapsible">
                    <h3 class="card-title">Add Batch</h3>
                    <div class="card-toolbar rotate-180">
                        <i class="ki-duotone ki-down fs-1"></i>
                    </div>
                </div>
                <div id="kt_docs_card_collapsible" class="collapse show">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label required">Enter Batch Name</label>
                            <input type="text" name="batch_name" class="form-control" placeholder="Enter batch name">
                        </div>
                    </div>
                    <div class="card-footer">
                        {publish_button}
                    </div>
                </div>
            </div>
        </form>
    </div>
    -->
    <div class="col-md-12">
        <form id="btach_add">
            <div class="{card_class}">
                <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse" data-bs-target="#kt_docs_card_collapsible">
                    <h3 class="card-title">Add Batch</h3>
                    <div class="card-toolbar rotate-180">
                        <i class="ki-duotone ki-down fs-1"></i>
                    </div>
                </div>
                <div id="kt_docs_card_collapsible" class="collapse show">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="form-label required">Batch Name</label>
                                <input type="text" name="batch_name" class="form-control" placeholder="batch name">
                            </div>
                            <div class="form-group col-md-3" <?php if($this->center_model->isCenter()) { ?> style="display: none" <?php } ?> >
                                <label class="form-label required">Center</label>
                                <?php
                                $center_id = 0;
                                if ($this->center_model->isCenter()) {
                                    $center_id = $this->center_model->loginId();
                                    $this->db->where('id', $center_id);
                                }
                                ?>
                                <select class="form-select" id="centre_id" name="center_id" data-control="select2"
                                    data-placeholder="Select a Center"
                                    data-allow-clear="<?= $this->center_model->isAdmin() ?>">
                                    <option></option>
                                    <?php
$list = $this->db->where('type', 'center')->get('centers')->result();
foreach ($list as $row) {
    $selected = $center_id == $row->id ? 'selected' : '';
    if (isset($exam['id']) && !empty($exam['center_id'])) {
        $selected = $exam['center_id'] == $row->id ? 'selected' : '';
    }

    echo '<option value="' . $row->id . '" ' 
    . $selected 
    . ' data-search="' . strtolower($row->name . ' ' . $row->institute_name) . '"'
    . ' data-kt-rich-content-subcontent="' . $row->institute_name . '">'
    . $row->name . ' (' . $row->institute_name . ')</option>';

}
?>
                                </select>
                            </div>
                            <div class="form-group col-md-3" >
                                <label class="form-label required">Course</label>
                                <select class="form-select" name="course_id" data-control="select2"
                                    data-placeholder="Select a Course" data-allow-clear="true">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-label required">From Date</label>
                                <input type="text" class="form-control date-with-notime" name="from_date" placeholder="dd/mm/yyyy" autocomplete="off">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-label required">To Date</label>
                                <input type="text" name="to_date" class="form-control date-with-notime" placeholder="dd/mm/yyyy">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-label required">From Time</label>
                                <input type="text" name="from_time" class="form-control only-time" placeholder="Select from time">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-label required">To Time</label>
                                <input type="text" name="to_time" class="form-control only-time" placeholder="Select to time">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-label required">Duration</label>
                                <input type="text" readonly="readonly" name="duration" class="form-control" placeholder="Enter duration">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        {publish_button}
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="col-md-12 mt-4">
        <div class="{card_class}">
            <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse" data-bs-target="#list">
                <h3 class="card-title">List Batch</h3>
                <div class="card-toolbar rotate-180">
                    <i class="ki-duotone ki-down fs-1"></i>
                </div>
            </div>
            <div id="list" class="collapse show">
                <div class="card-body">

                    <div class="table-responsive">
                        <!--begin::Datatable-->
                        <table id="batch_list" class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">

                                    <th>Batch Name</th>
                                    <th>Center</th>
                                    <th>Course</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Duration</th>
                                    <th class="text-end min-w-100px">Actions</th>
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

<script id="formTemplate" type="text/x-handlebars-template">
    <input type="hidden" name="id" value="{{id}}">
    <div class="form-group">
        <label class="form-label required">Batch Name</label>
        <input type="text" name="batch_name" value="{{batch_name}}" class="form-control" placeholder="Enter batch name">
    </div>
</script>