<h4>Exams</h4>
<div class="container-fluid d-flex flex-column mt-3">
    <? 
        $counter = 0;
        foreach($data->Data as $exam) : 
            $last = $counter === count($data->Data) - 1 && count($data->Data) % 3 !== 0;
            if ($counter % 3 == 0) : ?>
                <div class="row w-100 mb-3">
            <? endif; ?>
            <div class="col-sm-4">
                    <div class="card h-100">        
                        <div class="card-body">
                            <div class="container m-2 d-flex flex-row justify-content-between">
                                <h5 redirect class="card-title w-25 mb-0 mr-2 overflow"><? echo $exam->Title ?></h5>                            
                                <div class="overflow mb-0 ml-2 mr-2 w-50 d-flex flex-column">
                                    <i><? echo date("d.m.Y", $exam->Date); ?></i>
                                    <p class="m-0"><? echo $exam->Description?></p>
                                </div>
                                <? if($exam->Grade > 0) : 
                                    $colorClass = "text-danger";
                                    if ($exam->Grade >= $GLOBALS["config"]["ui"]["grade"]["goodAbove"]) {
                                        $colorClass = "text-success";
                                    } else if ($exam->Grade >= $GLOBALS["config"]["ui"]["grade"]["mediumAbove"]) {
                                        $colorClass = "text-secondary";
                                    }
                                ?>
                                    <div class="mb-0 mr-2 ml-2 d-flex flex-column font-weight-light">
                                        <h5 class="mb-0 align-self-start <? echo $colorClass; ?>"><? echo number_format($exam->Grade, 2); ?></h5>
                                        <h5 class="mb-0 align-self-end"><? echo $exam->Grading * 100; ?>%</h5>
                                    </div>
                                <? endif;?>
                                <div class="mb-0 ml-2 d-flex flex-column justify-content-between">
                                    <i class="fas fa-cog mb-2 clickable" data-examid="<? echo $exam->Id; ?>"></i>
                                    <form action="/exam-del" method="post">
                                        <input type="hidden" name="aftoken" value="<? echo $data->SessionData->AntiForgeryToken?>">
                                        <input type="hidden" name="subjectId" value="<? echo $subjectId; ?>">
                                        <input type="hidden" name="examId" value="<? echo $exam->Id; ?>">
                                        <i class="fas fa-trash-alt mt-2 clickable" data-title="<? echo $exam->Title; ?>"></i>                                    
                                    </form>
                                </div>
                            </div>                        
                        </div>
                    </div>                
                </div>
            <? if ($last) : ?>
                <div class="col-sm-4">
                    <div add class="card h-100 clickable hoverable">        
                        <div class="card-body">
                            <div class="container m-2 d-flex flex-row justify-content-between">
                                <h5 class="card-title m-0">Add Exam</h5>                    
                                <h3 class="fa fa-plus m-0"></h3>
                            </div>
                        </div>
                    </div>            
                </div>
                </div>
            <? endif;?>            
            <? $counter++;
            if ($counter % 3 == 0) : ?>
                </div>
            <? endif;                 
        endforeach;

        if (count($data->Data) % 3 === 0) : ?>
            <div class="row w-100 mb-3">
                <div class="col-sm-4">
                    <div add class="card h-100 clickable hoverable">        
                        <div class="card-body">
                            <div class="container m-2 d-flex flex-row justify-content-between">
                                <h5 class="card-title m-0">Add Exam</h5>                    
                                <h3 class="fa fa-plus m-0"></h3>
                            </div>
                        </div>
                    </div>            
                </div>            
            </div>
        <?endif;
    
    ?>
</div>
<script>
    (function() {
        const examJson = JSON.parse('<? echo json_encode($data->Data); ?>');
        document.addEventListener('DOMContentLoaded', () => {
            $('[add]').click(() => {
                subjectModal.showModal(true);
            });            

            Array.from(document.querySelectorAll('.fa-trash-alt')).forEach(e => {
                e.addEventListener('click', () => {
                    let examTitle = e.dataset.title;
                    let del = confirm('Do you really want to delete the exam ' + examTitle + ' ?');
                    if (del) {
                        e.parentNode.submit();
                    }
                });
            });

            Array.from(document.querySelectorAll('.fa-cog')).forEach(e => {
                e.addEventListener('click', () => {
                    const examId = e.dataset.examid;
                    const examData = examJson.filter(e => {
                        if (e.Id == examId) {
                            return e;
                        }
                    })[0];                                        
                    subjectModal.showModal(false, examData);
                });
            });
        });
    })();
</script>
<? require_once(realpath($GLOBALS["config"]["paths"]["view"] . "/ExamEditViewModal.php")); ?>