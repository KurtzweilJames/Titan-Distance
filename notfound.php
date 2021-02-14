<?php $pgtitle = "Page not Found"; http_response_code(404); ?>
<?php include("header.php");?>
<div class="container my-4">
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1 class="display-4">Page not Found</h1>
            <p class="lead">It seems you may be lost...</p>
            <p class="lead"><a href="/">Return Home</a></p>
        </div>
    </div>
</div>
<?php include("footer.php");?>