<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/site_template/header.php';
?>

<div class="container text-center my-5">        
        <?php
            if (isset($_SESSION['message'])) {
                echo '<p><div class="alert alert-success" role="alert">
                ' . $_SESSION['message'] . '</div></p>';
            }
            unset ($_SESSION['message']);                
        ?>
</div>

<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/site_template/footer.php';
?>
