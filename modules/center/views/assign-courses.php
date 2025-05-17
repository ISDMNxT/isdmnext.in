<div class="row">
    <div class="col-md-6">
        <form action="" class="assign-course-to-center">
            <div class="{card_class}">
                <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                    data-bs-target="#kt_docs_card_collapsible">
                    <h3 class="card-title">Select Center & Course Category</h3>
                    <div class="card-toolbar rotate-180">
                        <i class="ki-duotone ki-down fs-1"></i>
                    </div>
                </div>
                <div id="kt_docs_card_collapsible" class="collapse show">
                    <div class="card-body">
                        <div class="border rounded mb-4">
                            <label class="form-label fw-bold">Select Center</label>
                            <select id="select-center" class="form-select form-select-transparent"
                                data-placeholder="Select A Center" data-allow-clear="true">
                                <option></option>
                                <?php
                                $list = $this->db->where('type', 'center')->get('centers')->result();
                                foreach ($list as $row) {
                                    $selected = isset($center_id) && $center_id == $row->id ? 'selected' : '';
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

                        <div class="border rounded mb-3">
                            <label class="form-label fw-bold">Select Course Category</label>
                            <select id="select-category" class="form-select form-select-transparent"
                                data-placeholder="Select A Category" data-allow-clear="true">
                                <option value="">All Categories</option>
                                <?php
                                $categories = $this->db->get('isdm_course_category')->result(); // Correct table name
                                foreach ($categories as $cat) {
                                    echo '<option value="' . $cat->id . '">' . $cat->title . '</option>'; // Use 'title' not 'name'
                                }
                                ?>
                            </select>
                        </div>

                        <?php if (!empty($category_name)): ?>
                            <div class="mt-2">
                                <h5>Courses under: <span class="text-primary"><?= $category_name ?></span></h5>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="col-md-4" id="profile-box">
    </div>

    <div class="col-md-12" id="assign_form_and_display_box">
    </div>
</div>
