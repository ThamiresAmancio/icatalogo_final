<?php
require("../database/conexao.php");

$sql = " SELECT p.*, c.descricao as categoria FROM tbl_produto p
        INNER JOIN tbl_categoria c ON p.categoria_id = c.id ";

if (isset($_GET["p"]) && $_GET["p"] != "") {
  $p = $_GET["p"];
  $sql .= " WHERE p.descricao LIKE '%$p%' OR c.descricao LIKE '%$p%' ";
}

$sql .= " ORDER BY p.id DESC ";

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
  include("../componentes/header/header.php");
  ?>
  <div class="content">
    <section class="produtos-container">
      <?php
      //autorização

      //se o usuário estiver logado, mostrar os botões
      if (isset($_SESSION["usuarioId"])) {
      ?>
        <header>
          <button onclick="javascript:window.location.href ='./novo/'">Novo Produto</button>
          <button onclick="javascript:window.location.href ='../categorias/'">Adicionar Categoria</button>
        </header>
      <?php
      }
      ?>
      <main>
        <?php
        while ($produto = mysqli_fetch_array($resultado)) {
          //recuperou as variáveis do produto
          $valor = $produto["valor"];
          $desconto = $produto["desconto"];

          $valorDesconto = 0;

          //se houver desconto, tranforma a porcentagem em valor
          if ($desconto > 0) {
            $valorDesconto = ($desconto / 100) * $valor;
          }

          //verificou quantidade de parcelas referente ao valor
          $qtdeParcelas = $valor > 1000 ? 12 : 6;
          $valorComDesconto = $valor - $valorDesconto;
          $valorParcela = $valorComDesconto / $qtdeParcelas;

        ?>
          <article class="card-produto">
            <?php
            if (isset($_SESSION["usuarioId"])) {
            ?>
              <div class="acoes-produtos">
                <img onclick="deletar(<?= $produto['id'] ?>)" src="../imgs/trash.svg" />
              </div>
            <?php
            }
            ?>
            <figure>
              <img src="fotos/<?= $produto["imagem"] ?>" />
            </figure>
            <section>
              <span class="preco">
                R$ <?= number_format($valorComDesconto, 2, ",", ".") ?>
                <em><?= $desconto ?>% off</em>
              </span>
              <span class="parcelamento">ou em
                <em>
                  <?= $qtdeParcelas ?>x R$<?= number_format($valorParcela, 2, ",", ".") ?> sem juros
                </em>
              </span>

              <span class="descricao"><?= $produto["descricao"] ?></span>
              <span class="categoria">
                <em><?= $produto["categoria"] ?></em>
              </span>
            </section>
            <footer>

            </footer>
          </article>
        <?php
        }
        ?>
        <form id="formDeletar" method="POST" action="./acoes.php">
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
    function deletar(produtoId) {
      if (confirm("Tem certeza que deseja deletar este produto?")) {
        document.querySelector("#produtoId").value = produtoId;
        document.querySelector("#formDeletar").submit();
      }
    }
  </script>
</body>

</html>