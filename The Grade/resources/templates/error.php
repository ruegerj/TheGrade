<div class="jumbotron jumbotron-fluid bg-white shadow"> 
    <div class="container">
        <h1 class="display-4 text-danger">
            <?
                if (isset($errorCode) && strlen($errorCode) > 0) {
                    echo $errorCode;
                } else {
                    echo "Oops, something went wrong";
                }                
            ?>
        <h1>
        <p class="lead">
            <?
                if (isset($errorMessage) && strlen($errorMessage) > 0) {
                    echo $errorMessage;
                }
            ?>
        </p>
        <hr class="my-4" />
        <?
            if (isset($_SERVER["HTTP_REFERER"])) {
                $redirect = $_SERVER["HTTP_REFERER"]);
            }
            else {
                $redirect = $config["urls"]["baseUrl"];
            }
        ?>
        <a href="<? echo $redirect ?>" class="btn btn-secondary" role="button">Go Back to Previous Page</a>
    </div>
</div>