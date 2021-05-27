<?php
require("../database/conexao.php");

$sql = "SELECT p. *, c.descricao as categoria FROM tbl_produto p 
INNER JOIN tbl_categoria c ON p.categoria_id = c.id ORDER BY p.id DESC";

$resultado = mysqli_query($conexao,$sql) or die(mysqli_error($conexao));

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles-global.css" />
    <link rel="stylesheet" href="./produtos.css" />
    <title>Administrar Produtos</title>
</head>

<body>
    <?php
        require("../componentes/header/header.php");
    ?>
    <div class="content">
        <div style="position: absolute; top:0; right:0;">
            <?php
                if(isset($_SESSION["erros"])){
                    echo $_SESSION["erros"][0];
                }
                if(isset($_SESSION["mensagem"])){
                    echo $_SESSION["mensagem"];
                }

                unset($_SESSION["erros"]);
                unset($_SESSION["mensagem"]);
            ?>
        </div>
        <section class="produtos-container">
        <!--se o usuairo estiver logado, mostrar os botoes-->
            <?php
                if(isset($_SESSION["usuarioId"])){
            ?>
            <header>
                <button type="button" onclick="javascript:window.location.href ='./novo/'">Novo Produto</button>
                <button type="button" onclick="javascript:window.location.href ='../categorias/'">Adicionar Categoria</button>
            </header>
            <?php
            }
            ?>
            <main>
                    <?php
                        while($produto = mysqli_fetch_array($resultado)){
                            $quantidadeParcelas = $produto["valor"] > 1000 ? 15 : 6;
                            $valorParcela = $produto["desconto"] / $quantidadeParcelas;
                            $descontoValor = $produto["valor"] - $produto["desconto"];
                    ?>
                <article class="card-produto">
                     <figure>
                        <img src="produto/<?=$produto["imagem"]?>">
                    </figure>
                    <section>
                        <span class="preco" valu> R$<?=number_format ($produto["valor"],2,",",".")?></span>
                        <span class="parcelamento">ou em <em><?= $quantidadeParcelas?> x R$ <?= number_format($valorParcela,2,",",".")?> sem juros</em></span>
                        <span class="desconto"> Com Desconto:<?=$descontoValor?><style></style></span>
                        <span class="descricao"><?=$produto["descricao"]?></span>
                        <span class="categoria"><em><?=$produto["categoria"]?></span></em>
                    </section> 
                    <footer>

                    </footer>
                </article>
                <?php
                    }
                ?>
            </main>

        </section>
    </div>
    <footer>
        SENAI 2021 - Todos os direitos reservados
    </footer>
</body>

</html>