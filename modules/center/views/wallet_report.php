<div class="row">
    <div class="col-md-12">
        <form action="" id="add_form" name="add_form">
            <div class="{card_class}">
                <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                    data-bs-target="#kt_docs_card_collapsible">
                    <h3 class="card-title">Select Criteria</h3>
                    <div class="card-toolbar rotate-180">
                        <i class="ki-duotone ki-down fs-1"></i>
                    </div>
                </div>
                <div id="kt_docs_card_collapsible" class="collapse show">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="search_datee" class="form-label required">Date</label>
                                    <input type="text" class="form-control search_datee" value="<?= $filterDate ?>" id="search_datee" name="search_datee" placeholder="Select Search Date">
                                </div>
                            </div>
                            <div class="col-md-2 text-end">
                                <div class="form-group pt-8">
                                    {search_button}
                                </div>  
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
            <div class="card-header">
                <h3 class="card-title"><i class="fa fa-history fs-3 text-dark"></i> &nbsp;&nbsp;Wallet Report</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="list-history" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Center</th>
                                <th>Status</th>
                                <th>Message</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>