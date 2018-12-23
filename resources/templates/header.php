<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
      <?
        if (isset($data->Title)) {
          echo $data->Title;
        } else {
          echo "The Grade";
        }
      ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">  
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <!-- Custom CSS-->
    <link rel="stylesheet" href="/resources/css/main.css" type="text/css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- popper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</head>
<body class="bg-light">  
<div class="container-fluid w-75 border-bottom pb-2">
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand p-0" href="/"><i class="fas fa-graduation-cap"></i> The Grade</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item d-flex flex-row justify-content-between">
            <h5 class="m-0 font-weight-light pt-1 pb-1">Hello <? echo $data->SessionData->UserPrename . " " . $data->SessionData->UserName?></h5>   
            <div class="align-self-center pl-2 p-1 dropdown">
              <span class="clickable" data-toggle="dropdown">
                <i class="fas fa-ellipsis-v"></i>
                <div class="dropdown-menu dropdown-menu-right bg-light p-0">
                  <a href="/logout" class="dropdown-item btn btn-default d-flex flex-row justify-content-between">
                    Logout                 
                    <i class="align-self-center fas fa-sign-out-alt"></i>
                  </a>
                </div>
              </span>            
            </div>         
          </li>
        </ul>
    </div>
  </nav>  
</div>
<? if (isset($crumbs)) :?>
<div class="container-fluid w-75 border-bottom pb-2">
  <ol class="breadcrumb bg-light mb-0 pt-2 pb-0">
    <?
      $crumbCount = count($crumbs) - 1;
      foreach ($crumbs as $key => $value) {
        $index = array_search($key, array_keys($crumbs));
        if ($index < $crumbCount) {         
          echo '<li class="breadcrumb-item"><a class="link-btn" href="'. $value .'">' . $key . '</a></li>';
        } else {        
          echo '<li class="breadcrumb-item active">' . $key . '</li>';
        }
      }
    ?>
  </ol>
</div>
<? endif;?>
<div class="container-fluid mb-3 mt-3 w-75 ">
