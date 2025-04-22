<div class="card">
    <table class="table-striped table table-bordered">
        <tr>
            <th colspan="4" class="fs-2 fw-bold text-center ">
                <strong class="position-relative text-capitalize    ">
                    {institute_name}
                    <small class="text-success fs-2">Overview</small>
                    <span class="d-inline-block position-absolute h-2px bottom-0 end-0 start-0 bg-success translate rounded"></span>
                </strong>
            </th>
        </tr>
        <tr>
            <th class="w-25">Head Name</th>
            <td class="w-25">{name}</td>
            <th class="w-25">Designation</th>
            <td class="w-25">{qualification_of_center_head}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{email}</td>
            <th>Mobile</th>
            <td>{contact_number}</td>
        </tr>
        <tr>
            <th>Wallet</th>
            <td>{inr} {wallet}</td>
            <th>Whatsapp Number</th>
            <td>{whatsapp_number}</td>
        </tr>
        <tr>
            <th>City</th>
            <td><?= $this->SiteModel->get_city(['DISTRICT_ID' => $city_id])->row('DISTRICT_NAME') ?></td>
            <th>State</th>
            <td><?= $this->SiteModel->get_state(['STATE_ID' => $state_id])->row('STATE_NAME') ?></td>
        </tr>
        <tr>
            <th>Address</th>
            <td>{center_full_address}</td>
            <th>Pincode</th>
            <td>{pincode}</td>
        </tr>
    </table>
</div>