<?php
date_default_timezone_set('America/Sao_Paulo');

// Função para gerar o link de pagamento e calcular o valor total
function gerarLinkPagamento() {
    global $user_id, $conexao; // Usar a variável global do ID do usuário e a conexão com o banco
    
    // Definição dos itens do carrinho do usuário
    $items = [];
    $valor_pago = 0;

    // Consulta para pegar os itens do carrinho do usuário
    $sql = "SELECT p.id_produto, p.nome AS title, c.quantidade, p.preco AS unit_price 
            FROM carrinho c 
            JOIN produtos p ON c.id_produto = p.id_produto 
            WHERE c.id_usuario = ?";
    
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    while ($item = $resultado->fetch_assoc()) {
        $items[] = [
            "id" => $item["id_produto"],
            "title" => $item["title"],
            "quantity" => $item["quantidade"],
            "currency_id" => "BRL",
            "unit_price" => (float)$item["unit_price"]
        ];

        // Calculando o valor total
        $valor_pago += $item["quantidade"] * $item["unit_price"];
    }

    $stmt->close();

    // Dados do pagamento incluindo o valor total calculado
    $payment_data = [
        "items" => $items,
        "back_urls" => [
            "success" => "http://localhost:8000/pages/home.php",
            "failure" => "http://localhost:8000/pages/home.php",
            "pending" => "http://localhost:8000/pages/home.php"
        ],
        "auto_return" => "all"
    ];

    // Configuração da requisição cURL
    $access_token = "TEST-2596423661439279-110405-445e8f92f230457c373d9183f0c71475-327334435";
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.mercadopago.com/checkout/preferences",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $access_token",
            "Content-Type: application/json"
        ],
        CURLOPT_POSTFIELDS => json_encode($payment_data),
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "Erro: " . $err;
        return null;
    } else {
        $response_data = json_decode($response, true);
        $response_data['valor_pago'] = $valor_pago; // Adiciona o valor total ao array de resposta
        return $response_data;
    }
}

// Função para registrar o pagamento no banco de dados
function registrarPagamentoNoBanco($id_usuario, $nome_comprador, $email_comprador, $id_preference, $valor_pago) {
    $conn = new mysqli("localhost", "root", "", "primedb");

    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    // Prepara a consulta de inserção sem o campo 'data_pagamento'
    $stmt = $conn->prepare("INSERT INTO pagamentos (id_usuario, id_transacao, status_pagamento, valor_pago, metodo_pagamento, nome_comprador, email_comprador, id_preference) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    // Dados iniciais: status = pendente, método de pagamento não especificado
    $status_pagamento = "pendente";
    $metodo_pagamento = "não especificado"; // Inicialmente não especificado

    // Bind dos parâmetros com os valores necessários
    $stmt->bind_param("issdssss", $id_usuario, $id_preference, $status_pagamento, $valor_pago, $metodo_pagamento, $nome_comprador, $email_comprador, $id_preference);

    if ($stmt->execute()) {
        return $conn->insert_id; // Retorna o ID do pagamento inserido
    } else {
        echo "Erro ao registrar o pagamento: " . $stmt->error;
        return false;
    }

    $stmt->close();
    $conn->close();
}

// Código para pegar o ID, nome e email do usuário baseado no email da sessão
$user_id = null;
$nome_usuario = null;
$email_usuario = null;

if (isset($_SESSION['email'])) {
    include_once('../includes/config.php');
    $email = $_SESSION['email'];
    $sql = "SELECT id_usuario, nome, email FROM usuarios WHERE email = '$email'";
    $resultado = $conexao->query($sql);
    $usuario = $resultado->fetch_assoc();
    $user_id = $usuario['id_usuario'];  // Atribui o ID do usuário
    $nome_usuario = $usuario['nome'];  // Atribui o nome do usuário
    $email_usuario = $usuario['email'];  // Atribui o e-mail do usuário
}

// Funções para limpar o carrinho e atualizar o estoque

// Função para excluir os produtos do carrinho após a finalização do pedido
function limparCarrinho($id_usuario) {
    global $conexao; // Inclui o arquivo de configuração do banco de dados

    $sql = "DELETE FROM carrinho WHERE id_usuario = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id_usuario);

    if ($stmt->execute()) {
        echo "Carrinho limpo com sucesso.";
    } else {
        echo "Erro ao limpar o carrinho: " . $stmt->error;
    }

    $stmt->close();
}

// Função para atualizar o estoque conforme a quantidade finalizada no pedido
function atualizarEstoque($items) {
    global $conexao; // Inclui o arquivo de configuração do banco de dados

    foreach ($items as $item) {
        $sql = "UPDATE produtos SET quantidade_estoque = quantidade_estoque - ? WHERE id_produto = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ii", $item['quantity'], $item['id']);

        if (!$stmt->execute()) {
            echo "Erro ao atualizar estoque para o produto " . $item['id'] . ": " . $stmt->error;
        }

        $stmt->close();
    }
}

// Verifique se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($user_id) {
        $response_data = gerarLinkPagamento();
        if ($response_data) {
            $payment_id = registrarPagamentoNoBanco($user_id, $nome_usuario, $email_usuario, $response_data["id"], $response_data["valor_pago"]);
            if ($payment_id) {
                // Limpar o carrinho e atualizar o estoque antes de redirecionar
                limparCarrinho($user_id);
                atualizarEstoque($response_data['items']);

                // Redireciona para o link de pagamento
                $link_pagamento = $response_data["init_point"];
                header("Location: " . $link_pagamento);
                exit();
            }
        } else {
            echo "Erro ao gerar o link de pagamento.";
        }
    } else {
        echo "<script>
                alert('Usuário não está logado. Por favor, faça login para finalizar a compra.');
                window.location.href = 'login.php';
              </script>";
    }
}

// Função para atualizar os dados do pagamento no banco de dados após a resposta da API
function atualizarPagamento($status, $payment_type, $preference_id) {
    // Conexão com o banco de dados
    $conn = new mysqli("localhost", "root", "", "primedb");

    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    // Definindo a data de pagamento se o status for aprovado
    $data_pagamento = null;
    if ($status === 'approved') {
        $data_pagamento = date('Y-m-d H:i:s'); // Hora atual
    }

    // Preparando a query SQL para atualizar os dados de pagamento com o preference_id correspondente
    $stmt = $conn->prepare("UPDATE pagamentos 
                            SET status_pagamento = ?, metodo_pagamento = ?, data_pagamento = ? 
                            WHERE id_preference = ?");

    // Bind dos parâmetros
    $stmt->bind_param("ssss", $status, $payment_type, $data_pagamento, $preference_id);

    if ($stmt->execute()) {
        echo "<script>
            window.onload = function() {
                const sucessMsgPayment = document.getElementById('sucessMsgPayment');
                sucessMsgPayment.style.display = 'flex';
                
                // Esconde a mensagem após 5 segundos
                setTimeout(() => {
                    sucessMsgPayment.style.display = 'none';
                }, 5000);

                // Redireciona para a página após 5 segundos
                setTimeout(() => {
                    window.location.href = 'http://localhost:8000/pages/home.php';
                }, 5000);
            };
          </script>";
    } else {
        echo "Erro ao atualizar o pagamento: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

// Verifique se os parâmetros da URL estão presentes
if (isset($_GET['collection_status'], $_GET['payment_type'], $_GET['preference_id'])) {
    // Pegando os parâmetros da URL
    $status = $_GET['collection_status']; // Status do pagamento
    $payment_type = $_GET['payment_type']; // Tipo de pagamento
    $preference_id = $_GET['preference_id']; // ID da preferência

    // Atualizar o pagamento no banco de dados com os dados recebidos
    atualizarPagamento($status, $payment_type, $preference_id);
}


