
<?php
require 'includes/header.php';?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FAQ Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    :root {
      --primary-color: #096B68;
      --primary-light: #E0F2F1;
      --primary-dark: #063E3C;
      --accent-color: #FF6B6B;
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }
    
    .header {
      background-color: var(--primary-color);
      color: white;
      padding: 2rem 0;
      margin-bottom: 2rem;
      /* border-radius: 0 0 20px 20px; */
    }
    
    .tab-btn {
      cursor: pointer;
      padding: 20px;
      text-align: center;
      background-color: white;
      border-radius: 10px;
      margin: 0 auto;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
      border: 2px solid transparent;
    }
    
    .tab-btn:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }
    
    .tab-btn.active {
      background-color: var(--primary-color);
      color: white;
      border-color: var(--primary-dark);
    }
    
    .tab-icon {
      font-size: 2rem;
      margin-bottom: 10px;
      color: var(--primary-color);
    }
    
    .tab-btn.active .tab-icon {
      color: white;
    }
    
    .faq-question {
      border-bottom: 1px solid #ddd;
      padding: 15px 0;
      cursor: pointer;
      font-weight: 500;
      color: var(--primary-dark);
      transition: all 0.2s ease;
    }
    
    .faq-question:hover {
      color: var(--primary-color);
    }
    
    .faq-question::after {
      content: '+';
      float: right;
      font-weight: bold;
      color: var(--primary-color);
    }
    
    .faq-question.active::after {
      content: '-';
    }
    
    .faq-answer {
      display: none;
      padding: 15px 0;
      color: #555;
      line-height: 1.6;
    }
    
    #searchInput {
      border-radius: 50px;
      padding: 12px 20px;
      border: 2px solid var(--primary-light);
    }
    
    #searchInput:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.25rem rgba(9, 107, 104, 0.25);
    }
    
    .search-container {
      position: relative;
    }
    
    .search-container i {
      position: absolute;
      right: 20px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--primary-color);
    }
    
    .container {
      max-width: 1000px;
    }
    
    @media (max-width: 768px) {
      .tab-btn {
        margin-bottom: 10px;
      }
    }
  </style>
</head>
<body>
<div class="container d-flex flex-column justify-content-center fixed" style="margin-top: 80px;"></div>
<div class="header">
  <div class="container text-center">
    <h1>Frequently Asked Questions</h1>
    <p class="lead">Find answers to common questions about our services</p>
  </div>
</div>

<div class="container">
  <div class="search-container mb-4">
    <input type="text" id="searchInput" class="form-control" placeholder="Search for a question...">
    <i class="fas fa-search"></i>
  </div>

  <div class="row text-center mb-3">
    <div class="col-md-3 tab-btn active" data-tab="general">
      <div class="tab-icon"><i class="fas fa-lightbulb"></i></div>
      <div><strong>General & Accounts</strong></div>
    </div>
    <div class="col-md-3 tab-btn" data-tab="payments">
      <div class="tab-icon"><i class="fas fa-book"></i></div>
      <div><strong>Package & Course</strong></div>
    </div>
    <div class="col-md-3 tab-btn" data-tab="business">
      <div class="tab-icon"><i class="fas fa-graduation-cap"></i></div>
      <div><strong>Academy & Admission</strong></div>
    </div>
    <div class="col-md-3 tab-btn" data-tab="sellers">
      <div class="tab-icon"><i class="fas fa-briefcase"></i></div>
      <div><strong>Exam & Career</strong></div>
    </div>
  </div>

  <div id="faqContainer" class="bg-white p-4 rounded shadow-sm"></div>
</div>

<script>
const faqs = {
  general: [
    { q: "Is Two-Factor Authentication (2FA) mandatory?", a: "Yes, for your security we require all users to enable 2FA to protect their accounts from unauthorized access." },
    { q: "Do you charge fees for payments or wire transfers?", a: "We do not charge any fees for payments or wire transfers. However, intermediary banks may apply their own charges which are beyond our control." },
    { q: "How do I open an account with you?", a: "You can open an account by filling out our online application form. Approval usually takes 1–2 business days after we receive all required documents." },
    { q: "How much can I transact with your platform?", a: "Transaction limits depend on your account type and verification level. Basic accounts have lower limits while fully verified accounts enjoy higher transaction capacities." },
    { q: "Can I buy travel money or cash from you?", a: "No, we are a digital platform and do not offer physical currency or travel money services." }
  ],
  payments: [
    { q: "What payment methods are supported?", a: "We support bank transfers, direct debits, credit/debit cards (Visa, Mastercard), and popular e-wallets like PayPal and Skrill." },
    { q: "How long do transfers take?", a: "Domestic transfers usually complete within 24 hours. International transfers typically take 1–3 business days depending on the destination country." }
  ],
  business: [
    { q: "Do you support mass payments?", a: "Yes, our platform can handle bulk payments efficiently. You can upload a spreadsheet or connect via API to process large batches of payments." },
    { q: "Can I open accounts in multiple currencies?", a: "Yes, we support multi-currency accounts with over 20 different currencies available for holding and transferring funds." }
  ],
  sellers: [
    { q: "Can I link my e-commerce store?", a: "Yes, we offer integration with major e-commerce platforms including Amazon, Shopify, and WooCommerce." },
    { q: "Do you support other marketplaces?", a: "Yes, our platform integrates with eBay, Etsy, AliExpress, and most other major online marketplaces." }
  ]
};

function loadFAQs(category) {
  const container = $("#faqContainer");
  container.empty();
  
  if (faqs[category].length === 0) {
    container.html('<div class="text-center py-4"><i class="fas fa-info-circle fa-2x mb-3" style="color: #096B68"></i><p>No questions found in this category</p></div>');
    return;
  }
  
  faqs[category].forEach((faq, index) => {
    const item = $(
      `<div class="faq-item">
        <div class="faq-question" data-index="${index}"><strong>${faq.q}</strong></div>
        <div class="faq-answer">${faq.a}</div>
      </div>`
    );
    container.append(item);
  });
}

$(document).ready(function () {
  loadFAQs("general");

  $(".tab-btn").click(function () {
    $(".tab-btn").removeClass("active");
    $(this).addClass("active");
    const category = $(this).data("tab");
    loadFAQs(category);
  });

  $("#faqContainer").on("click", ".faq-question", function () {
    $(this).toggleClass("active");
    $(this).next(".faq-answer").slideToggle();
  });

  $("#searchInput").on("keyup", function () {
    const value = $(this).val().toLowerCase();
    $(".faq-item").each(function() {
      const text = $(this).text().toLowerCase();
      $(this).toggle(text.indexOf(value) > -1);
    });
    
    // Show all answers when searching
    if (value.length > 0) {
      $(".faq-answer").slideDown();
      $(".faq-question").addClass("active");
    }
  });
});
</script>
</body>
</html>
<?php include "includes/footer.php" ?>