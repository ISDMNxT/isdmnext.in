<div class="row">
    <div class="col-md-12">
        <form action="" id="subject_add_master">
            <div class="{card_class}">
                <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                    data-bs-target="#kt_docs_card_collapsible">
                    <h3 class="card-title"><i class="fa fa-plus text-dark fw-bold fs-1"></i> &nbsp;Add Subject</h3>
                    <div class="card-toolbar rotate-180">
                        <i class="ki-duotone ki-down fs-1"></i>
                    </div>
                </div>
                <div id="kt_docs_card_collapsible" class="collapse show">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subject_name" class="form-label required">Subject Name</label>
                                    <input type="text" class="form-control" name="subject_name"
                                        placeholder="Subject Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="" class="form-label required">Subject Code</label>
                                    <input type="text" class="form-control" placeholder="Subject Code"
                                        name="subject_code">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="file" class="form-label mt-4">Study Material</label>
                                    <input type="file" class="form-control" id="study_material" name="study_material[]" multiple="true">
                                </div>
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
                                <th>Study Material</th>
                                <th width="40%">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 mt-4">
        <div class="{card_class}">
            <div class="card-header">
                <h3 class="card-title"><i class="fa fa-list text-dark fw-bold fs-1"></i> &nbsp; List All Deleted Subjects</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="list-deleted-master-subjects">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Subject Name</th>
                                <th>Subject Code</th>
                                <th>Study Material</th>
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

<script id="formTemplate" type="text/x-handlebars-template">
    <input type="hidden" name="id" value="{{id}}">
    <div class="form-group mb-4">
        <label class="form-label required">Subject Name</label>
        <input type="text" name="subject_name" class="form-control" placeholder="Subject name" value="{{subject_name}}">
    </div>
    <div class="form-group mb-4">
        <label class="form-label">Subject Code</label>
        <input type="text" name="subject_code" class="form-control" placeholder="Subject Code" value="{{subject_code}}">
    </div>
    <div class="form-group mb-4">
        <label class="form-label">Study Material</label>
        <input type="file" class="form-control" id="study_material" name="study_material[]" multiple="true">
    </div>
</script>