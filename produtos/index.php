<?php
require("../database/conexao.php");

$pesquisa = isset($_GET["p"]) ? $_GET["p"] : null;


// echo $pesquisar;
// $pesquisar = $_GET["pesquisar"];

if($pesquisa){
    $sql = "SELECT p. *, c.descricao as categoria FROM tbl_produto p
    INNER JOIN tbl_categoria c ON p.categoria_id = c.id WHERE p.descricao LIKE '%$pesquisa%' 
    OR c.descricao LIKE '%$pesquisa%'";


    }else{
    $sql = "SELECT p. *, c.descricao as categoria FROM tbl_produto p 
    INNER JOIN tbl_categoria c ON p.categoria_id = c.id ORDER BY p.id DESC";

}
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
                             
                            $valor = $produto["valor"];
                            $desconto = $produto["desconto"];

                            if($desconto > 0){
                                $valorDesconto = ($desconto/100) * $valor;
                            }

                            $quantidadeParcelas = $produto["valor"] > 1000 ? 15 : 6;
                            $valorComDesconto = $valor - $valorDesconto;
                            $valorParcela = $valorComDesconto / $quantidadeParcelas;
                            


    
                    ?>
                <article class="card-produto">
                     <figure>
                        <img src="produto/<?=$produto["imagem"]?>">
                    </figure>
                    <section>
                        <form method="POST" action="./productsActions.php">
                            <input type="hidden" value="deletar" name="acao"/>

                            <input  type="hidden" name="id" value="<?=$produto["id"]?>"/>

                            <span class="preco" > R$<?=number_format ($valorComDesconto,2,",",".")?><em><?=$desconto?>%</em></span>
                            <br></br>
                            <span class="parcelamento">ou em <em><?= $quantidadeParcelas?> x R$ <?= number_format($valorParcela,2,",",".")?> sem juros</em></span>
                            <br></br>
                            <span class="descricao"><?=$produto["descricao"]?></span>
                            <br></br>

                            <span class="categoria"><em><?=$produto["categoria"]?></span></em>
                            <br></br>
                            <?php
                                if(isset($_SESSION["usuarioId"])){
                            ?>
                            <button>&#128465;</button>
                            <?php
                                }
                            ?>
                        </form>
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