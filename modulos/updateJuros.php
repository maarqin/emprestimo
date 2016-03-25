<?php

include '../src/autoinclude.php';

$e = new Emprestimo();

// Retorna a quantidade de linhas afetadas
$error = ( $e->renewJuros($_POST['emprestimo_id'], $_POST['taxaJuros']) > 0 ) ? 'success' : 'danger';

header('Location: ../index.php?error=' . $error);
