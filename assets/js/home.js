// Exibir produtos na página
fetch('../../controller/get_products.php')
    .then(response => response.json())
    .then(data => {
        const productsDiv = document.getElementById('products');

        data.forEach(product => {
            const card = document.createElement('div');
            card.classList.add('card');

            const productImage = document.createElement('img');
            productImage.src = product.imagem;
            productImage.alt = product.imagem;
            card.appendChild(productImage);

            const productName = document.createElement('h2');
            productName.textContent = product.nome;
            card.appendChild(productName);

            const productPrice = document.createElement('p');
            productPrice.textContent = `R$ ${product.preco}`;
            card.appendChild(productPrice);

            const buyButton = document.createElement('button');
            buyButton.classList.add('btn', 'btn-primary');
            buyButton.textContent = "Comprar";

            buyButton.addEventListener('click', () => {
                addToCart(product);
                const successMsgProduct = document.getElementById('sucessMsgProduct');
                successMsgProduct.style.display = 'flex';
                setTimeout(() => {
                    successMsgProduct.style.display = 'none';
                }, 5000);
            });

            card.appendChild(buyButton);
            productsDiv.appendChild(card);
        });
    })
    .catch(error => console.error('Erro ao buscar produtos:', error));



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



// Exibir itens do carrinho ao carregar a página
document.addEventListener('DOMContentLoaded', updateCart);




// Função para adicionar itens ao carrinho
function addToCart(newItem) {
    fetch('../../controller/cart/save_item.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(newItem),
    })
    .then(() => {
        updateCart();  // Atualiza o carrinho
        cartMainDiv.style.display = "block";  // Abre o carrinho
    })
    .catch(error => console.error('Erro ao adicionar item ao carrinho:', error));
}


// Função para atualizar a exibição do carrinho
function updateCart() {
    fetch('../../controller/cart/get_cart_items.php')
        .then(response => response.json())
        .then(cartItems => {
            const cartDiv = document.getElementById('cartContainer');
            cartDiv.innerHTML = ''; // Limpa o conteúdo do carrinho

            cartItems.forEach(item => {
                const cartItem = document.createElement('div');
                cartItem.classList.add('cartItem');

                const productImage = document.createElement('img');
                productImage.src = item.imagem;
                productImage.alt = item.nome;
                cartItem.appendChild(productImage);

                const itemInfo = document.createElement('div');
                itemInfo.textContent = `${item.nome} - R$ ${item.preco}`;
                cartItem.appendChild(itemInfo);

                const quantityDiv = document.createElement('div');
                quantityDiv.className = 'quantityDiv';

                const quantityText = document.createElement('span');
                quantityText.textContent = `Quantidade: ${item.quantidade}`;
                quantityDiv.appendChild(quantityText);

                // Botão de Aumentar
                const increaseButton = document.createElement('button');
                increaseButton.textContent = '+';
                increaseButton.addEventListener('click', () => {
                    increaseQuantity(item.id_produto, item.quantidade);
                });
                quantityDiv.appendChild(increaseButton);

                // Botão de Diminuir
                const decreaseButton = document.createElement('button');
                decreaseButton.textContent = '-';
                decreaseButton.addEventListener('click', () => {
                    decreaseQuantity(item.id_produto, item.quantidade);
                });
                quantityDiv.appendChild(decreaseButton);

                cartItem.appendChild(quantityDiv);

                // Botão de Remover
                const removeButton = document.createElement('button');
                removeButton.textContent = 'Remover';
                removeButton.addEventListener('click', () => {
                    removeFromCart(item.id_produto);
                });
                cartItem.appendChild(removeButton);

                cartDiv.appendChild(cartItem);
            });

            const totalPrice = cartItems.reduce((total, item) => total + (parseFloat(item.preco) * item.quantidade), 0);
            const totalDiv = document.getElementById('cartTotal');
            totalDiv.textContent = `Total: R$ ${totalPrice.toFixed(2)}`;
        })
        .catch(error => console.error('Erro ao atualizar o carrinho:', error));
}


function increaseQuantity(id_produto, currentQuantity) {
    const newQuantity = currentQuantity + 1;

    fetch('../../controller/cart/update_cart_item.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `id_produto=${id_produto}&quantidade=${newQuantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCart(); // Atualiza o carrinho após a mudança
        } else {
            alert('Erro ao atualizar a quantidade');
        }
    })
    .catch(error => console.error('Erro ao aumentar a quantidade:', error));
}

function decreaseQuantity(id_produto, currentQuantity) {
    if (currentQuantity > 1) {
        const newQuantity = currentQuantity - 1;

        fetch('../../controller/cart/update_cart_item.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id_produto=${id_produto}&quantidade=${newQuantity}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCart(); // Atualiza o carrinho após a mudança
            } else {
                alert('Erro ao diminuir a quantidade');
            }
        })
        .catch(error => console.error('Erro ao diminuir a quantidade:', error));
    } else {
        removeFromCart(id_produto); // Se for 1, remove o item
    }
}



function removeFromCart(id_produto) {
    fetch('../../controller/cart/remove_cart_item.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `id_produto=${id_produto}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCart(); // Atualiza o carrinho após a remoção
        } else {
            alert('Erro ao remover o produto');
        }
    })
    .catch(error => console.error('Erro ao remover o item do carrinho:', error));
}

