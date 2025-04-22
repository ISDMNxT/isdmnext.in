<?php
$center_id = 0;
$boxClass = '';
if ($this->center_model->isCenter()) {
    $center_id = $this->center_model->loginId();
    $this->db->where('id', $center_id);
    $boxClass = 'd-none';
}

?>
<div class="row <?= $boxClass ?>">
    <div class="col-md-12">
        <form id="treort">
            <div class="{card_class}">
                <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                    data-bs-target="#kt_docs_card_collapsible1">
                    <h3 class="card-title">Search</h3>
                    <div class="card-toolbar rotate-180">
                        <i class="ki-duotone ki-down fs-1"></i>
                    </div>
                </div>
                <div id="kt_docs_card_collapsible1" class="collapse show">
                    <div class="card-body">
                        <div class="row">
                            
                            <div class="form-group mb-4 col-md-4 col-xs-12 col-sm-12 <?= $boxClass ?>">
                                <label class="form-label required">Center</label>

                                <select class="form-select" name="center_id" id="center_id" data-control="select2"
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
                            <div class="form-group mb-4 col-md-4 col-xs-12 col-sm-12 mt-8 <?= $boxClass ?>">
                                {search_button}
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row mt-10">
    <div class="col-md-12">

        <div class="{card_class}">
            <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                data-bs-target="#kt_docs_card_collapsible">
                <h3 class="card-title">List Trainers</h3>
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

                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Center</th>
                                    <th>Rating</th>
                                    <th class="text-end min-w-100px">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>