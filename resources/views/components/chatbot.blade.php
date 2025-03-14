<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dialogflow Messenger</title>
  <style>
    /* Custom CSS for mobile responsiveness */
    df-messenger {
      --df-messenger-button-size: 56px;
      --df-messenger-chat-width: 300px;
      --df-messenger-chat-height: 400px;
    }

    @media (max-width: 480px) {
      df-messenger {
        --df-messenger-chat-width: 90vw; /* Adjust chat width for mobile */
        --df-messenger-chat-height: 80vh; /* Adjust chat height for mobile */
      }
    }
  </style>
  <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
</head>
<body>
  <df-messenger
    intent="WELCOME"
    chat-title="Jake"
    agent-id="9684bf36-ab30-4fa2-b971-e7bf753a816b"
    language-code="en"
  ></df-messenger>
</body>
</html>