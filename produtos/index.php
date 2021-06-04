<?php
require("../database/conexao.php");

$sql = " SELECT p.*, c.descricao as categoria FROM tbl_produto p
         INNER JOIN tbl_categoria c ON p.categoria_id = c.id
         ORDER BY p.id DESC ";

if(isset($_GET["p"]) && $_GET["p"] != ""){
    $p = $_GET["p"];
    $sql .= "WHERE p.descricao LIKE '%$p%' OR c.descricao LIKE '%$p%'";
}

$resultado = mysqli_query($conexao, $sql) or die(mysqli_error($conexao));

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
                while ($produto = mysqli_fetch_array($resultado)) {
                    $valor = $produto["valor"];
                    $desconto = $produto["desconto"];

                    $valorDesconto = 0;

                    if ($desconto > 0) {
                        $valorDesconto = ($desconto / 100) * $valor;
                    }

                    $qntdParcelas = $valor > 1000 ? 12 : 6;
                    $valorComDesconto = $valor - $valorDesconto;
                    $valorParcela = $valorComDesconto / $qntdParcelas;


                ?>
                <article class="card-produto">
                <?php
                    if(isset($_SESSION["usuarioId"])){
                        
                        ?>
                        <div class="acoes-produtos">
                            <img onclick="deletar(<?= $produto['id'] ?>)" src="../imgs/delete.svg" />
                            <img onclick="javascript: window.location = './editar/?id=<?= $produto['id'] ?>'" src="../imgs/pencil.svg" />
                        </div>
                        <?php
                        }
                        ?>
                     <figure>
                        <img src="produto/<?=$produto["imagem"]?>">
                    </figure>
                    <section>
                            <span class="preco" > R$<?=number_format ($valorComDesconto,2,",",".")?><em><?=$desconto?>%</em></span>
                            
                            <span class="parcelamento">ou em <em><?= $qntdParcelas?> x R$ <?= number_format($valorParcela,2,",",".")?> sem juros</em></span>
                            
                            <span class="descricao"><?=$produto["descricao"]?></span>
                            
                            <span class="categoria"><em><?=$produto["categoria"]?></span></em>
                            
                       </form>
                    </section> 
                    <footer>

                    </footer>
                </article>
                <?php
                }
                ?>
                <form id="formDeletar" method="POST" action="./productsActions.php">
                    <input type="hidden" name="acao" value="deletar" />
                    <input type="hidden" name="produtoId" id="produtoId" />
                </form>
            </main>

        </section>
    </div>
    <footer>
        SENAI 2021 - Todos os direitos reservados
    </footer>
    <script lang="javascript">
        function deletar(produtoId){
            if(confirm("Tem certeza que deseja deletar este produto?")){
                document.querySelector("#produtoId").value = produtoId;
                document.querySelector("#formDeletar").submit();
            }
        }
    </script>
</body>
</html>