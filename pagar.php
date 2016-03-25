<!doctype html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Marcos Thomaz">
    <title>Pagar empréstimo</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <style media="print">
        .container, #imprimir { display: none; }
    </style>
</head>
<body>

<div class="container">
    <h1>Pagar empréstimo</h1>

    <?php

    // Informacao ao usuario
    if( isset($_GET['error']) ){
        $msg = ( $_GET['error']  == 'success' ) ? 'Feito com sucesso' : 'Oops! Sua ação não pôde ser finalizada, verifique o valor';
        echo '<div class="alert alert-' . $_GET['error'] . '"> ' . $msg . '</div>';
    }

    ?>

    <form action="/modulos/storeEmprestimoPagamento.php" method="post" class="form-horizontal" autocomplete="off">
        <input type="hidden" name="emprestimo_id" value="<?= $_GET['id'] ?>">
        <div class="form-group">
            <label class="control-label col-sm-3">Valor</label>
            <div class="col-sm-6">
                <input type="text" class="form-control monetario" name="valor" required="required" style="text-align: right">
            </div>
        </div>
        <div class="form-group">

            <label class="control-label col-sm-3" for="email">Data</label>
            <div class="col-sm-4">
                <input type="text" class="form-control data" name="data" required="required">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-6" style="text-align: right">
                <a class="btn btn-primary" href="/">Voltar</a>
                <button type="submit" class="btn btn-default">Salvar</button>
            </div>
        </div>
    </form>
</div>

<h4>Valores ref. ao empréstimo</h4>
    <div class="row">
        <div class="col-lg-12">
            <div class="widget-container fluid-height clearfix">
                <div class="widget-content padded clearfix">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>
                                Descrição
                            </th>
                            <th>
                                Valor (R$)
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        include 'src/autoinclude.php';

                        $ep = new EmprestimoPagamento();

                        $id = $_GET['id'];
                        $dados = $ep->getAllValoresPagos($id);

                        for($i = 0; $i < count($dados); $i++){
                            echo '<tr>
                                    <td>' . Uses::formatarData($dados[$i]->data, '-', '/') . '</td>
                                    <td>' . Uses::moeda($dados[$i]->valor) . '-</td>
                                </tr>';
                        }

                        $e = new Emprestimo();
                        $dados = current($e->all(array('id' => array('=', $id))));

                        echo '<tr>
                                    <td>Saldo devedor</td>
                                    <td>' . $e->valorComJurosAPagar($dados->taxaJuros, $dados->diff, $dados->valor, $dados->pagos) . '</td>
                                </tr>';

                        ?>
                        </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
    <a class="btn btn-default" id="imprimir" onclick="window.print()">Imprimir</a>




<script src="/js/jquery-2.1.3.min.js"></script>
<script src="/js/jquery.maskeinput.min.js"></script>
<script src="/js/jquery.price_format.min.js"></script>
<script type="text/javascript">
    $('.monetario').priceFormat({
        prefix: '',
        centsSeparator: ',',
        thousandsSeparator: '.'
    });
    $('.data').mask('99/99/9999');
</script>

</body>
</html>
