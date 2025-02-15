<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ asset('css/Navbar_Style.css') }}">
    <title>Chat System</title>

    <!-- Link to the external CSS file -->
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Navbar_Style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


</head>
<body>

<div class="container">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Navbar -->
        @include('components.navbar')

    <div id="chat-container">

    
        <!-- Sidebar with list of active users -->
        <div id="user-list">
    <h3>Active Users</h3>

    <!-- Technical Support Accordion -->
    <div class="accordion">
        <h4>Technical Support</h4>
        <ul id="tech-accordion">
            @foreach ($users as $user)
                @if ($user->employee_id !== Auth::user()->employee_id && $user->account_type === 'technical_support')
                <li class="user" data-user-id="{{ $user->employee_id }}">
                    <span>{{ $user->first_name }} {{ $user->last_name }}</span>
                    <div class="user-status offline" id="status-{{ $user->employee_id }}"></div>
                    <span class="notification-badge" id="badge-{{ $user->employee_id }}" style="display: none;"></span>
                </li>


                @endif
            @endforeach
        </ul>
    </div>

    <!-- Employee Accordion -->
    <div class="accordion">
        <h4>Employees</h4>
        <ul id="employee-accordion">
            @foreach ($users as $user)
                @if ($user->employee_id !== Auth::user()->employee_id && $user->account_type === 'employee')
                <li class="user" data-user-id="{{ $user->employee_id }}">
                    <span>{{ $user->first_name }} {{ $user->last_name }}</span>
                    <div class="user-status offline" id="status-{{ $user->employee_id }}"></div>
                    <span class="notification-badge" id="badge-{{ $user->employee_id }}" style="display: none;"></span>
                </li>


                @endif
            @endforeach
        </ul>
    </div>
</div>

<!-- Chat Area -->
<div id="chat-area">
    <div id="chat-header">Select a user to chat</div>
    <div id="messages">
        <!-- Messages will be dynamically loaded here -->
    </div>

    <!-- Chat Input Area with Send Button (Moved inside chat-area) -->
    <div id="chat-input-container">
        <label for="file-upload" id="send-file" title="Send File">
        <i class="fas fa-paperclip"></i> <!-- Font Awesome paperclip icon -->
    </label>
    <textarea id="chat-input" placeholder="Type your message..."></textarea>
    <input type="file" id="file-upload" style="display: none;">
    <button id="send-message" title="Send Message">
        <span>&#10148;</span> <!-- Unicode for right arrow -->
    </button>
</div>



    <!-- Link to the external JavaScript file -->
    <script src="{{ asset('js/chat.js') }}"></script>
    <script src="{{ asset('js/chat_json.js') }}"></script>
</body>
</html>
