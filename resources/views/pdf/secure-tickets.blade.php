<!DOCTYPE html>
<html>
<head>
    <title>Secure Tickets Report</title>
    <style>
        /* Modern Color Theme */
        :root {
            --primary: #2c3e50;
            --primary-light: #3d566e;
            --primary-dark: #1a252f;
            --primary-extra-light: #f5f7fa;
            --accent: #e74c3c;
            --accent-light: #ff8a7a;
            --text: #2d3436;
            --text-light: #636e72;
            --light-bg: #f8fafc;
            --border: #dfe6e9;
            --success: #27ae60;
            --warning: #f39c12;
            --danger: #e74c3c;
            --info: #3498db;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        body {
            font-family: 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            color: var(--text);
            line-height: 1.6;
            padding: 0;
            margin: 0;
            background-color: white;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            font-size: 12px;
        }
        
        /* Watermark */
        .watermark {
            position: fixed;
            opacity: 0.1;
            font-size: 120px;
            color: var(--primary);
            transform: rotate(-45deg);
            transform-origin: center;
            z-index: -1;
            top: 50%;
            left: 50%;
            margin-top: -200px;
            margin-left: -400px;
            font-weight: bold;
            pointer-events: none;
        }
        
        /* Header */
        .header {
            background-color: var(--primary);
            color: white;
            padding: 20px 0;
            margin-bottom: 25px;
            box-shadow: var(--shadow);
        }
        
        .header-content {
            width: 95%;
            max-width: 1000px;
            margin: 0 auto;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .header p {
            margin: 5px 0 0;
            font-size: 12px;
            opacity: 0.9;
        }
        
        /* Report Container */
        .report-container {
            width: 95%;
            max-width: 1000px;
            margin: 0 auto;
        }
        
        /* Info Section */
        .info-section {
            margin-bottom: 25px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .info-card {
            background-color: white;
            border-radius: 6px;
            padding: 15px;
            box-shadow: var(--shadow);
            border-top: 4px solid var(--primary);
        }
        
        .info-card h3 {
            margin: 0 0 10px 0;
            font-size: 12px;
            color: var(--text-light);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-card p {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: var(--primary);
        }
        
        /* Section Title */
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--primary);
            margin: 25px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--primary-extra-light);
        }
        
        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 11px;
            box-shadow: var(--shadow);
        }
        
        thead {
            background-color: var(--primary);
            color: white;
        }
        
        th {
            padding: 10px 8px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
        }
        
        td {
            padding: 8px;
            border-bottom: 1px solid var(--border);
            vertical-align: top;
        }
        
        tbody tr:nth-child(even) {
            background-color: var(--primary-extra-light);
        }
        
        tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        /* Status and Priority Indicators */
        .priority-high {
            color: var(--danger);
            font-weight: 600;
            background-color: rgba(231, 76, 60, 0.1);
            border-radius: 4px;
            padding: 2px 6px;
            display: inline-block;
        }
        
        .priority-medium {
            color: var(--warning);
            font-weight: 600;
            background-color: rgba(243, 156, 18, 0.1);
            border-radius: 4px;
            padding: 2px 6px;
            display: inline-block;
        }
        
        .priority-low {
            color: var(--success);
            font-weight: 600;
            background-color: rgba(39, 174, 96, 0.1);
            border-radius: 4px;
            padding: 2px 6px;
            display: inline-block;
        }
        
        .status-open {
            color: var(--info);
            font-weight: 600;
            background-color: rgba(52, 152, 219, 0.1);
            border-radius: 4px;
            padding: 2px 6px;
            display: inline-block;
        }
        
        .status-in-progress {
            color: var(--warning);
            font-weight: 600;
            background-color: rgba(243, 156, 18, 0.1);
            border-radius: 4px;
            padding: 2px 6px;
            display: inline-block;
        }
        
        .status-resolved {
            color: var(--success);
            font-weight: 600;
            background-color: rgba(39, 174, 96, 0.1);
            border-radius: 4px;
            padding: 2px 6px;
            display: inline-block;
        }
        
        .status-closed {
            color: var(--text-light);
            font-weight: 600;
            background-color: rgba(99, 110, 114, 0.1);
            border-radius: 4px;
            padding: 2px 6px;
            display: inline-block;
        }
        
        /* Rating Stars */
        .rating-stars {
            color: var(--warning);
            font-size: 12px;
            letter-spacing: 1px;
        }
        
        /* Footer */
        .footer {
            margin-top: 40px;
            padding: 20px 0;
            border-top: 1px solid var(--border);
            font-size: 10px;
            color: var(--text-light);
            text-align: center;
        }
        
        .footer p {
            margin: 5px 0;
        }
        
        /* Page Break Control */
        .page-break {
            page-break-after: always;
        }
        
        /* Utility Classes */
        .text-capitalize {
            text-transform: capitalize;
        }
        
        /* Print-specific styles */
        @media print {
            body {
                padding: 0;
                font-size: 10pt;
            }
            
            .header {
                padding: 15px 0;
            }
            
            .info-card {
                page-break-inside: avoid;
            }
            
            table {
                page-break-inside: auto;
            }
            
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            
            thead {
                display: table-header-group;
            }
            
            tfoot {
                display: table-footer-group;
            }
        }
    </style>
</head>
<body>
    <!-- Watermark Background -->
    <div class="watermark">CONFIDENTIAL</div>
    
    <!-- Header Section -->
    <div class="header">
        <div class="header-content">
            <h1>TICKETS MANAGEMENT REPORT</h1>
            <p>Generated on: {{ date('F j, Y \a\t g:i A') }}</p>
        </div>
    </div>
    
    <div class="report-container">
        <!-- Report Info Section -->
        <div class="info-section">
            <div class="info-grid">
                <div class="info-card">
                    <h3>Report Period</h3>
                    <p>
                        @if($filter === 'weekly')
                            Week of {{ date('M j', strtotime('last monday')) }} - {{ date('M j, Y', strtotime('next sunday')) }}
                        @elseif($filter === 'monthly')
                            {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}
                        @elseif($filter === 'annually')
                            Calendar Year {{ $year }}
                        @else
                            Custom Date Range
                        @endif
                    </p>
                </div>
                
                <div class="info-card">
                    <h3>Total Tickets</h3>
                    <p>{{ count($tickets) }} Tickets</p>
                </div>
                
                <div class="info-card">
                    <h3>Generated By</h3>
                    <p class="text-capitalize">{{ auth()->user()->name }}</p>
                </div>
                
                <div class="info-card">
                    <h3>Average Rating</h3>
                    <p>
                        @php
                            $rated = array_filter($tickets->toArray(), function($t) { return isset($t['rating']) && $t['rating'] > 0; });
                            echo count($rated) > 0 
                                ? round(array_sum(array_column($rated, 'rating')) / count($rated), 1) . ' / 5'
                                : 'No ratings yet';
                        @endphp
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Tickets Table -->
        <h3 class="section-title">Ticket Details</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">Control #</th>
                    <th style="width: 12%;">Date/Time</th>
                    <th style="width: 12%;">Requester</th>
                    <th style="width: 12%;">Department</th>
                    <th style="width: 18%;">Concern</th>
                    <th style="width: 8%;">Priority</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 10%;">Work Done</th>
                    <th style="width: 8%;">Duration</th>
                    <th style="width: 12%;">Rating</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tickets as $ticket)
                <tr>
                    <td style="font-weight: 600; color: var(--primary);">{{ $ticket['control_no'] }}</td>
                    <td>{{ date('M j, Y g:i A', strtotime($ticket['date_time'])) }}</td>
                    <td>{{ $ticket['name'] }}</td>
                    <td>{{ $ticket['department'] }}</td>
                    <td>{{ Str::limit($ticket['concern'], 50) }}</td>
                    <td class="priority-{{ strtolower($ticket['priority']) }}">
                        {{ $ticket['priority'] }}
                    </td>
                    <td class="status-{{ strtolower(str_replace(' ', '-', $ticket['status'])) }}">
                        {{ $ticket['status'] }}
                    </td>
                    <td>{{ $ticket['remarks'] }}</td>
                    <td>{{ $ticket['duration'] ?? '0m' }}</td>
                    <td>
                        @if($ticket['rating'] ?? false)
                            <span class="rating-stars">
                                {{ str_repeat('★', $ticket['rating']) }}{{ str_repeat('☆', 5 - $ticket['rating']) }}
                            </span>
                            @if(isset($ticket['rating_percentage']))
                            <br><small>({{ $ticket['rating_percentage'] ?? 0 }}%)</small>
                            @endif
                        @else
                            <span style="color: #999;">N/A</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p><strong>CONFIDENTIAL REPORT</strong> - This document contains privileged information and is intended solely for the use of authorized personnel.</p>
        <p>Document ID: {{ strtoupper(Str::random(3)) }}-{{ rand(1000, 9999) }} | Generated by {{ config('app.name') }} v{{ config('app.version') }}</p>
        <p>© {{ date('Y') }} {{ config('app.company_name') }}. All rights reserved.</p>
    </div>
</body>
</html>