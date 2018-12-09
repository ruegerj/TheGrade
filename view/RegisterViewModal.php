<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content bg-secondary text-white">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Register</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
            <form action="/register" method="post" id="registerForm">
                <input type="hidden" name="aftoken" value="<? echo $afToken?>" />
                <div class="d-flex flex-row justify-content-between mb-3">
                    <input reg class="form-control border" type="text" name="prename" placeholder="You're Prename">  
                    <h3 class="text-danger far fa-times-circle align-self-center ml-2 mb-0 p-1"></h3>   
                </div>
                <div class="d-flex flex-row justify-content-between mb-3">
                    <input reg class="form-control border" type="text" name="name" placeholder="You're Name">  
                    <h3 class="text-danger far fa-times-circle align-self-center ml-2 mb-0 p-1"></h3>   
                </div>
                <div class="d-flex flex-row justify-content-between mb-3">
                    <input reg class="form-control border" type="email" name="email" placeholder="You're E-Mail"> 
                    <h3 class="text-danger far fa-times-circle align-self-center ml-2 mb-0 p-1"></h3>   
                </div>
                <div class="d-flex flex-row justify-content-between mb-3">
                    <input reg class="form-control border" type="password" name="password" placeholder="Choose a Password">  
                    <h3 class="text-danger far fa-times-circle align-self-center ml-2 mb-0 p-1"></h3>   
                </div>
                <div class="d-flex flex-row justify-content-between mb-3">
                    <input reg class="form-control border" type="password" name="passwordConfirm" placeholder="Reenter you're Password"> 
                    <h3 class="text-danger far fa-times-circle align-self-center ml-2 mb-0 p-1"></h3>   
                </div>                                                
            </form>
        </div>
      </div>
      <div class="modal-footer">
        <button id="registerBtn" type="button" class="btn btn-outline-light w-100">Register</button>
      </div>
    </div>
  </div>
</div>
<script>
    (function modalJs () {
        let prenameC, nameC, mail, password, passwordR, currentPassword;
    const failureIcon = "fa-times-circle";
    const failureColor = "text-danger";
    const failureBorder= "border-danger";
    const validIcon = "fa-check-circle";
    const validColor = "text-success";    

    document.addEventListener('DOMContentLoaded', () => {
        Array.from(document.querySelectorAll('[reg]')).forEach(e => {
            e.addEventListener('input', () => {
                checkInput(e);
            });
        });

        document.getElementById('registerBtn').addEventListener('click', () => {            
            if (prenameC && nameC && mail && password && passwordR) {
                document.getElementById('registerForm').submit();
            }
        });
    });

    function checkInput(elem) {        
        const mailPattern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        const minPassLength = (8 - 1);
        const inputType = elem.type;
        const inputVal = String(elem.value);
        if (inputType === "text") {
            if (inputVal.length >= 3 && inputVal.length <= 30) {
                setSuccessState(elem);
                elem.name === "prename" ? prenameC = true : nameC = true;
            } else {
                setFailureState(elem);
                elem.name === "prename" ? prenameC = false : nameC = false;
            }
        } else if (inputType === "email") {
            if (mailPattern.test(inputVal) && inputVal.length <= 50) {
                setSuccessState(elem);
                mail = true;
            } else {
                setFailureState(elem);
                mail = false;
            }
        } else if (inputType === "password") {
            if (elem.name === "password") {
                if (inputVal.length > minPassLength && inputVal.length <= 50) {
                    setSuccessState(elem);
                    password = true;
                } else {
                    setFailureState(elem);
                    password = false;
                }
                currentPassword = inputVal;
            } else {
                if (currentPassword === inputVal) {
                    setSuccessState(elem);
                    passwordR = true;
                } else {
                    setFailureState(elem);
                    passwordR = false;
                }
            }
        }

        return prenameC && nameC && mail && password && passwordR;
    }

    function setSuccessState(input) {
        const icon = Array.from(input.parentNode.children).filter(n => {
            if (n.classList.contains("far")) {
                return n;
            }
        })[0];     
        icon.classList.add(validIcon);
        icon.classList.add(validColor);
        icon.classList.remove(failureIcon);
        icon.classList.remove(failureColor);
    }

    function setFailureState(input) {
        const icon = Array.from(input.parentNode.children).filter(n => {
            if (n.classList.contains("far")) {
                return n;
            }
        })[0];     
        icon.classList.add(failureIcon);
        icon.classList.add(failureColor);
        icon.classList.remove(validIcon);
        icon.classList.remove(validColor);
    }
    })();    
</script>