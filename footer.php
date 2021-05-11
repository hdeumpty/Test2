<?php?>

<footer class="footer">
  <div class="container-fluid">
    <div class="copyright ml-auto">
      <?php
      if (date('Y') == 2021) {
        echo 'Copyright &copy; 2021 Cyrille CARDAILLAC - Tous droits réservés.';
      }
      else {
        echo 'Copyright &copy; 2021 - ' . date('Y') . ' Cyrille CARDAILLAC - Tous droits réservés.';
      }
      ?>
    </div>
  </div>
</footer>
