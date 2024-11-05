<?php

class Payment {
    private $pdo;

    // Informações do usuário e do pagamento
    private $user_id = NULL;
    public $payment_id = NULL;

    public function __construct($user_id = NULL) {
        $this->user_id = $user_id;
        $this->pdo = DB::getInstance(); // Supondo que DB::getInstance() retorna uma conexão PDO
    }

    // Método para obter informações de um pagamento específico
    public function get() {
        $query = $this->pdo->prepare("SELECT * FROM pagamentos WHERE id_pagamento = :id");
        $query->bindValue(':id', $this->payment_id);

        if ($query->execute()) {
            $row = $query->fetchAll(PDO::FETCH_OBJ);
            return count($row) > 0 ? $row[0] : false;
        } else {
            return false;
        }
    }

    // Método para adicionar um pagamento na tabela
    public function addPayment($id_pedido_venda, $valor_pago, $metodo_pagamento = NULL, $status_pagamento = 'pendente') {
        $data_pagamento = date('Y-m-d H:i:s'); // Define a data atual como data do pagamento

        $query = $this->pdo->prepare("
            INSERT INTO pagamentos (id_pedido_venda, data_pagamento, metodo_pagamento, valor_pago, status_pagamento) 
            VALUES (:id_pedido_venda, :data_pagamento, :metodo_pagamento, :valor_pago, :status_pagamento)
        ");
        $query->bindValue(':id_pedido_venda', $id_pedido_venda);
        $query->bindValue(':data_pagamento', $data_pagamento);
        $query->bindValue(':metodo_pagamento', $metodo_pagamento);
        $query->bindValue(':valor_pago', $valor_pago);
        $query->bindValue(':status_pagamento', $status_pagamento);

        if ($query->execute()) {
            return $this->pdo->lastInsertId(); // Retorna o ID do pagamento recém-criado
        } else {
            return false;
        }
    }

    // Método para atualizar o status de um pagamento específico
    public function setStatusPayment($status) {
        $query = $this->pdo->prepare("UPDATE pagamentos SET status_pagamento = :status WHERE id_pagamento = :id");
        $query->bindValue(':status', $status);
        $query->bindValue(':id', $this->payment_id);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

?>
