<div class="col-md-12 mt-4">
    <div class="{card_class}">
        <div class="card-header">
            <h3 class="card-title"><i class="fa fa-list text-dark fw-bold fs-1"></i> &nbsp; Class Reports</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="list-master-class">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Subject</th>
                            <th>Title</th>
                            <th>Trainer</th>
                            <th>Date</th>
                            <th>Notes</th>
                            <th width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script id="formTemplate" type="text/x-handlebars-template">
    <input type="hidden" name="class_plan_id" value="{{id}}">
    <div class="form-group">
        <label for="rating" class="form-label required">Rating</label>
        <select name="rating" class="form-select" >
            <option value="1" >Star</option>
            <option value="2" >2 Star</option>
            <option value="3" >3 Star</option>
            <option value="4" >4 Star</option>
            <option value="5" >5 Star</option>
        </select>
    </div>
    <div class="form-group mt-4">
        <label for="description" class="form-label required">Description</label>
        <textarea name="description" id="description" rows="2" placeholder="Enter Description"
            class="form-control "></textarea>
    </div>
</script>