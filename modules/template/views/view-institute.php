<div class="row">
<style>
    .custom_setting_input{
        font-size: 18px!important;
        text-align: center;
    }
</style>    
<div class="col-md-12">
        <div class="card card-body marks-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered border-primary">
                    <thead class="bg-light-primary text-white">
                        <tr>
                            <th class="text-center fs-4">Institutes Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $per        = []; 
                        if(!empty($centerss[0]['id'])) { $per = json_decode($centerss[0]['permission'],true); }
                        $list       = $this->center_model->get_all_center()->result();
                        if(count($per) > 0){
                        foreach($list as $key => $row){  if(in_array($row->id, $per)) { ?>
                                            
                            <tr>
                                <td class="text-center fs-4" >
                                    <?= $row->institute_name ?>
                                </td>
                            </tr>
                                        
                        <?php } }
                       
                        } else {
                        ?>
                        <tr>
                            <td class="text-center fs-4" colspan="5">Institutes Not Mapped</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>