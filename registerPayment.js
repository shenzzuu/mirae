window.onload = () => {
  fetch('footer.html')
    .then(res => res.text())
    .then(data => document.getElementById('footer').innerHTML = data);
};

function checkForm() {
  const name = document.getElementById('name').value.trim();
  const card = document.getElementById('cardNumber').value.trim();
  const expiry = document.getElementById('expiry').value.trim();
  const cvv = document.getElementById('cvv').value.trim();

  const isFilled = name && card && expiry && cvv;
  document.getElementById('payBtn').disabled = !isFilled;
}

function processPayment() {
  const userData = JSON.parse(localStorage.getItem("registrationData"));
  
  if (!userData) {
    alert("Missing registration data. Please register again.");
    window.location.href = "register.html";
    return;
  }

  fetch("register.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: new URLSearchParams({
      fullName: userData.fullName,
      email: userData.email,
      phone: userData.phone,
      password: userData.password,
      membershipPlan: userData.plan
    })
  })
  .then(res => res.text())
  .then(response => {
    if (response.toLowerCase().includes("success")) {
      alert("Payment successful! Your account has been registered.");
      localStorage.removeItem("registrationData");
      window.location.href = "login.html";
    } else {
      alert("Something went wrong: " + response);
    }
  })
  .catch(error => {
    alert("Server error: " + error);
  });
}
