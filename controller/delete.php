<?php

    if(!empty($_GET['id_produto']))
    {
        include_once('../includes/config.php');

        $id = $_GET['id_produto'];

        $sqlSelect = "SELECT *  FROM produtos WHERE id_produto=$id";

        $result = $conexao->query($sqlSelect);

        if($result->num_rows > 0)
        {
            $sqlDelete = "DELETE FROM produtos WHERE id_produto=$id";
            $resultDelete = $conexao->query($sqlDelete);
        }
    }
    header('Location: ../pages/addProdutos.php');