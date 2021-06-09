<?php
session_start();
?>
<link rel="stylesheet" href="/componentes/header/header.css">
<?php
if (isset($_SESSION["mensagem"])) {
?>
    <div class="mensagens">
        <?= $_SESSION["mensagem"]; ?>
    </div>
    <script lang="javasrcipt">
        setTimeout(() => {
            document.querySelector(".mensagens").style.display = "none";
        }, 4000);
    </script>
<?php
    unset($_SESSION["mensagem"]);
}
?>
<header class="header">
    <figure>
        <!-- <a href="/web-backend/icatalogo-parte1/produtos"> -->
        <img src="/imgs/logo.png">
        <!-- </a> -->
    </figure>

    <form method="GET" action="/produtos/index.php">
        <input id="pesquisar" type="text" name="p" value="<?= isset($_GET["p"]) ? $_GET["p"] : ""?>" placeholder="Pesquisar" />
        <button <?= isset($_GET["p"]) && $_GET["p"] != "" ? "onclick='limparFiltro()'" : ""?>>
        <?php
        if(isset($_GET["p"]) && $_GET["p"] != ""){
        ?>
            <img  style="width: 15px" src="/imgs/close.svg">
        <?php
        }else{
        ?>
        <img src="/imgs/lupa-de-pesquisa.svg">
        <?php
            }
        ?>
        </button>
    </form>
    <nav>
        <?php

        if (!isset($_SESSION["usuarioId"])) {
        ?>
            <ul>
                <a id="menu-admin"> Administrar </a>
            </ul>

    </nav>
    <div id="container-login" class="container-login">

        <h1>fazer login</h1>
        <form method="POST" action="/componentes/header/acoes.php">
            <input type="hidden" name="acao" value="login">
            <input type="text" name="usuario" placeholder="Usuário">
            <input type="password" name="senha" placeholder="Senha">
            <button>Entrar</button>
        </form>
    </div>
<?php
        } else {
?>
    <nav>
        <ul>
            <a id="menu-admin" onclick="logout()"> Sair </a>
        </ul>
    </nav>
    <form id="form-logout" style="display: none;" method="POST" action="/componentes/header/acoes.php">
        <input type="hidden" name="acao" value="logout">
    </form>
    <!--  Tinha faltado fechar esse form... dessa forma todos os buttons pertenciam a esse form e enviavam para logout -->
<?php
        }
?>
</header>
<script lang="javascript">
    document.querySelector("#menu-admin").addEventListener("click", toggleLogin);

    function logout() {
        document.querySelector('#form-logout').submit();
    }

    function toggleLogin() {
        let containerLogin = document.querySelector("#container-login");
        let h1Form = document.querySelector("#container-login > h1");
        let form = document.querySelector("#container-login > form");
        //se estiver oculto, mostra 
        if (containerLogin.style.opacity == 0) {
            h1Form.style.display = "block";
            form.style.display = "flex";
            containerLogin.style.opacity = 1;
            containerLogin.style.height = "200px";
            //se não, oculta
        } else {
            h1Form.style.display = "none";
            form.style.display = "none";
            containerLogin.style.opacity = 0;
            containerLogin.style.height = "0px";
        }
    }

    function limparFiltro() {
        document.querySelector('#pesquisar').value = "";
    }
</script>