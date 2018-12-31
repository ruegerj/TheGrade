<h4>Exams</h4>
<div class="container-fluid d-flex flex-column mt-3">
    <? 
        $counter = 0;
        foreach($data->Data as $exam) : 
            $last = $counter === count($data->Data) - 1 && count($data->Data) % 2 !== 0;
            if ($counter % 2 == 0) : ?>
                <div class="row w-100 mb-xl-3">
            <? endif; ?>
            <div class="col-xl-6 mb-3 mb-sm-3 mb-md-3 mb-lg-3 mb-xl-0">
                    <div class="card h-100">        
                        <div class="card-body">
                            <div class="container d-flex flex-row justify-content-between m-0 row">
                                <h5 redirect class="card-title mb-1 mb-sm-1 mb-lg-0 mb-xl-0 break-word col-12 col-sm-12 col-lg-3 col-xl-3 pl-lg-0"><? echo $exam->Title ?></h5>                            
                                <div class="break-word mb-2 mb-sm-2 mb-lg-0 mb-xl-0 w-50 col-12 col-sm-12 col-lg-4 col-xl-4">
                                    <i date data-unix="<? echo $exam->Date; ?>"><? echo date("d.m.Y", $exam->Date); ?></i>
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
                                    <div class="col-12 col-sm-12 col-lg-3 col-xl-3 mb-3 mb-sm-3 mb-lg-0 mb-xl-0 d-flex flex-row flex-lg-column justify-content-between font-weight-light">
                                        <h5 class="mb-0 <? echo $colorClass; ?>"><? echo number_format($exam->Grade, 2); ?></h5>
                                        <h5 class="mb-0"><? echo $exam->Grading * 100; ?>%</h5>
                                    </div>
                                <? endif;?>
                                <div class="mb-0 d-flex flex-row flex-lg-column justify-content-center col-12 col-sm-12 col-lg-1 pr-lg-0">
                                    <div class="d-flex flex-row flex-lg-column justify-content-between w-100 h-100">
                                        <i class="fas fa-cog mb-0 mb-sm-0 mb-md-0 mb-lg-2 mb-xl-2 clickable" data-examid="<? echo $exam->Id; ?>"></i>
                                        <form action="/exam-del" method="post" class="d-flex flex-column justify-content-around mt-0 mt-sm-0 mt-md-0 mt-xl-2 mt-lg-2">
                                            <input type="hidden" name="aftoken" value="<? echo $data->SessionData->AntiForgeryToken?>">
                                            <input type="hidden" name="subjectId" value="<? echo $subjectId; ?>">
                                            <input type="hidden" name="examId" value="<? echo $exam->Id; ?>">
                                            <i class="fas fa-trash-alt clickable" data-title="<? echo $exam->Title; ?>"></i>                                    
                                        </form>                                
                                    </div>
                                </div>
                            </div>                        
                        </div>
                    </div>                
                </div>
            <? if ($last) : ?>
                <div class="col-xl-6 mb-3 mb-sm-3 mb-md-3 mb-lg-3 mb-xl-0">
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
            if ($counter % 2 == 0) : ?>
                </div>
            <? endif;                 
        endforeach;

        if (count($data->Data) % 2 === 0) : ?>
            <div class="row w-100 mb-3">
                <div class="col-xl-6 mb-3 mb-sm-3 mb-md-3 mb-lg-3 mb-xl-0">
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

            Array.from(document.querySelectorAll('[date]')).forEach(e => {
                const uf = new UnixFormatter(e.dataset.unix);
                e.setAttribute('title', uf.textFormat);

            });
            $('[title]').tooltip();
        });
    })();
</script>
<? require_once(realpath($GLOBALS["config"]["paths"]["view"] . "/ExamEditViewModal.php")); ?>