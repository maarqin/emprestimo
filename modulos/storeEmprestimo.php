<?php

include '../src/autoinclude.php';

$e = new Emprestimo();

// Retorno do cadastro do cliente
// Retorna o complemento do nome para a class(html) do bootstrap(css)
$error = ( $e->store($_POST) > 0 ) ? 'success' : 'danger';

header('Location: ../index.php?error=' . $error);
