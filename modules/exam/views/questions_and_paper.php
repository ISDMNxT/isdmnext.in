<div class="row">
    <div class="col-md-12 mt-4">
        <div class="{card_class}">
            <div class="card-header">
                <h3 class="card-title"><i class="fa fa-list text-dark fw-bold fs-1"></i> &nbsp; List All Subjects</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="list-master-subjects">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Subject Name</th>
                                <th>Subject Code</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script id="template" type="text/x-handlebars-template">
    <label class="parent-ans btn btn-outline btn-outline-dashed btn-active-light-primary d-flex flex-stack text-start p-2 mb-5 {{parent_class}}">
        <div class="d-flex align-items-center me-2">
            <div class="form-check form-check-custom form-check-solid form-check-primary me-6">
                <input class="form-check-input is-right" type="radio" name="is_right[]" {{is_chcked}}/>
                <input type="hidden" name="ans[]" class="ans" value="{{answer}}">
            </div>
            <div class="flex-grow-1">
                <h2 class="d-flex align-items-center fs-3 fw-bold flex-wrap ans-title">
                    {{answer}}
                </h2>
            </div>
        </div>
        <div class="ms-5">
            <button class="btn btn-primary edit-ans btn-sm" type="button"><span class="fa fa-edit"></span></button>
            <button class="btn btn-danger delete-ans btn-sm" type="button"><span class="fa fa-trash"></span></button>
            <input type="hidden" name="ans_id[]" class="ans_id" value="{{answer_id}}">
        </div>
    </label>
</script>
