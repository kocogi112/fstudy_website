<?php
/*
 * Template Name: Gift User Token Template
 * Template Post Type: user token page
 
 */


// Database credentials (update with your own database details)
$servername = "localhost";
$username = "root";
$password = ""; // No password by default
$dbname = "wordpress";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy giá trị custom_gift_id từ URL
global $wp_query;
//$custom_gift_id = $wp_query->get('custom_gift_id');


  global $wpdb;
 // Get the current user
 $current_user = wp_get_current_user();
 $current_username = $current_user->user_login;
 $user_id = $current_user->ID; // Lấy user ID

  // Thực hiện truy vấn để lấy id_video_orginal 
  $sql = "SELECT username, updated_time, token, token_use_history FROM user_token WHERE username = %s";
  $result = $wpdb->get_row($wpdb->prepare($sql, $current_username));

  
   

  // Đóng kết nối
  $conn->close();

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Token Transactions</title>
  <style>
   
    
    h1 {
      text-align: center;
      margin-bottom: 20px;
    }
    .tabs {
      display: flex;
      justify-content: space-around;
      margin-bottom: 20px;
      cursor: pointer;
    }
    .tab {
      padding: 10px 15px;
      background: #ddd;
      border-radius: 5px;
      text-align: center;
      flex-grow: 1;
      font-weight: bold;
    }
    .tab.active {
      background: #007bff;
      color: white;
    }
    .transaction-list {
      list-style: none;
      padding: 10px;
    }
    .transaction {
      background: #f9f9f9;
      padding: 15px;
      margin-bottom: 10px;
      border-radius: 5px;
      position: relative;
      box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
    }
    .transaction-header {
      font-weight: bold;
    }
    .view-details {
      color: #007bff;
      cursor: pointer;
      font-size: 14px;
    }
    #popup {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      z-index: 1000;
      max-width: 400px;
      display: none;
    }
    #popup-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 999;
      display: none;
    }
    .popup-content {
      margin-bottom: 10px;
    }
    .close-popup {
      background: red;
      color: white;
      padding: 5px 10px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div id="container">
    <h3>Token Transaction History</h3>
    <div class="user-token">
        <p>Username: <?= $result->username ?? 'Không có dữ liệu' ?></p>
        <p>Time updated: <?= $result->updated_time ?? 'Không có dữ liệu' ?></p>
        <p>Token: <?= $result->token ?? 'Không có dữ liệu' ?></p>
    </div>
    <div class="tabs">
      <div class="tab active" data-tab="all">All Transactions</div>
      <div class="tab" data-tab="buy">Buy Transactions</div>
      <div class="tab" data-tab="paid">Token Usage</div>
      <div class="tab" data-tab="gift">Gift Token</div>
    </div>

    <ul id="transaction-list" class="transaction-list"></ul>
  </div>

  <div id="popup-overlay"></div>
  <div id="popup">
    <div id="popup-content"></div>
    <button class="close-popup">Close</button>
  </div>

  <script>
    const transactionData = <?php echo $result->token_use_history ?? 'Không có dữ liệu' ?>
     
    

    function renderTransactions(filterType) {
      const list = document.getElementById('transaction-list');
      list.innerHTML = '';
      const sortedData = transactionData.sort((a, b) => new Date(b.update_time) - new Date(a.update_time));
      const filteredData = filterType === 'all' ? sortedData :
        filterType === 'buy' ? sortedData.filter(tx => !tx.type_transaction || tx.type_transaction === 'buy') :
        sortedData.filter(tx => tx.type_transaction === filterType);

      filteredData.forEach(transaction => {
        const listItem = document.createElement('li');
        listItem.classList.add('transaction');

        let content = `<div class='transaction-header'>${transaction.title.substring(0, 50)}</div>`;
        const transactionType = transaction.type_transaction || 'buy';

        if (transactionType === 'buy') {
          content += `<p>Payment Gate: ${transaction.payment_gate} | Amount: ${transaction.amount} | Tokens Added: ${transaction.change_token}</p>`;
        } else if (transactionType === 'paid') {
          content += `<p>Tokens Used: ${transaction.change_token}</p>`;
        } else if (transactionType === 'gift') {
          content += `<p>Gift Tokens Added: ${transaction.change_token}</p>`;
        }

        // Display transaction update time
        content += `<p>Update Time: ${transaction.update_time}</p>`;
        content += `<span class='view-details' onclick='showDetails(${JSON.stringify(transaction)})'>View Details</span>`;

        listItem.innerHTML = content;
        list.appendChild(listItem);
      });
}


    function showDetails(transaction) {
      document.getElementById('popup-overlay').style.display = 'block';
      document.getElementById('popup').style.display = 'block';
      document.getElementById('popup-content').innerHTML = `
        <div class='popup-content'><strong>#Order Code:</strong> Đang cập nhật </div>

        <div class='popup-content'><strong>Title:</strong> ${transaction.title}</div>
        <div class='popup-content'><strong>Biến động Token:</strong> ${transaction.change_token}</div>
        <div class='popup-content'><strong>Cổng thanh toán:</strong> ${transaction.payment_gate || 'N/A'}</div>
        <div class='popup-content'><strong>Thời gian:</strong> ${transaction.update_time}</div>
        <div class='popup-content'><strong>Số tiền:</strong> ${transaction.amount || 'N/A'}</div>
      `;
    }

    document.querySelector('.close-popup').addEventListener('click', function () {
      document.getElementById('popup-overlay').style.display = 'none';
      document.getElementById('popup').style.display = 'none';
    });

    document.querySelectorAll('.tab').forEach(tab => {
      tab.addEventListener('click', function () {
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        renderTransactions(this.dataset.tab);
      });
    });

    // Initial render
    renderTransactions('all');
  </script>
</body>
</html>
