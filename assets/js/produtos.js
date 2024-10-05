// Função para validar os campos do produto
function validarCampos() {
    const nomeProduto = document.getElementById('nomeProduto').value;
    const descricao = document.getElementById('descricao').value;
    const preco = document.getElementById('preco').value;

    if (nomeProduto === '' || descricao === '' || preco === '') {
        alert('Por favor, preencha todos os campos.');
        return false;
    }

    return true;
}

// Função chamada ao clicar em um botão para salvar o produto
function salvarProduto() {
    if (validarCampos()) {
        // Aqui você pode adicionar a lógica para salvar o produto
        console.log("Produto salvo com sucesso!");
    } else {
        // Não faz nada se os campos não estiverem preenchidos
        console.log("Não foi possível salvar o produto devido a campos vazios.");
    }
}