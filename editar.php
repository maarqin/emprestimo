<!doctype html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Marcos Thomaz">
    <title>Editar juros do empréstimo</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="container">
    <h1>Editar juros do empréstimo</h1>

    <?php

    include 'src/autoinclude.php';

    $e = new Emprestimo();

    $dados = $e->getJurosById($_GET['id']);

    ?>

    <form action="/modulos/updateJuros.php" method="post" class="form-horizontal" autocomplete="off">
        <input type="hidden" name="emprestimo_id" value="<?= $dados->id ?>">
        <div class="form-group">
            <label class="control-label col-sm-3">Juros</label>
            <div class="col-sm-6">
                <input type="text" class="form-control percentual" name="taxaJuros" value="<?= $dados->juros ?>" required="required" style="text-align: right">
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
<script src="/js/jquery-2.1.3.min.js"></script>
<script src="/js/jquery.maskeinput.min.js"></script>
<script src="/js/jquery.price_format.min.js"></script>
<script type="text/javascript">
    $('.percentual').priceFormat({
        prefix: '',
        centsSeparator: '.',
        thousandsSeparator: ''
    });
    $('.data').mask('99/99/9999');
</script>

</body>
</html>
