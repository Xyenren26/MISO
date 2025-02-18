<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat System</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/Navbar_Style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
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
                        @if (Auth::user()->account_type === 'technical_support')
                            @foreach ($technicalSupportUsers as $techUser)
                                <li class="user" data-user-id="{{ $techUser->employee_id }}">
                                    <span>{{ $techUser->first_name }} {{ $techUser->last_name }}</span>
                                    <div class="user-status offline" id="status-{{ $techUser->employee_id }}"></div>
                                    <span class="notification-badge" id="badge-{{ $techUser->employee_id }}" style="display: none;"></span>
                                </li>
                            @endforeach
                        @else
                            @if ($assignedTechUsers->isNotEmpty())
                                <p style="color: green;">✅ Assigned Technical Support Found</p>
                                @foreach ($assignedTechUsers as $techUser)
                                    <li class="user" data-user-id="{{ $techUser->employee_id }}">
                                        <span>{{ $techUser->first_name }} {{ $techUser->last_name }}</span>
                                        <div class="user-status offline" id="status-{{ $techUser->employee_id }}"></div>
                                        <span class="notification-badge" id="badge-{{ $techUser->employee_id }}" style="display: none;"></span>
                                    </li>
                                @endforeach
                            @else
                                <p style="color: red;">❌ No Assigned Technical Support Found</p>
                            @endif
                        @endif
                    </ul>
                </div>


                <!-- Employee Accordion (Visible only for Technical Support users) -->
                @if (Auth::user()->account_type === 'technical_support')
                <div class="accordion">
                    <h4>Employees</h4>
                    <ul id="employee-accordion">
                        @if ($assignedEndUsers->isNotEmpty())
                            @foreach ($assignedEndUsers as $endUser)
                                <li class="user" data-user-id="{{ $endUser->employee_id }}">
                                    <span>{{ $endUser->first_name }} {{ $endUser->last_name }}</span>
                                    <div class="user-status offline" id="status-{{ $endUser->employee_id }}"></div>
                                    <span class="notification-badge" id="badge-{{ $endUser->employee_id }}" style="display: none;"></span>
                                </li>
                            @endforeach
                        @else
                            <p style="color: red;">❌ No Assigned Employees Found</p>
                        @endif
                    </ul>
                </div>
            @endif
            </div> <!-- Close #user-list -->

            <!-- Chat Area -->
            <div id="chat-area">
                <div id="chat-header">Select a user to chat</div>
                <div id="messages">
                    <!-- Messages will be dynamically loaded here -->
                </div>

                <!-- Scroll to Bottom Button -->
                <button id="scrollButton" style="display: none;">
                    <i class="fas fa-arrow-down"></i>
                </button>

                <!-- Chat Input Area -->
                <div id="chat-input-container">
                    <label for="file-upload" id="send-file" title="Send File">
                        <i class="fas fa-paperclip"></i>
                    </label>
                    <textarea id="chat-input" placeholder="Type your message..."></textarea>
                    <input type="file" id="file-upload" style="display: none;">
                    <button id="send-message" title="Send Message">
                        <span>&#10148;</span>
                    </button>
                </div>
            </div>
        </div> <!-- Close #chat-container -->

    </div> <!-- Close .main-content -->
</div> <!-- Close .container -->

<!-- Scripts -->
<script src="{{ asset('js/chat.js') }}"></script>
<script src="{{ asset('js/chat_json.js') }}"></script>

</body>
</html>