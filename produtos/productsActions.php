

<?php

//inicializa a sessão no php
//todo arquivo que utilizar sessão, precisa chamar a session_start()
session_start();

//validação vou fazer daqui a pouco
function validarCampos()
{
    //declara um vetor de erros
    $erros = [];

    //validar se campo descricao está preenchido
    if (!isset($_POST["descricao"]) && $_POST["descricao"] == "") {
        $erros[] = "O campo descrição é obrigatório";
    }

    //validar se o campo peso está preenchido
    if (!isset($_POST["peso"]) && $_POST["peso"] == "") {
        $erros[] = "O campo peso é obrigatório";
        //validar se o campo peso é um número
    } elseif (!is_numeric(str_replace(",", ".", $_POST["peso"]))) {
        $erros[] = "O campo peso deve ser um número";
    }

    //validar se o campo quantidade está preenchido
    if (!isset($_POST["quantidade"]) && $_POST["quantidade"] == "") {
        $erros[] = "O campo quantidade é obrigatório";
        //validar se o campo quantidade é um número
    } elseif (!is_numeric(str_replace(",", ".", $_POST["quantidade"]))) {
        $erros[] = "O campo quantidade deve ser um número";
    }

    if (!isset($_POST["cor"]) && $_POST["cor"] == "") {
        $erros[] = "O campo cor é obrigatório";
    }

    if (!isset($_POST["valor"]) && $_POST["valor"] == "") {
        $erros[] = "O campo valor é obrigatório";
    } elseif (!is_numeric(str_replace(",", ".", $_POST["valor"]))) {
        $erros[] = "O campo valor deve ser um número";
    }

    //se o campo desconto veio preenchido, testa se ele é numérico
    if (isset($_POST["desconto"]) && $_POST["desconto"] != "" && !is_numeric(str_replace(",", ".", $_POST["desconto"]))) {
        $erros[] = "O campo desconto deve ser um número";
    }

    

    //verificar se o campo foto está vindo e se ele é uma imagem
    if ($_FILES["foto"]["error"] == UPLOAD_ERR_NO_FILE) {
        $erros[] = "Você precisa enviar uma imagem";

    } else {
        //se o arquivo é uma imagem
        $imagemInfo =  getimagesize($_FILES["foto"]["tmp_name"]);
    }if(!$imagemInfo){
        $erros[] = "Este arquivo não é uma imagem.";
    }
    if($_FILES["foto"]["size"] > 1024*1024*2){
        $erros[] = "O arquivo não pode ser maior que 2 MB";
    }

    $width = $imagemInfo[0];
    $height = $imagemInfo[1];
    if($width != $height){
        $erros [] = "A imagem precisa ser quadrada.";
    }

    if(!isset($_POST["categoria"]) && $_POST["categoria"] == ""){
        $erros [] = "O campo Categoria é obrigatório!";
    }
    return $erros;

    //!== Não é idêntico
    // != É diferente
    // == Igualdade(teste o valor)
    // === testa o valor é a tipagem
}


require("../database/conexao.php");

switch ($_POST["acao"]) {

    case "inserir":
        //chamamos a função de validação para verificicar se tem erros
        $erros = validarCampos();

        //se houver erros
        if (count($erros) > 0) {

            //incluímos um campo erros na sessão e atribuímos o vetor de erros a ele
            $_SESSION["erros"] = $erros;

            //redireciona para a págino do formulário
            header("location: novo/index.php");
        }

        $nomeArquivo = $_FILES["foto"]["name"];
        //extraímos do nome original a extensão        
        $extensao = pathinfo($nomeArquivo, PATHINFO_EXTENSION);
        //geramos um novo nome único utilizando o unix timestamp       
         $novoNomeArquivo = md5(microtime()) . ".$extensao";
        //movemos a foto para a pasta fotos dentro de produtos        
        move_uploaded_file($_FILES["foto"]["tmp_name"], "produto/$novoNomeArquivo");
        

        $descricao = $_POST["descricao"];
        $peso = str_replace(",", ".", $_POST["peso"]);
        $quantidade = $_POST["quantidade"];
        $cor = $_POST["cor"];
        $tamanho = $_POST["tamanho"];
        $valor = str_replace(",", ".", $_POST["valor"]);
        $desconto = $_POST["desconto"] != "" ? $_POST["desconto"] : 0;
        $categoriaId = $_POST["categoria"];


        //declaramos o sql de insert no banco de dados        
        $sqlInsert = " INSERT INTO tbl_produto (descricao, peso, quantidade, cor, tamanho, valor, desconto, imagem,categoria_id)        
        VALUES ('$descricao', $peso, $quantidade, '$cor', '$tamanho', $valor, $desconto, '$novoNomeArquivo',$categoriaId)";
        //executamos o sql        
        $resultado = mysqli_query($conexao, $sqlInsert) or die(mysqli_error($conexao));
        //verificamos se deu certo ou não       
        if ($resultado) {
            $mensagem = "Produto inserido com sucesso!";
        } else {
            $mensagem = "Erro ao inserir o produto!";
        }

        $_SESSION["mensagem"] = $mensagem;
        //redirecionamos para a página de listagem       
         header("location: index.php");
        break;



        case "deletar":

            $id = $_POST["id"];

            $sqlDelete = "DELETE FROM tbl_produto WHERE id = $id";

            $resultado = mysqli_query($conexao,$sqlDelete);

            if($resultado){
                $_SESSION["mensagem"] = "Categoria deletado com sucesso";
            }else{
                $_SESSION["mensagem"] = "Categoria não deletada";

            }

            header("location:index.php");
    
            break;
}

?>