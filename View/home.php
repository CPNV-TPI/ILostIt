<?php
namespace ILostIt\View;

ob_start();

$title = "Home";
?>

<div class="home">
    <p>Home page</p>
</div>

<?php
$pageContent = ob_get_clean();
require 'gabarit.php';
?>
