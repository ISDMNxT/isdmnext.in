<div class="row">
<style>
    .custom_setting_input{
        font-size: 18px!important;
        text-align: center;
    }
</style>    
<div class="col-md-12">
        <div class="card card-body marks-body">
            <div class="table-responsive">
                <table class="table table-bordered border-primary">
                    <thead class="bg-light-primary text-white">
                        <tr>
                            <th class="text-center fs-4" rowspan="2">Subject Code</th>
                            <th class="text-center fs-4" rowspan="2">Subject Name</th>
                            <th class="text-center fs-4" colspan="4">Details</th>
                        </tr>
                        <tr>
                            <th class="text-center fs-4">Subject Type</th>
                            <th class="text-center fs-4">Min Marks</th>
                            <th class="text-center fs-4">Max Marks</th>
                            <th class="text-center fs-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(count($subjects) > 0){
                        $template = '
                                        <td class="text-center fs-4">{title}</td>
                                         <td class="text-center fs-4">{min_marks}</td>
                                         <td class="text-center fs-4">{max_marks}</td>
                                         ';

                        foreach ($subjects as $subject) {
                            $rowspan = $subject['subject_type'] == 'both' ? 2 : 1;
                            ?>
                            <tr>
                                <td class="text-center fs-4" rowspan="<?= $rowspan ?>">
                                    <?= $subject['subject_code'] ?>
                                </td>
                                <td class="text-center fs-4" rowspan="<?= $rowspan ?>">
                                    <?= $subject['subject_name'] ?>
                                </td>
                                <?php
                                if ($subject['subject_type'] == 'both' or $subject['subject_type'] == 'theory') {
                                    echo $this->parser->parse_string($template, [
                                        'id' => $subject['id'],
                                        'title' => 'Theory',
                                        'name' => 'theory_marks',
                                        'max_marks' => $subject['theory_max_marks'],
                                        'min_marks' => $subject['theory_min_marks']
                                    ],true);
                                }
                                if ($subject['subject_type'] == 'practical') {

                                    echo $this->parser->parse_string($template, [
                                        'id' => $subject['id'],
                                        'title' => 'Practical',
                                        'name' => 'practical',
                                        'max_marks' => $subject['practical_max_marks'],
                                        'min_marks' => $subject['practical_min_marks']
                                    ],true);
                                }
                                ?>
                                 <td class="text-center fs-4" rowspan="<?= $rowspan ?>">
                                    <?php if(!empty($subject['study_material'])) { $ss = explode(",",$subject['study_material']); foreach($ss as $k => $v) { ?>
                                        <a class="btn btn-sm btn-light-info" target="_blank" href="<?= base_url() ?>upload/study_material/<?= $v ?>" ><?= $v ?></a>
                                    <?php }} ?>
                                </td>
                            </tr>

                            <?php
                            if ($subject['subject_type'] == 'both') {
                                echo '<tr>';
                                echo $this->parser->parse_string($template, [
                                    'id' => $subject['id'],
                                    'title' => 'Practical',
                                    'name' => 'practical',
                                    'max_marks' => $subject['practical_max_marks'],
                                    'min_marks' => $subject['practical_min_marks']
                                ],true);
                                echo '</tr>';
                            }
                        }
                        } else {
                        ?>
                        <tr>
                            <td class="text-center fs-4" colspan="5">Subject And Marks Not Mapped</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>