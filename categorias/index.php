<?php

    //session_start();

    require("../database/conexao.php");

    $sql = "SELECT * FROM tbl_categoria";

    $resultado = mysqli_query($conexao,$sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles-global.css" />
    <link rel="stylesheet" href="./categorias.css" />
    <title>Administrar Categorias</title>
</head>

<body>
    <?php
    include("../componentes/header/header.php");
    ?>
    <div class="content">
        <section class="categorias-container">
            <main>
                <form class="form-categoria" method="POST" action="./actionsCategoria.php">
                <input type="hidden" name="acao" value="inserir">
                    <h1 class="span2">Adicionar Categorias</h1>
                    <ul>
                    <?php
                        if(isset($_SESSION["erros"])){
                            foreach($_SESSION["erros"] as $erro){
                        ?>
                            <li><?=$erro?></li>
                        <?php
                        }
                        unset($_SESSION["erros"]);
                        }  
                        ?>
                    </ul>
                    <div class="input-group span2">
                        <label for="descricao">Descrição</label>
                        <input type="text" name="descricao" id="descricao" required/>
                    </div>
                    <button type="button" onclick="javascript:window.location.href='../produtos'">Cancelar</button>
                    <button>Salvar</button>
                </form>
                <h1>Lista de Categorias</h1>
                <?php
                if(mysqli_num_rows($resultado)== 0){
                    echo "<p style='text-align:center'>Nenhuma categoria cadastrada.</p>";
                }
                     while($descricao = mysqli_fetch_array($resultado)){
                ?>
                <div class="card-categorias">
                        <?=$descricao["descricao"]?>
                        <form method="POST" action="./actionsCategoria.php">
                        <input  type="hidden" name="acao" value="deletar"/>
                        <input  type="hidden" name="id" value="<?=$descricao["id"]?>"/>
                        <button>&#128465;</button>
                </div>
                <?php
                    }
                ?>
            </main>
        </section>
    </div>
</body>

</html>
