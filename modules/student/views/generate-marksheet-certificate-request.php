<div class="col-md-12 mt-10">
        <div class="{card_class}">
            <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse" data-bs-target="#list">
                <h3 class="card-title">List Marksheet & Certificate Request</h3>
                <div class="card-toolbar rotate-180">
                    <i class="ki-duotone ki-down fs-1"></i>
                </div>
            </div>
            <div id="kt_docs_card_collapsible" class="collapse show">
                <?php if($this->center_model->isAdmin()) { ?>
                    <div class="form-group mb-4 col-md-4 col-xs-12 col-sm-12 mt-4" style="margin-left: 25px;margin-bottom: -25px;" >
                        <label class="form-label required">Search Center</label>
                        <select class="form-select searchCenter" name="searchCenter" id="searchCenter" data-control="select2" data-placeholder="Select a Center" >
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
                <?php } ?>
                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Datatable-->
                        <table id="marksheets" class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">

                                    <th>Center</th>
                                    <th>Student Name</th>
                                    <th>Course</th>
                                    <th>Exam</th>
                                    <th>Issue Date</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
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