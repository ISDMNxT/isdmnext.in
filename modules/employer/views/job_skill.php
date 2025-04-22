<div class="row">
    <div class="col-md-12">
        <form id="job_skill">
            <div class="{card_class}">
                <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse" data-bs-target="#kt_docs_card_collapsible">
                    <h3 class="card-title">Add Job Skill</h3>
                    <div class="card-toolbar rotate-180">
                        <i class="ki-duotone ki-down fs-1"></i>
                    </div>
                </div>
                <div id="kt_docs_card_collapsible" class="collapse show">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="form-label required">Job Skill Name</label>
                                <input type="text" name="skill" class="form-control" placeholder="Job Skill Name">
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
            <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                data-bs-target="#list_job_skill_div">
                <h3 class="card-title">List Job Skill</h3>
                <div class="card-toolbar rotate-180">
                    <i class="ki-duotone ki-down fs-1"></i>
                </div>
            </div>
            <div id="list_job_skill_div" class="collapse show">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="list_job_skill" class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th>#</th>
                                    <th>Job Skill</th>
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
<script id="formTemplate" type="text/x-handlebars-template">
    <input type="hidden" name="id" value="{{id}}">
    <div class="form-group">
        <label class="form-label required">Job Skill Name</label>
        <input type="text" name="skill" value="{{skill}}" class="form-control" placeholder="Job Skill Name">
    </div>
</script>