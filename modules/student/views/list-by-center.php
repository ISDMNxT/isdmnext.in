<div class="form-group mb-4 col-md-4">
    <label class="form-label required">Center</label>

    <select class="form-select" name="center_id" data-control="select2" data-placeholder="Select a Center"
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

<div class="row">
    <div class="col-md-12">
        <div class="card list-students">
            <div class="card-header">
                <h3 class="card-title">List Students</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <!--begin::Datatable-->
                    <table id="list-students" class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">

                                <th>Roll No</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Email</th>
                                <th>Center</th>
                                <th>Course</th>
                                <th>Batch</th>
                                <th>Father</th>
                                <th>Mother</th>
                                <th>State</th>
                                <th>City</th>
                                <th>Address</th>
                                <th>Pincode</th>
                                <th>Admission Type</th>
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