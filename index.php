<!doctype html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Marcos Thomaz">
    <title>Empréstimo realizados</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<style type="text/css">
    td {
        vertical-align: middle !important;
    }
</style>
<body>

<div class="container">
    <h1>Empréstimos</h1>

    <?php

    // Informacao ao usuario
    if( isset($_GET['error']) ){
        $msg = ( $_GET['error']  == 'success' ) ? 'Feito com sucesso' : 'Oops! Sua ação não pôde ser finalizada';
        echo '<div class="alert alert-' . $_GET['error'] . '"> ' . $msg . '</div>';
    }

    ?>

    <a class="btn btn-primary" href="emprestimo.php">Criar novo</a>
    <form class="form-inline" action="/" method="get" style="float: right">
        <div class="form-group">
            <label class="sr-only">Password</label>
            <input type="search" class="form-control" placeholder="Procurar" name="q" value="<?=$_GET['q']?>">
        </div>
        <button type="submit" class="btn btn-default">Ok</button>
    </form>

    <div class="row">
        <div class="col-lg-12">
            <div class="widget-container fluid-height clearfix">
                <div class="widget-content padded clearfix">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    ID
                                </th>
                                <th>
                                    Cliente
                                </th>
                                <th>
                                    Contatos
                                </th>
                                <th>
                                    Data
                                </th>
                                <th>
                                    Valor do empréstimo (R$)
                                </th>
                                <th>
                                    Juros (%)
                                </th>
                                <th>
                                    Qtde. dias utilizados
                                </th>
                                <th>
                                    Saldo devedor (R$)
                                </th>
                                <th>
                                    Pagar
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php

                        include 'src/autoinclude.php';

                        $e = new Emprestimo();

                        $pago = ( $_GET['pagos'] == 1 ) ? array('=', 1, 'and') : array('is', null, 'and');

                        $dados = $e->all(array('pago' => $pago, 'cliente' => array('like', '%'.$_GET['q'].'%')));

                        for($i = 0; $i < count($dados); $i++){

                            if( $dados[$i]->pago == 1 ){
                                $saldoDevedor = '-';
                                $btnAcao = array('url' => 'relatorio.php?id=' . $dados[$i]->id, 'texto' => 'Ver relatório');
                                $qtdeDias = $dados[$i]->diffPago;
                                $urlJuros = '#';
                            } else {

                                $saldoDevedor = $e->valorComJurosAPagar($dados[$i]->taxaJuros, $dados[$i]->diff, $dados[$i]->valor, $dados[$i]->pagos);
                                $btnAcao = array('url' => 'pagar.php?id=' . $dados[$i]->id, 'texto' => 'Clique aqui');
                                $qtdeDias = $dados[$i]->diff;
                                $urlJuros = '/editar.php?id=' . $dados[$i]->id;
                            }

                            echo '<tr>
                                    <td align="right">
                                        ' . $dados[$i]->id . '
                                    </td>
                                    <td>
                                        ' . $dados[$i]->cliente . '
                                    </td>
                                    <td align="center">
                                        ' . $dados[$i]->email. '<br>
                                        ' . $dados[$i]->telefone . '<br>
                                        ' . $dados[$i]->celular . '
                                    </td>
                                    <td align="center">
                                        ' . Uses::formatarData($dados[$i]->dia, '-', '/') . '
                                    </td>
                                    <td align="right">
                                        ' . Uses::moeda($dados[$i]->valor) . '
                                    </td>
                                    <td align="center">
                                        <a class="btn btn-xs btn-success" href="' . $urlJuros . '">' . $dados[$i]->taxaJuros . '</a>
                                    </td>
                                    <td align="center">
                                        ' . $qtdeDias . '
                                    </td>
                                    <td align="right">
                                        ' . $saldoDevedor . '
                                    </td>
                                    <td>
                                        <a class="btn btn-xs btn-success" href="/' . $btnAcao['url'] . '">' . $btnAcao['texto'] . '</a>
                                    </td>
                                </tr>';
                        }

                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
            $estado = ( $_GET['pagos'] == 1 ) ? array('url' => '0', 'texto' => 'Voltar ao modo normal') :
                array('url' => '1', 'texto' => 'Ver empréstimos finalizados');

            echo '<a class="btn btn-warning" href="/?pagos=' . $estado['url'] . '">' . $estado['texto'] . '</a>';
            ?>
        </div>
    </div>

</body>
</html>