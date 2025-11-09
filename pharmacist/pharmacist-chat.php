<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (!isset($_SESSION['pmspid']) || empty($_SESSION['pmspid'])) {
    header('location:logout.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Chat with AI - Dharani PMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
  <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
  <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
  <style>
    .chat-container {
      max-width: 500px;
      margin: 30px auto;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 20px 40px rgba(0,0,0,0.2);
      background: white;
    }
    .chat-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 25px;
      text-align: center;
    }
    .chat-body {
      height: 500px;
      overflow-y: auto;
      padding: 20px;
      background: #f8f9fa;
    }
    .message {
      margin: 15px 0;
      padding: 12px 20px;
      border-radius: 20px;
      max-width: 80%;
      word-wrap: break-word;
    }
    .user {
      background: #4e54c8;
      color: white;
      margin-left: auto;
      border-bottom-right-radius: 5px;
    }
    .bot {
      background: white;
      color: #333;
      margin-right: auto;
      border: 1px solid #ddd;
      border-bottom-left-radius: 5px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .input-area {
      padding: 20px;
      background: white;
      border-top: 1px solid #ddd;
    }
    #send-btn {
      border-radius: 50px;
      width: 50px;
      height: 50px;
    }
    .typing {
      font-style: italic;
      color: #666;
    }
  </style>
</head>
<body class="">
  <?php include_once('includes/navbar.php'); ?>
  <div class="main-content">
    <?php include_once('includes/sidebar.php'); ?>

    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6">
              <h1 class="text-white mb-0">
                <i class="fas fa-robot text-warning"></i> Chat with AI Assistant
              </h1>
              <p class="text-white opacity-8">Ask about medicine stock, hours, or anything!</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid mt--7">
      <div class="row">
        <div class="col-xl-8 mx-auto">
          <div class="card shadow">
            <div class="card-body p-0">
              <div class="chat-container">
                <div class="chat-header">
                  <h4><i class="fas fa-robot"></i> Dharani AI Assistant</h4>
                  <small>Gampaha | 7AM - 10PM</small>
                </div>
                <div class="chat-body" id="chat-body">
                  <div class="message bot">
                    <strong>Hi Pharmacist! 👋</strong><br>
                    I'm your AI assistant. Ask me:<br>
                    • "Do we have Paracetamol?"<br>
                    • "What time do we open?"<br>
                    • "Any expired medicine?"
                  </div>
                </div>
                <div class="input-area">
                  <div class="input-group">
                    <input type="text" id="user-input" class="form-control form-control-lg" placeholder="Type your message..." autocomplete="off">
                    <button class="btn btn-primary btn-lg" id="send-btn">
                      <i class="fas fa-paper-plane"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer text-center no-print">
              <small class="text-muted">
                <i class="fas fa-info-circle"></i> Powered by DeepSeek AI • Real-time medicine stock check
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
  <script>
    const chatBody = document.getElementById('chat-body');
    const userInput = document.getElementById('user-input');
    const sendBtn = document.getElementById('send-btn');

    function addMessage(text, type) {
      const div = document.createElement('div');
      div.className = 'message ' + type;
      div.innerHTML = text;
      chatBody.appendChild(div);
      chatBody.scrollTop = chatBody.scrollHeight;
    }

    function sendMessage() {
      const msg = userInput.value.trim();
      if (!msg) return;

      addMessage(msg, 'user');
      userInput.value = '';
      addMessage('<span class="typing">AI is thinking...</span>', 'bot');

      fetch('chat-api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'message=' + encodeURIComponent(msg)
      })
      .then(r => r.json())
      .then(data => {
        chatBody.lastElementChild.remove(); // remove "thinking"
        addMessage(data.reply, 'bot');
      })
      .catch(() => {
        chatBody.lastElementChild.remove();
        addMessage('Sorry, AI is busy. Try again!', 'bot');
      });
    }

    sendBtn.addEventListener('click', sendMessage);
    userInput.addEventListener('keypress', e => {
      if (e.key === 'Enter') sendMessage();
    });
  </script>
</body>
</html>