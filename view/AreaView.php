<h4>Areas</h4>
<div class="container-fluid d-flex flex-column mt-3">
    <?
        $counter = 0;
        foreach ($data->Data as $area) {
            if ($counter % 2 == 0) : ?>
                <div class="row w-100">            
            <? endif; ?>
                <div class="col-sm-6">
                    <div class="card">        
                        <div class="card-body d-flex flex-column justify-content-center clickable area-add">
                            <h5 class="card-title"><? echo $area->Title ?></h5>
                            <p class="card-text"></p>
                        </div>
                    </div>                
                </div>
            <?
            if ($counter % 2 == 0) : ?>
                </div>          
            <? endif; 
            $counter++;
        } 
        if (count($data->Data) % 2 != 0) : ?>        
            <div class="row w-100">
        <? endif;?>
            <div class="col-sm-6">
                <div add class="card clickable hoverable">        
                    <div class="card-body">
                        <div class="container m-2 d-flex flex-row justify-content-between">
                            <h5 class="card-title m-0">Add Area</h5>                    
                            <h3 class="fa fa-plus m-0"></h3>
                        </div>
                    </div>
                </div>            
            </div>
        <? if (count($data->Data) % 2 != 0) : ?>        
            </div>
        <? endif;
    ?>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        $('[add]').click(() => {
            $('#areaAddModal').modal();
        });
    });
</script>
<? require_once(realpath($GLOBALS["config"]["paths"]["view"] . "/AreaAddViewModal.php")); ?>