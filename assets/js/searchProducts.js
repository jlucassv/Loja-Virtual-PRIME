const searchInput = document.getElementById('searchInput');
const suggestionsDiv = document.getElementById('suggestions');

// Função para mostrar as sugestões
searchInput.addEventListener('input', function() {
    suggestionsDiv.style.display = 'block'; // Mostra as sugestões
});

// Adiciona um listener para capturar o evento de input
searchInput.addEventListener('input', () => {
    const query = searchInput.value;
    if (query.length > 0) {
        fetchSuggestions(query);
    } else {
        suggestionsDiv.innerHTML = ''; // Limpa as sugestões se o input estiver vazio
    }
});

// Função para buscar sugestões de produtos
function fetchSuggestions(query) {
    fetch(`../../controller/searchProduct.php?query=${encodeURIComponent(query)}`)
    .then(response => response.json())
    .then(data => {
        suggestionsDiv.innerHTML = ''; // Limpa as sugestões anteriores
        data.forEach(product => {
            const suggestionItem = document.createElement('div');
            suggestionItem.classList.add('suggestion-item');
            suggestionItem.textContent = product.nome; // Exibe o nome do produto

            // Adiciona um evento de clique para selecionar o produto
            suggestionItem.addEventListener('click', () => {
                addToCart(product); // Adiciona o produto ao carrinho
                searchInput.value = ''; // Limpa o campo de pesquisa
                suggestionsDiv.innerHTML = ''; // Limpa as sugestões
            });

            suggestionsDiv.appendChild(suggestionItem); // Adiciona a sugestão à lista
        });
    })
    .catch(error => console.error('Erro ao buscar sugestões:', error));
}

searchInput.addEventListener('keydown', (event) => {
    if (event.key === 'Enter') { // Verifica se a tecla pressionada é 'Enter'
        event.preventDefault(); // Previne o comportamento padrão
        const query = searchInput.value;
        if (query.length > 0) {
            // Redireciona para a página de resultados, passando a query na URL
            window.location.href = `../../pages/resultadoProdutos.php?query=${encodeURIComponent(query)}`;
        }
    }
});