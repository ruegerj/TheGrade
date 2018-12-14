<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
      <?
        if (isset($code)) {
          echo "Error - " . $code;
        } else {
          echo "Error - The Grade";
        }
      ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">  
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/../../resources/css/main.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- popper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>    
</head>
<body>
    <div class="jumbotron jumbotron-fluid bg-white"> 
        <div class="container">   
            <h1>The Grade</h1>         
            <h1 role="button" class="display-4 text-danger">
                <?
                    if (isset($code)) {
                        echo "Error " . $code;
                    } else {
                        echo "Oops, something went wrong...";
                    }                
                ?>            
            <h1>
            <hr class="my-4" />
            <p class="lead">
                <?
                    if (isset($message)) {
                        echo $message;
                    } else {
                        echo "We'll try to fix as fast as we can. Thanks for your patience.";
                    }                    
                ?>            
            </p>        
        </div>
        <div id="errorDetails" class="container collapse">
            <? echo $exception?>
        </div>
    </div>  
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('[role="button"]').addEventListener('click', () => {
                $('#errorDetails').collapse('toggle');
            });
        });
    </script>
</body>
</html>