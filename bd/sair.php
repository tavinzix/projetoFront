<?php
session_start();
session_destroy();
unset($_SESSION["logado"]);
unset($_SESSION["cpf"]);
header("Location:../index.php");
