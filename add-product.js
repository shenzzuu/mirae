const form = document.getElementById("productForm");
const nameInput = document.getElementById("name");
const priceInput = document.getElementById("price");
const promoInput = document.getElementById("promo");
const descriptionInput = document.getElementById("description");
const imageInput = document.getElementById("image");
const productIdInput = document.getElementById("productId");
const formTitle = document.getElementById("formTitle");

let products = JSON.parse(localStorage.getItem("products")) || [];
let editIndex = localStorage.getItem("editIndex");

if (editIndex !== null) {
  const product = products[editIndex];
  nameInput.value = product.name;
  priceInput.value = product.price;
  promoInput.value = product.promo;
  descriptionInput.value = product.description;
  productIdInput.value = editIndex;
  formTitle.textContent = "Edit Product";
  localStorage.removeItem("editIndex");
}

form.addEventListener("submit", function (e) {
  e.preventDefault();

  const name = nameInput.value;
  const price = parseFloat(priceInput.value);
  const promo = parseFloat(promoInput.value);
  const description = descriptionInput.value;

  const imageFile = imageInput.files[0];
  const index = productIdInput.value;

  if (imageFile) {
    const reader = new FileReader();
    reader.onloadend = function () {
      const image = reader.result;
      saveProduct(name, price, promo, description, image, index);
    };
    reader.readAsDataURL(imageFile);
  } else {
    const existingImage = index ? products[index]?.image : "";
    saveProduct(name, price, promo, description, existingImage, index);
  }
});

function saveProduct(name, price, promo, description, image, index) {
  const product = { name, price, promo, description, image };

  if (index) {
    products[index] = product;
  } else {
    products.push(product);
  }

  localStorage.setItem("products", JSON.stringify(products));
  location.href = "product.html";
}
