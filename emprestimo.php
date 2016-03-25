<!doctype html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Marcos Thomaz">
    <title>Criar novo empréstimo</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="container">
    <h1>Novo empréstimo</h1>

    <form action="/modulos/storeEmprestimo.php" method="post" class="form-horizontal" autocomplete="off">
        <div class="form-group">
            <div class="col-md-5">
                <label>Cliente</label>
                <input type="text" class="form-control" name="cliente" required="required">
            </div>
            <div class="col-md-4">
                <label>Email</label>
                <input type="email" class="form-control" name="email">
            </div>
            <div class="col-md-3">
                <label>Data do empréstimo</label>
                <input type="text" class="form-control data" name="data" required="required">
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-3">
                <label>Celular</label>
                <input type="text" class="form-control telefone" name="celular" required="required">
            </div>

            <div class="col-md-3">
                <label>Telefone</label>
                <input type="text" class="form-control telefone" name="telefone">
            </div>

            <div class="col-md-3">
                <label>Valor do empréstimo</label>
                <input type="text" class="form-control monetario" name="valor" required="required">
            </div>
            <div class="col-md-3">
                <label>Taxa de juros(a.m.)</label>
                <input type="text" class="form-control percentual" name="taxaJuros" required="required">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9" style="text-align: right">
                <a class="btn btn-primary" href="/">Cancelar</a>
                <button type="submit" class="btn btn-default">Salvar</button>
            </div>
        </div>

    </form>
</div>
<script src="/js/jquery-2.1.3.min.js"></script>
<script src="/js/jquery.maskeinput.min.js"></script>
<script src="/js/jquery.price_format.min.js"></script>
<script type="text/javascript">
    $('.telefone').mask('(99) 9999-9999?9');
    $('.data').mask('99/99/9999');
    $('.monetario').priceFormat({
        prefix: '',
        centsSeparator: ',',
        thousandsSeparator: '.'
    });
    $('.percentual').priceFormat({
        prefix: '',
        centsSeparator: '.',
        thousandsSeparator: ''
    });
</script>

</body>
</html>
