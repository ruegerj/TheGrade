</div>
<footer class="container-fluid w-75 d-flex border-top pl-3 pt-3 mr-auto">
    <small class="pl-0  ml-3 m-0">&copy; The Grade - Developed by Jan Rueger</small>
</footer>   
<script>
    $('document').ready(() => {
        $('[title]').tooltip();
        $('.dropdown-menu').find('a').on('click', function() {
            window.location = $(this).attr('href');                        
        });
    })
</script>
</body>
</html>