<?php if ($this->center_model->isAdmin()) { ?>
<!--begin::Basic info-->
<div class="card mb-5 mb-xl-10">
    <!--begin::Card header-->
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
        data-bs-target="#downloads_form_details" aria-expanded="true" aria-controls="kt_account_profile_details">
        <!--begin::Card title-->
        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Downloads</h3>
        </div>
        <!--end::Card title-->
    </div>
    <!--begin::Card header-->

    <!--begin::Content-->
    <div id="downloads_form_details" class="collapse show">
        <!--begin::Form-->
        <form id="downloads_form" class="form">
            <input type="hidden" name="download_id" id="download_id" value="<?php if(!empty($down['id'])) { echo $down['id']; } else { echo '0';} ?>">
            <!--begin::Card body-->
            <div class="card-body border-top p-9">
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label fw-semibold fs-6 required">Downloads For</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8 fv-row">
                        <select class="form-select" name="download_for">
                            <option value="center" <?php if((!empty($down['download_for']) && $down['download_for'] == "center") || empty($down['download_for'])) { echo 'selected="selected"'; } ?> >Center</option>
                            <option value="student" <?php if(!empty($down['download_for']) && $down['download_for'] == "student") { echo 'selected="selected"'; } ?>>Student</option>
                        </select>
                    </div>
                    <!--end::Col-->
                </div>
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label fw-semibold fs-6 required">Downloads Type</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8 fv-row">
                        <select class="form-select" name="type">
                            <option value="marketing" <?php if((!empty($down['type']) && $down['type'] == "marketing") || empty($down['type'])) { echo 'selected="selected"'; } ?>>Marketing Material</option>
                            <option value="operational" <?php if(!empty($down['type']) && $down['type'] == "operational") { echo 'selected="selected"'; } ?>>Operational Material</option>
                        </select>
                    </div>
                    <!--end::Col-->
                </div>
                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Title</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-12 fv-row">
                                <input type="text" name="title"
                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                    placeholder="Title" value="<?php if(!empty($down['title'])) { echo $down['title']; } ?>" />
                            </div>
                            <!--end::Col-->

                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label fw-semibold fs-6 required">Description</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8 fv-row">
                        <textarea type="text" name="description" class="form-control form-control-lg form-control-solid"
                            placeholder="Description"><?php if(!empty($down['description'])) { echo $down['description']; } ?></textarea>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->

                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label fw-semibold fs-6 required">Upload Files</label>
                    <input type="hidden" name="upload_files" id="upload_files" value="">
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <div class="form-group row">
                        <!--begin::Col-->
                        <div class="col-lg-10">
                            <!--begin::Dropzone-->
                            <div class="dropzone dropzone-queue mb-2" id="kt_dropzonejs_example_3">
                                <!--begin::Controls-->
                                <div class="dropzone-panel mb-lg-0 mb-2">
                                    <a class="dropzone-select btn btn-sm btn-primary me-2">Attach files</a>
                                    <a class="dropzone-remove-all btn btn-sm btn-light-primary">Remove All</a>
                                </div>
                                <!--end::Controls-->

                                <!--begin::Items-->
                                <div class="dropzone-items wm-200px">
                                    <div class="dropzone-item" style="display:none">
                                        <!--begin::File-->
                                        <div class="dropzone-file">
                                            <div class="dropzone-filename" title="some_image_file_name.jpg">
                                                <span data-dz-name>some_image_file_name.jpg</span>
                                                <strong>(<span data-dz-size>340kb</span>)</strong>
                                            </div>

                                            <div class="dropzone-error" data-dz-errormessage></div>
                                        </div>
                                        <!--end::File-->

                                        <!--begin::Progress-->
                                        <div class="dropzone-progress">
                                            <div class="progress">
                                                <div class="progress-bar bg-primary" role="progressbar"
                                                    aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"
                                                    data-dz-uploadprogress>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Progress-->

                                        <!--begin::Toolbar-->
                                        <div class="dropzone-toolbar">
                                            <span class="dropzone-delete" data-dz-remove><i
                                                    class="bi bi-x fs-1"></i></span>
                                        </div>
                                        <!--end::Toolbar-->
                                    </div>
                                </div>
                                <!--end::Items-->
                            </div>
                            <!--end::Dropzone-->

                            <!--begin::Hint-->
                            <span class="form-text text-muted">Max file size is 2MB and max number of files is
                                5.</span>
                            <!--end::Hint-->
                        </div>
                        <!--end::Col-->
                        </div>
                    </div>
                    <!--end::Col-->
                </div>
                <?php if(!empty($down['id'])) { ?>
                <div class="col-md-12 mt-5">
                    <div class="{card_class}">
                        <div class="card-header">
                            <h3 class="card-title">List Files</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped" id="list-images">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>File</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <!--end::Card body-->

            <!--begin::Actions-->
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button type="submit" class="btn btn-primary" id="downloads_form_details_submit">Save</button>
            </div>
            <!--end::Actions-->
        </form>
        <!--end::Form-->
    </div>
    <!--end::Content-->
</div>
<!--end::Basic info-->
<?php } ?>
<div class="col-md-12 mt-5">
    <div class="{card_class}">
        <div class="card-header">
            <h3 class="card-title">List Downloads</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped" id="list-downloads">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Download Type</th>
                            <th>Files</th>
                            <?php if ($this->center_model->isAdmin()) { ?>
                            <th>Action</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>