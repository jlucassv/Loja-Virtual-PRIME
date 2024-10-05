<?php
    // isset -> serve para saber se uma variável está definida
    include_once('../includes/config.php');
    
    if(isset($_POST['save']))
    {       
            $id = $_POST['id'];
            $nome = $_POST['nomeProduto'];
            $descricao = $_POST['descricao'];
            $preco = $_POST['preco'];
            $estoque = $_POST['estoque'];
            
            if(isset($_FILES['imagemProduto'])){
                $arquivo = $_FILES['imagemProduto'];
                $pasta = "../assets/arquivos/";
                $nomeDoArquivo = $arquivo['name'];
                $extensao = strtolower(pathinfo($nomeDoArquivo,  PATHINFO_EXTENSION));
                $path = $pasta . $nomeDoArquivo;
                move_uploaded_file($arquivo['tmp_name'], $path);
            }
        
            $sqlInsert = "UPDATE produtos 
            SET nome='$nome', descricao='$descricao', preco='$preco', quantidade_estoque='$estoque', imagem='$path' 
            WHERE id_produto=$id";
        $result = $conexao->query($sqlInsert);
        print_r($result);
    }
    header('Location: ../pages/addProdutos.php');