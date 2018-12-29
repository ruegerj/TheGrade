<h4>Areas</h4>
<div class="container-fluid d-flex flex-column mt-3">
    <?
        $counter = 0;
        foreach ($data->Data as $area) :
            $last = $counter === count($data->Data) - 1 && count($data->Data) % 2 !== 0;
            if ($counter % 2 == 0) : ?>
                <div class="row w-100 mb-xl-3">
            <? endif; ?>
                <div class="col-xl-6 mb-3 mb-sm-3 mb-md-3 mb-lg-3 mb-xl-0">
                    <div class="card h-100">        
                        <div class="card-body">
                            <div class="container d-flex flex-row justify-content-between row m-0">
                                <h5 redirect class="card-title mb-1 mb-sm-1 mb-lg-0 mb-xl-0 clickable break-word col-12 col-sm-12 col-lg-3 col-xl-3 pl-lg-0" data-areaid="<? echo $area->Id; ?>"><? echo $area->Title ?></h5>                            
                                <div class="break-word mb-2 mb-sm-2 mb-lg-0 mb-xl-0 w-50 col-12 col-sm-12 col-lg-4 col-xl-4">
                                    <p class="m-0"><? echo $area->Description?></p>
                                </div>
                                <? if($area->SubjectAverage > 0) : 
                                    $colorClass = "text-danger";
                                    if ($area->SubjectAverage >= $GLOBALS["config"]["ui"]["grade"]["goodAbove"]) {
                                        $colorClass = "text-success";
                                    } else if ($area->SubjectAverage >= $GLOBALS["config"]["ui"]["grade"]["mediumAbove"]) {
                                        $colorClass = "text-secondary";
                                    }    
                                ?>
                                    <h5 class="align-self-start col-12 col-sm-12 col-lg-3 col-xl-3 mb-3 mb-sm-3 mb-lg-0 mb-xl-0  <? echo $colorClass; ?>"><? echo number_format($area->SubjectAverage, 2); ?></h5>
                                <? endif;?>
                                <div class="mb-0 d-flex flex-row flex-lg-column justify-content-center col-12 col-sm-12 col-lg-1 pr-lg-0">
                                    <div class="d-flex flex-row flex-lg-column justify-content-between w-100 h-100">                                       
                                        <i class="fas fa-cog clickable" data-areaId="<? echo $area->Id; ?>"></i>                                        
                                        <form action="/area-del" method="post" class="d-flex flex-column justify-content-around">
                                            <input type="hidden" name="aftoken" value="<? echo $data->SessionData->AntiForgeryToken?>">
                                            <input type="hidden" name="areaId" value="<? echo $area->Id; ?>">
                                            <i class="fas fa-trash-alt clickable" data-title="<? echo $area->Title; ?>"></i>                                    
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
                            <h5 class="card-title m-0">Add Area</h5>                    
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
                    <div add class="car d h-100 clickable hoverable">        
                        <div class="card-body">
                            <div class="container m-2 d-flex flex-row justify-content-between">
                                <h5 class="card-title m-0">Add Area</h5>                    
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
        const areaJson = JSON.parse('<? echo json_encode($data->Data); ?>');
        document.addEventListener('DOMContentLoaded', () => {
            $('[add]').click(() => {
                areaModal.showModal(true);
            });

            Array.from(document.querySelectorAll('h5[redirect]')).forEach(e => {
                let id = e.dataset.areaid;
                e.addEventListener('click', () => {
                    window.location.href = '/area?id=' + id;
                });
            });

            Array.from(document.querySelectorAll('.fa-trash-alt')).forEach(e => {
                e.addEventListener('click', () => {
                    let areaTitle = e.dataset.title;
                    let del = confirm('Do you really want to delete the area ' + areaTitle + ' ?');
                    if (del) {
                        e.parentNode.submit();
                    }
                });
            });

            Array.from(document.querySelectorAll('.fa-cog')).forEach(e => {
                e.addEventListener('click', () => {
                    const areaId = e.dataset.areaid;
                    const areaData = areaJson.filter(e => {
                        if (e.Id == areaId) {
                            return e;
                        }
                    })[0];                    
                    areaModal.showModal(false, areaData);
                });
            });
        });
    })();
</script>
<? require_once(realpath($GLOBALS["config"]["paths"]["view"] . "/AreaEditViewModal.php")); ?>