// Exibir produtos na página
fetch('../../controller/get_products.php')
.then(response => response.json())
.then(data => {
    const productsDiv = document.getElementById('products');
    const cartItems = []; // Array para armazenar os itens do carrinho

    data.forEach(product => {
        // Cria um card para cada produto
        const card = document.createElement('div');
        card.classList.add('card');
        
        //imagem do produto
        const productImage = document.createElement('img');
        productImage.src = product.imagem; //URL da imagem
        productImage.alt = product.imagem;
        card.appendChild(productImage);  
        
        // nome do produto
        const productName = document.createElement('h2');
        productName.textContent = product.nome;
        card.appendChild(productName);
        
        //preço do produto
        const productPrice = document.createElement('p');
        productPrice.textContent = `R$ ${product.preco}`;
        card.appendChild(productPrice);
        
        // Botão "Comprar"
        const buyButton = document.createElement('button');
        buyButton.classList.add('btn', 'btn-primary');
        buyButton.textContent = "Comprar";
        
        // Adiciona evento de clique ao botão "Comprar"
        buyButton.addEventListener('click', () => {    
            addToCart(product); // Chama a função para adicionar ao carrinho
            // Obtém a referência da div de mensagem de sucesso
            const successMsgProduct = document.getElementById('sucessMsgProduct');
            // Define o estilo para display: block
            successMsgProduct.style.display = 'flex';
            setTimeout(() => {
                successMsgProduct.style.display = 'none';
        }, 5000);
    });
        
    card.appendChild(buyButton);  
        
    // Adiciona o card à lista de produtos
    productsDiv.appendChild(card);
});




// Show Carrinho de compras
const cartDiv = document.getElementById('cartContainer');
const cartMainDiv = document.getElementById('cartMainDiv');

const cartIcon = document.getElementById('cartIcon');
cartIcon.addEventListener('click', () => {
    cartMainDiv.style.display = "block";
});

const cartCloseButton = document.getElementById('cartCloseButton');
cartCloseButton.addEventListener("click", function(){
    cartMainDiv.style.display = "none";
});



// Função para adicionar itens ao carrinho
function addToCart(newItem) {
    const existingItem = cartItems.find(item => item.id_produto === newItem.id_produto);

    if (existingItem) {
        existingItem.quantidade++; // Aumenta a quantidade se o item já estiver no carrinho
    } else {
        newItem.quantidade = 1; // Define a quantidade inicial
        cartItems.push(newItem); // Adiciona o item ao carrinho
    }

    updateCart(); // Atualiza a exibição do carrinho
    cartMainDiv.style.display = "block";
}




let totalValue = 0; // Variável global para armazenar o valor total

// Função para atualizar a exibição do carrinho
function updateCart() {
    cartDiv.innerHTML = ''; // Limpa o conteúdo anterior do carrinho

    cartItems.forEach(item => {
        const cartItem = document.createElement('div');
        cartItem.setAttribute("class", "cartItem");


        // Cria a tag <img> para a imagem
        const productImage = document.createElement('img');
        productImage.src = item.imagem; // URL da imagem
        productImage.alt = item.nome;
        cartItem.appendChild(productImage);


        // Adiciona o nome e o preço do item
        const itemInfo = document.createElement('div');
        itemInfo.textContent = `${item.nome} - R$ ${item.preco}`;
        cartItem.appendChild(itemInfo);


        // Adiciona a quantidade
        const quantityDiv = document.createElement('div');
        const quantityText = document.createElement('span');
        quantityDiv.className = 'quantityDiv';
        quantityText.textContent = `Quantidade: ${item.quantidade}`;
        quantityDiv.appendChild(quantityText);


        // Botão para aumentar a quantidade
        const increaseButton = document.createElement('button');
        increaseButton.textContent = '+';
        increaseButton.addEventListener('click', () => {
            item.quantidade++;
            updateCart(); // Atualiza o carrinho
        });
        quantityDiv.appendChild(increaseButton);


        // Botão para diminuir a quantidade
        const decreaseButton = document.createElement('button');
        decreaseButton.textContent = '-';
        decreaseButton.addEventListener('click', () => {
            if (item.quantidade > 1) {
                item.quantidade--;
            } else {
                removeFromCart(item); // Remove o item se a quantidade for 0
            }
            updateCart(); // Atualiza o carrinho
        });
        quantityDiv.appendChild(decreaseButton);

        cartItem.appendChild(quantityDiv);


        // Botão para remover o item do carrinho
        const removeButton = document.createElement('button');
        removeButton.textContent = 'Remover';
        removeButton.addEventListener('click', () => {
            removeFromCart(item);
            updateCart(); // Atualiza o carrinho após remover
        });
        cartItem.appendChild(removeButton);

        cartDiv.appendChild(cartItem);
    });

    // Atualiza o total
    totalPrice = cartItems.reduce((total, item) => total + (parseFloat(item.preco) * item.quantidade), 0);
    const totalDiv = document.getElementById('cartTotal');
    totalDiv.textContent = `Total: R$ ${totalPrice.toFixed(2)}`;
}




// Função para remover um item do carrinho
function removeFromCart(itemToRemove) {
    const index = cartItems.findIndex(item => item.id_produto === itemToRemove.id_produto); // Assumindo que cada item tem um campo "id"
    if (index !== -1) {
        cartItems.splice(index, 1); // Remove o item do array
        updateCart(); // Atualiza a exibição do carrinho
    }
}
})
.catch(error => console.error('Erro ao buscar produtos:', error));

document.getElementById('cartBuyButton').addEventListener('click', () => {
    if (cartItems.length === 0) {
        alert("Seu carrinho está vazio!");
        return;
    }

    // Prepara os itens para a API do Mercado Pago
    const itemsForAPI = cartItems.map(item => ({
        id: item.id_produto,
        title: item.nome,
        quantity: item.quantidade,
        currency_id: "BRL",
        unit_price: parseFloat(item.preco)
    }));

    // Envia a requisição para o backend (apimercadopago.php)
    fetch('../apimercadopago.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ items: itemsForAPI })
    })
    .then(response => response.json())
    .then(data => {
        if (data.init_point) {
            // Redireciona para o link de pagamento
            window.location.href = data.init_point;
        } else {
            alert("Erro ao gerar o link de pagamento.");
        }
    })
    .catch(error => console.error('Erro ao processar pagamento:', error));
});
