<?php

include '../src/autoinclude.php';

$e = new Emprestimo();
$ep = new EmprestimoPagamento();

// Validacao PHP server-side
// Retorno da tentativa de pagamento do emprestimo
// Retorna o complemento do nome para a class(html) do bootstrap(css)

$id = $_POST['emprestimo_id'];

if( $dados = $e->getDadosFinanceirosById($id) ){

    // Valor pagos
    $pagos = $e->sumAllValoresPagos($id);

    // Valor a pagar
    $saldoDevedor = Uses::parseFloat($e->valorComJurosAPagar($dados->taxaJuros, $dados->diff, $dados->valor, $pagos));

    // Valor que o usuario digitou
    $valorPost = Uses::parseFloat($_POST['valor']);

    // Nao permite um valor superior ao saldo devedor
    if( $valorPost > $saldoDevedor ){
        header('Location: ../pagar.php?id=' . $id . '&error=danger');

    // Finalizar um financiamento(Paga o saldo devedor)
    } else if( $valorPost === $saldoDevedor ){
        if( $ep->storePagamento($_POST) > 0 ) {
            $resultado = ( $e->finalizar($id) > 0 ) ? 'success' : 'danger';

            header('Location: ../index.php?error=' . $resultado);
        } else {
            header('Location: ../index.php?error=danger');
        }
    } else { // Paga um pedaço do saldo devedor
        $resultado = ( $ep->storePagamento($_POST) > 0 ) ? 'success' : 'danger';
        header('Location: ../index.php?error=' . $resultado);
    }

}