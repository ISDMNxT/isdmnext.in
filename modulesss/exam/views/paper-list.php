<table class="w-100 table table-striped table-bordered border-primary bg-light-primary" >
    <thead>
        <tr>
            <th>&nbsp;</th>
            <th>Paper Name</th>
            <th>Paper Type</th>
            <th>Total Marks</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
<?php
if(count($papers) > 0){
$index = 1; foreach ($papers as $paper) { ?>
    <tr>
        <td><?= $index++ ?></td>
        <td><?= $paper['paper_name'] ?></td>
        <td><?= $paper['paper_type'] ?></td>
        <td><?= $paper['total_marks'] ?></td>
        <td>
            <div data-param='<?=json_encode($paper)?>'>
                <?php if($popupType) { if($paper['paper_type'] == 'theortical') { ?>
                    <a class="btn btn-xs btn-sm btn-info set-questions" >Set Questions</a>
                <?php } } else { ?>
                <a class="btn btn-xs btn-sm btn-info edit" ><i class="fa fa-pencil"></i></a>
                <a class="btn btn-xs btn-sm btn-danger delete" ><i class="fa fa-trash"></i></a>
                <?php } ?>
            </div>
        </td>
    </tr>
<?php } } else { ?>
    <tr>
        <td colspan="5">No Paper(s) found in this subject.</td>
    </tr>
<?php }   ?>
    </tbody>
</table>