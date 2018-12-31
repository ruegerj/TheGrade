<h4>Subjects</h4>
<div class="container-fluid d-flex flex-column mt-3">
    <?
        $counter = 0;
        foreach ($data->Data as $subject) :
            $last = $counter === count($data->Data) - 1 && count($data->Data) % 2 !== 0;
            if ($counter % 2 == 0) : ?>
                <div class="row w-100 mb-xl-3">
            <? endif; ?>
                <div class="col-xl-6 mb-3 mb-sm-3 mb-md-3 mb-lg-3 mb-xl-0">
                    <div class="card h-100">        
                        <div class="card-body">
                            <div class="container d-flex flex-row justify-content-between m-0 row">
                                <h5 redirect class="card-title mb-1 mb-sm-1 mb-lg-0 mb-xl-0 clickable break-word col-12 col-sm-12 col-lg-3 col-xl-3 pl-lg-0" data-subjectId="<? echo $subject->Id; ?>"><? echo $subject->Title ?></h5>                            
                                <div class="break-word mb-2 mb-sm-2 mb-lg-0 mb-xl-0 w-50 col-12 col-sm-12 col-lg-4 col-xl-4">
                                    <p class="m-0"><? echo $subject->Description?></p>
                                </div>
                                <? if($subject->GradeAverage > 0) : 
                                    $colorClass = "text-danger";
                                    if ($subject->GradeAverage >= $GLOBALS["config"]["ui"]["grade"]["goodAbove"]) {
                                        $colorClass = "text-success";
                                    } else if ($subject->GradeAverage >= $GLOBALS["config"]["ui"]["grade"]["mediumAbove"]) {
                                        $colorClass = "text-secondary";
                                    }
                                ?>
                                    <div class="col-12 col-sm-12 col-lg-3 col-xl-3 mb-3 mb-sm-3 mb-lg-0 mb-xl-0 d-flex flex-row flex-lg-column justify-content-between font-weight-light">
                                        <h5 class="mb-0 <? echo $colorClass; ?>"><? echo number_format($subject->GradeAverage, 2); ?></h5>
                                        <h5 class="mb-0"><? echo $subject->Grading * 100; ?>%</h5>
                                    </div>
                                <? endif;?>
                                <div class="mb-0 d-flex flex-row flex-lg-column justify-content-center col-12 col-sm-12 col-lg-1 pr-lg-0">
                                    <div class="d-flex flex-row flex-lg-column justify-content-between w-100 h-100">
                                        <i class="fas fa-cog mb-0 mb-sm-0 mb-md-0 mb-lg-2 mb-xl-2 clickable" data-subjectId="<? echo $subject->Id; ?>"></i>
                                        <form action="/subject-del" method="post" class="d-flex flex-column justify-content-around mt-0 mt-sm-0 mt-md-0 mt-xl-2 mt-lg-2">
                                            <input type="hidden" name="aftoken" value="<? echo $data->SessionData->AntiForgeryToken?>">
                                            <input type="hidden" name="areaId" value="<? echo $areaId; ?>">
                                            <input type="hidden" name="subjectId" value="<? echo $subject->Id; ?>">
                                            <i class="fas fa-trash-alt clickable" data-title="<? echo $subject->Title; ?>"></i>                                    
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
                            <h5 class="card-title m-0">Add Subject</h5>                    
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
            <div class="row w-100 mb-xl-3">
                <div class="col-xl-6 mb-3 mb-sm-3 mb-md-3 mb-lg-3 mb-xl-0">
                    <div add class="card h-100 clickable hoverable">        
                        <div class="card-body">
                            <div class="container m-2 d-flex flex-row justify-content-between">
                                <h5 class="card-title m-0">Add Subject</h5>                    
                                <h3 class="fa fa-plus m-0"></h3>
                            </div>
                        </div>
                    </div>            
                </div>            
            </div>
        <? endif; ?>
</div>
<script>
    (function() {
        const subjectJson = JSON.parse('<? echo json_encode($data->Data); ?>');
        document.addEventListener('DOMContentLoaded', () => {
            $('[add]').click(() => {
                subjectModal.showModal(true);
            });

            Array.from(document.querySelectorAll('h5[redirect]')).forEach(e => {
                let id = e.dataset.subjectid;
                e.addEventListener('click', () => {
                    window.location.href = '/subject?id=' + id;
                });
            });

            Array.from(document.querySelectorAll('.fa-trash-alt')).forEach(e => {
                e.addEventListener('click', () => {
                    let subjectTitle = e.dataset.title;
                    let del = confirm('Do you really want to delete the subject ' + subjectTitle + ' ?');
                    if (del) {
                        e.parentNode.submit();
                    }
                });
            });

            Array.from(document.querySelectorAll('.fa-cog')).forEach(e => {
                e.addEventListener('click', () => {
                    const subjectId = e.dataset.subjectid;
                    const subjectData = subjectJson.filter(e => {
                        if (e.Id == subjectId) {
                            return e;
                        }
                    })[0];                    
                    subjectModal.showModal(false, subjectData);
                });
            });
        });
    })();
</script>
<? require_once(realpath($GLOBALS["config"]["paths"]["view"] . "/SubjectEditViewModal.php")); ?>