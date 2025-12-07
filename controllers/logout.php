<?php
session_start();
session_unset();
session_destroy();
header("Location: /../InventarioVentas/views/auth/login.php");
exit();
