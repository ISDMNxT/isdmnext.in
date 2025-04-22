<div class="row">
    <input type="hidden" name="c_id" id="c_id" value="<?= $c_id ?>">
    <div class="col-md-12">
        <div class="{card_class}">
            <div class="card-header">
                <h3 class="card-title"><i class="fa fa-history fs-3 text-dark"></i> &nbsp;&nbsp;Wallet History</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="list-history" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Student Name</th>
                                <th>Status</th>
                                <th>Message</th>
                                <th>Amount</th>
                                <?php if ($this->center_model->isAdmin()) { ?>
                                    <th>Action</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
