<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><? echo $title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">  
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
<body class="h-100">
    <div class="backgroundImage">        
        <img src="../resources/images/classroom.jpg" alt="picture of a classroom as background" style="width:100%; height:100%">
    </div>
    <div class="w-100 h-100 d-flex justify-content-center flex-row text-white">
        <div class="hide-sm d-flex justify-content-center flex-column w-50 h-50">
            <div class="jumbotron jumbotron-fluid bg-secondary rounded shadow p-3" style="opacity: 0.9">                
                <h1 class="display-4 text-center mb-3">The Grade - Login</h1>                
                <div class="w-75 m-auto">
                    <form class="mb-3" action="/login" method="post">
                        <hr class="w-100 border border-white mt-3 mb-3" />
                        <input class="form-control mb-3 border border-lg" type="email" name="email" placeholder="You're E-Mail">                                                              
                        <input class="form-control mb-3 border border-lg" type="password" name="password" placeholder="You're Password">
                        <hr class="w-100 border border-white mt-3 mb-3" />
                    </form>                
                    <div class="d-flex flex-row">
                        <button login class="w-100 btn btn-outline-light mt-2 mb-3 mr-1">Login</button>
                        <button register class="w-100 btn btn-outline-light mt-2 mb-3 ml-1">Register</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const borderAlert = 'border-danger';
        const form = document.querySelector('form');
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('[login]').addEventListener('click', () => {
                if (validateInput() === true) {                    
                    form.submit();                   
                }
            });
            document.querySelector('[register]').addEventListener('click', () => {
                
            });
            
            document.addEventListener('keydown', e => {
                if (e.keyCode === 13) {
                    if (validateInput() === true) {
                        form.submit();
                    }
                }
            });

            Array.from(document.querySelectorAll('input[type=email], input[type=password]')).forEach(e => {
                e.addEventListener('input', () => {
                    e.classList.remove(borderAlert);
                });
            });
        });

        function validateInput() {
            let mailValid = false;
            let passwordValid = false;
            const mailPattern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            const minPassLength = 8;
            const inputs = Array.from(document.querySelectorAll('input[type=email], input[type=password]'));
            inputs.forEach(e => {
                if (e.type.toLowerCase() === "email") {
                    mailValid = mailPattern.test(String(e.value));
                    if (mailValid === false) {
                        highlightInput(e);
                    }
                } else if (e.type.toLowerCase() === "password") {
                    passwordValid = String(e.value).length > (minPassLength - 1);
                    if (passwordValid === false) {
                        highlightInput(e);
                    }
                }                
            });
            return mailValid && passwordValid;
        }

        function highlightInput(input) {
            input.classList.add(borderAlert);
        }
    </script>
</body>
</html>