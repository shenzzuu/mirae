const productList = document.getElementById("productList");
let products = JSON.parse(localStorage.getItem("products")) || [];

function renderProducts() {
  productList.innerHTML = "";

  products.forEach((product, index) => {
    const card = document.createElement("div");
    card.className = "product-card";
    card.innerHTML = `
      <img src="${product.image || ""}" alt="Product Image">
      <h3>${product.name}</h3>
      <p>Price: RM ${product.price}</p>
      <p>Promo: ${product.promo}%</p>
      <p>${product.description}</p>
      <div class="actions">
        <button onclick="editProduct(${index})">Edit</button>
        <button onclick="deleteProduct(${index})">Delete</button>
      </div>
    `;
    productList.appendChild(card);
  });
}

function editProduct(index) {
  localStorage.setItem("editIndex", index);
  location.href = "add-product.html";
}

function deleteProduct(index) {
  if (confirm("Are you sure you want to delete this product?")) {
    products.splice(index, 1);
    localStorage.setItem("products", JSON.stringify(products));
    renderProducts();
  }
}

renderProducts();
