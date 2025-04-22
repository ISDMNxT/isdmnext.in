<div class="row">
    <input type="hidden" id="master_id" value="<?= $master_id ?>">
    <div class="col-md-12">
        <?php if (intval($master_id) > 0) { ?> 
            <?php
                $adata = $this->db->select('DATE_FORMAT(m.timestamp,"%d-%m-%Y") as timestamp, m.title, m.total_amount, m.amount, m.wallet_status, c.institute_name, m.status, m.type')
                ->from('master_wallet as m')
                ->join('centers as c', "c.id = m.center_id")
                ->where('master_franchise_id', $master_id)
                ->get()->result_array();

                $total_earning  = 0;
                $total_payout   = 0;
                $remaining      = 0;
                foreach($adata as $key => $val){
                    if($val['wallet_status'] == 'credit' && $val['status'] == '1'){
                        $total_earning = intval($total_earning)+intval($val['amount']);
                    } else if($val['wallet_status'] == 'debit' && $val['status'] == '1'){
                        $total_payout = intval($total_payout)+intval($val['amount']);
                    }
                }
                $remaining = intval($total_earning)-intval($total_payout);
            ?>
            <div class="{card_class}">
                <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                    data-bs-target="#kt_docs_card_collapsible">
                    <h3 class="card-title">Financial Summary</h3>
                    <div class="card-toolbar rotate-180">
                        <i class="ki-duotone ki-down fs-1"></i>
                    </div>
                </div>
                <div id="kt_docs_card_collapsible" class="collapse show">
                    <div class="form-group col-md-12 mt-4"  >
                        <label class="form-label" style="margin-left: 22px">TOTAL EARNING:&nbsp;</label>
                        {inr}<?= $total_earning ?>

                        <label class="form-label text-danger" style="margin-left: 320px">TOTAL PAYOUT :&nbsp;</label>
                        {inr}<?= $total_payout ?>

                        <label class="form-label text-success" style="margin-left: 380px">REMAINING:&nbsp;</label>
                        {inr}<?= $remaining ?>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <!--begin::Datatable-->
                            <table id="list_center" class="table align-middle table-row-dashed fs-6 gy-5">
                                <thead>
                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Details</th>
                                        <th>Center</th>
                                        <th>Amount</th>
                                        <th>Net Earning/Payout</th>
                                        <th>Transaction Type</th>
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
        <?php } else { ?> 
            <div class="{card_class}">
                <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                    data-bs-target="#kt_docs_card_collapsible">
                    <h3 class="card-title">Wallet List & Transection</h3>
                    <div class="card-toolbar rotate-180">
                        <i class="ki-duotone ki-down fs-1"></i>
                    </div>
                </div>
                <div id="kt_docs_card_collapsible" class="collapse show">
                    <div class="card-body">
                        <div class="table-responsive">
                            <!--begin::Datatable-->
                            <table id="list_center" class="table align-middle table-row-dashed fs-6 gy-5">
                                <thead>
                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">

                                        <th>Prefix Roll No</th>
                                        <th>Institute ID</th>
                                        <th>Institute Name</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Wallet</th>
                                        <th class="text-end min-w-100px">Actions</th>
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
        <?php } ?>
    </div>
</div>