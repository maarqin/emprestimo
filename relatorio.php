<!doctype html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Marcos Thomaz">
    <title>Relatório do empréstimo</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <style media="print">
        table, .list-group { font-size:12px }
        #imprimir { display: none; }
    </style>

</head>
<body>

<h4 class="text-center">Relatório de empréstimo</h4><br/>
<?php

include 'src/autoinclude.php';

$e = new Emprestimo();

$dados = $e->getById($_GET['id']);

?>


<div class="list-group">
    <div class="col-xs-2"><strong>ID:</strong> <?= $dados->id ?></div>
    <div class="col-xs-6"><strong>Cliente:</strong> <?= $dados->cliente ?></div>
    <div class="col-xs-4"><strong>Email:</strong> <?= $dados->email ?></div>
</div>
<br/>

<div class="list-group">
    <div class="col-xs-3"><strong>Realizado em:</strong> <?= Uses::formatarData($dados->dia, '-', '/') ?></div>
    <div class="col-xs-3"><strong>Pago em:</strong> <?= Uses::formatarData($dados->diaPago, '-', '/') ?></div>
    <div class="col-xs-3"><strong>Celular:</strong> <?= $dados->celular ?></div>
    <div class="col-xs-3"><strong>Telefone:</strong> <?= $dados->telefone ?></div>
</div>
<br/>

<div class="list-group">
    <div class="col-xs-3"><strong>Valor inicial (R$):</strong> <?= Uses::moeda($dados->valor) ?></div>
    <div class="col-xs-3"><strong>Juros (%):</strong> <?= $dados->taxaJuros ?></div>
    <div class="col-xs-3"><strong>Qtde. dias utilizados:</strong> <?= $dados->diffPago ?></div>
    <div class="col-xs-3"><strong>Valor final (R$):</strong> <?= Uses::moeda($e->sumAllValoresPagos($dados->id)) ?></div>
</div>
<br/><br/>
<div class="row">

    <div class="col-lg-12">
        
        <div class="widget-container fluid-height clearfix">

            <div class="widget-content padded clearfix">
                <table class="table table-bordered">

                    <thead>
                    <tr>
                        <th>
                            Dia
                        </th>
                        <th>
                            Valor (R$)
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $ep = new EmprestimoPagamento();

                    $dados = $ep->getAllValoresPagos($_GET['id']);

                    for($i = 0; $i < count($dados); $i++){
                        echo '<tr>
                    <td>' . Uses::formatarData($dados[$i]->data, '-', '/') . '</td>
                    <td>' . Uses::moeda($dados[$i]->valor) . '</td>
                </tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<a class="btn btn-default" id="imprimir" onclick="window.print()">Imprimir</a>

</body>
</html>