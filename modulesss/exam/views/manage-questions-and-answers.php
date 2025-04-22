<?php if($popupType == "paper"){ ?>
<table class="w-100 table table-striped table-bordered border-primary bg-light-primary" >
    <tbody>
        <tr>
            <th>Paper Name</th>
            <th>Paper Type</th>
            <th>Total Marks</th>
        </tr>
        <tr>
            <td><?=$paperData['paper_name']?></td>
            <td><?=$paperData['paper_type']?></td>
            <td>
                <?=$paperData['total_marks']?>
                <input type="hidden" name="paper_id" id="paper_id" value="<?=$paperData['id']?>">
                <input type="hidden" name="total_marks" id="total_marks" value="<?=$paperData['total_marks']?>">
            </td>
        </tr>
    </tbody>
</table>
<?php $paper_ids = $this->exam_model->list_question_papers_ids($paperData['id']); } else { $paper_ids = [];} ?>

<?php
$index = 1;
foreach ($questions as $ques) {
    $list = $this->exam_model->list_question_answers($ques['id']);
    $data = [];
    $key = 'first';
    $i = 0;
    $ques['question'] = htmlentities($ques['question'], ENT_QUOTES, 'UTF-8');
    $editData = [
        'id' => $ques['id'],
        'question' => $ques['question'],
        'question_type' => $ques['question_type'],
        'difficulty_level' => $ques['difficulty_level'],
        'marks' => $ques['marks'],
        'negative_marks' => $ques['negative_marks']
    ];

    foreach ($list->result() as $ans) {
        $ans->answer = htmlentities($ans->answer, ENT_QUOTES, 'UTF-8');
        $editData['answers'][$ans->answer_id] = [
            'answer' => $ans->answer,
            'is_right' => $ans->is_right,
            'parent_class' => $ans->is_right ? 'active' : '',
            'is_chcked' =>  $ans->is_right ? 'checked' : '',
        ];
        $new = [
            $key => $ans->answer,
            "{$key}_is_right" => $ans->is_right
        ];
        if (isset ($data[$i]))
            $data[$i] = array_merge($data[$i], $new);
        else
            $data[$i] = $new;
        if ($key == 'second') {
            $key = 'first';
            $i++;
        } else {
            if ($key == 'first') {
                $key = 'second';
            }
        }
    }
    ?>
    <table class="w-100 table table-striped table-bordered border-primary bg-light-primary" data-param='<?=json_encode($editData)?>'>
        <tbody>
            

            <?php if($popupType != "paper"){ ?>
                <tr>
                    <th colspan="2" class="pe-4 fs-2">
                        <div class="d-flex flex-stack">
                            <div class="">
                                <i class="fs-4 text-warning">QUE
                                    <?= $index++ ?>.
                                </i>
                                <b class="text-dark">
                                    <?= $ques['question']; ?>
                                </b>
                            </div>
                            <div class="">
                                <a class="btn btn-xs btn-sm btn-info edit"><i class="fa fa-pencil"></i></a>
                                <a class="btn btn-xs btn-sm btn-danger delete"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                    </th>
                </tr>
            <?php } ?>

           <?php if($popupType == "paper"){ ?>
                <tr>
                    <th colspan="2" class="pe-4 fs-2">
                        <div class="d-flex flex-stack">
                            <div class="">
                                
                                <input type="checkbox" class="ques_names" name="q_ids[]" value="<?= $ques['id'] ?>" <?php if(count($paper_ids) > 0 && in_array($ques['id'], $paper_ids)) { ?> checked="true" <?php } ?> >
                                <input type="hidden" id="marks_<?= $ques['id'] ?>" name="marks_<?= $ques['id'] ?>" value="<?= $ques['marks'] ?>" >
                                
                                <i class="fs-4 text-warning">QUE
                                    <?= $index++ ?>.
                                </i>
                                <b class="text-dark">
                                    <?= $ques['question'] ?>
                                </b>
                            </div>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th colspan="2" class="pe-4 fs-2">
                        <div class="d-flex flex-stack">
                            <div class="">
                                <i class="fs-4 text-warning">MARKS - </i>
                                <b class="text-dark">
                                    <?= $ques['marks'] ?>
                                </b>
                            </div>
                        </div>
                    </th>
                </tr>
            <?php } ?>

            <?php
            if (count($data)) {
                $i = 1;
                foreach ($data as $ans) {
                    echo '<tr><td>';
                    echo isset ($ans['first']) ? ($i++) . '.<span class="p-4 fs-3 fw-bold"> ' . $ans['first'] . '</span>' . ($ans['first_is_right'] ? $this->ki_theme->keen_icon('double-check-circle text-success mt-3', 3, 1, 'solid') : '') : '';
                    echo '</td><td>';
                    echo isset ($ans['second']) ? ($i++) . '.<span class="p-4 fs-3 fw-bold">' . $ans['second'] . '</span>' . ($ans['second_is_right'] ? $this->ki_theme->keen_icon('double-check-circle text-success mt-3', 3, 1, 'solid') : '') : '';
                    echo '</></tr>';
                }
            } else {
                echo '<tr><td colspan="2">' . alert('No Answers found.', 'danger') . '</td></tr>';
            }
            ?>
        </tbody>
    </table>
    <?php
}
?>
