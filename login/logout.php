<?php
session_start();
session_unset();
session_destroy();

header("Location: ../pagina.principal/pagina.principal.php");
exit();