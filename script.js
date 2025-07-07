// Get cart data from PHP
let cart = window.cartData || [];

// Update the cart UI
function updateCartUI() {
  const container = document.querySelector('.cart-items');
  container.innerHTML = '';

  let subtotal = 0;

  cart.forEach((item, index) => {
    subtotal += item.prodPrice * item.quantity;

    container.innerHTML += `
      <div class="cart-item">
        <div class="cart-item-image">
          <img src="uploads/${item.prodImage}" alt="${item.prodName}" style="width: 60px;">
        </div>
        <div class="cart-item-details">
          <h3 class="cart-item-name">${item.prodName}</h3>
          <p class="cart-item-price">RM ${item.prodPrice}</p>
        </div>
        <div class="cart-item-quantity">
          <button class="quantity-btn" onclick="updateQuantity(${index}, 'decrease')">-</button>
          <span class="quantity">${item.quantity}</span>
          <button class="quantity-btn" onclick="updateQuantity(${index}, 'increase')">+</button>
        </div>
        <div class="cart-item-remove">
          <button class="remove-btn" onclick="removeItem(${index})">×</button>
        </div>
      </div>
    `;
  });

  document.getElementById('subtotal').textContent = subtotal.toFixed(2);
}

// Send an AJAX request to update the cart on the server
function updateCartOnServer(index, action) {
  const formData = new FormData();
  formData.append('index', index);
  formData.append('action', action);

  fetch('update_cart.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.status === 'success') {
      cart = data.cart; // Update local cart
      updateCartUI();   // Refresh UI
    } else {
      alert('Something went wrong!');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Failed to update cart.');
  });
}

// Increase or decrease quantity
function updateQuantity(index, action) {
  updateCartOnServer(index, action);
}

// Remove item
function removeItem(index) {
  updateCartOnServer(index, 'remove');
}

// Init
window.onload = updateCartUI;
