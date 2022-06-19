<?php
$pgtitle = "Page not Found";
http_response_code(404);
$from = htmlspecialchars($_GET["from"]);
$id = htmlspecialchars($_GET["id"]);
?>
<?php include("header.php"); ?>
<div class="container my-4">
    <h1 class="display-4">Page not Found</h1>
    <p class="lead">It seems you may be lost... If you think it's a problem on our end, please contact us.</p>
</div>
<div class="container my-4">
    <?php include("includes/search.php"); ?>
</div>
<div class="container my-4">
    <div class="d-flex justify-content-start">
        <button type="button" class="btn btn-primary mx-3" onClick="window.history.back()"><i class="bi bi-arrow-left me-2"></i>Go Back</button>
        <a class="btn btn-primary mx-3" href="/" role="button"><i class="bi bi-house-fill me-2"></i>Go Home</a>
    </div>
</div>
<?php include("footer.php"); ?>