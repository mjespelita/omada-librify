
<!DOCTYPE html>
<html lang='{{ str_replace('_', '-', app()->getLocale()) }}'>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <meta name='csrf-token' content='{{ csrf_token() }}'>
        <meta name='author' content='Mark Jason Penote Espelita'>
        <meta name='keywords' content='keyword1, keyword2'>
        <meta name='description' content='Dolorem natus ab illum beatae error voluptatem incidunt quis. Cupiditate ullam doloremque delectus culpa. Autem harum dolorem praesentium dolorum necessitatibus iure quo. Et ea aut voluptatem expedita.'>

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link href='{{ url('assets/bootstrap/bootstrap.min.css') }}' rel='stylesheet'>
        <!-- FontAwesome for icons -->
        <link href='{{ url('assets/font-awesome/css/all.min.css') }}' rel='stylesheet'>
        <link rel='stylesheet' href='{{ url('assets/custom/style.css') }}'>
        <link rel='icon' href='{{ url('assets/logo.png') }}'>
    </head>
    <body class='font-sans antialiased'>

        <!-- Sidebar for Desktop View -->
        <div class='sidebar' id='mobileSidebar'>
            <div class='logo'>
                <div class="p-3">
                    <img src='{{ url('assets/librify-logo.png') }}' alt=''> <br>
                </div>
                <div class="p-4">
                    <small>Powered by</small>
                    <img src='{{ url('assets/logo.png') }}' alt='' style="width: 60px !important">
                </div>
            </div>
            <a href='{{ url('dashboard') }}' class='{{ request()->is('dashboard', 'admin-dashboard') ? 'active' : '' }}'><i class='fas fa-tachometer-alt'></i> Dashboard</a>
            <a href='{{ url('customers') }}' class='{{ request()->is('customers', 'trash-customers', 'create-customers', 'show-customers/*', 'edit-customers/*', 'delete-customers/*', 'customers-search*') ? 'active' : '' }}'><i class='fas fa-users'></i> Customers</a>
            <a href='{{ url('sites') }}' class='{{ request()->is('sites', 'trash-sites', 'create-sites', 'show-sites/*', 'edit-sites/*', 'delete-sites/*', 'sites-search*') ? 'active' : '' }}'><i class='fas fa-house'></i> Sites</a>
            {{-- <a href='#' class="cloud-based-systems-menu-toggle-button py-3" style="border-top: 1px solid #dcdcdc;border-bottom: 1px solid #dcdcdc;"><i class='fas fa-cloud'></i> Cloud-Based Systems</a>
            <div class="cloud-based-systems-menu-dropdown" style="display: none">
                <a href='{{ url('logs') }}' class='{{ request()->is('logs', 'create-logs', 'show-logs/*', 'edit-logs/*', 'delete-logs/*', 'logs-search*') ? 'active' : '' }}'><i class='fas fa-bars'></i> Coming Soon</a>
            </div>
            <a href='#' class="on-premise-systems-menu-toggle-button py-3" style="border-bottom: 1px solid #dcdcdc;"><i class='fas fa-cloud'></i> On-Premise Systems</a>
            <div class="on-premise-systems-menu-dropdown" style="display: none">
                <a href='{{ url('logs') }}' class='{{ request()->is('logs', 'create-logs', 'show-logs/*', 'edit-logs/*', 'delete-logs/*', 'logs-search*') ? 'active' : '' }}'><i class='fas fa-bars'></i> Coming Soon</a>
            </div> --}}
            <a href='{{ url('uploads') }}' class='{{ request()->is('uploads', 'trash-uploads', 'create-uploads', 'show-uploads/*', 'edit-uploads/*', 'delete-uploads/*', 'uploads-search*') ? 'active' : '' }}'><i class='fas fa-upload'></i> Data Uploads</a>
            <a href='{{ url('auditlogs') }}' class='{{ request()->is('auditlogs', 'create-auditlogs', 'show-auditlogs/*', 'edit-auditlogs/*', 'delete-auditlogs/*', 'auditlogs-search*') ? 'active' : '' }}'><i class="fas fa-clipboard-list"></i> Audit Logs</a>
            <a href='{{ url('logs') }}' class='{{ request()->is('logs', 'create-logs', 'show-logs/*', 'edit-logs/*', 'delete-logs/*', 'logs-search*') ? 'active' : '' }}'><i class="fas fa-bars"></i> System Logs</a>
            <a href='{{ url('user/profile') }}'><i class='fas fa-user'></i> {{ Auth::user()->name }}</a>
        </div>

        <!-- Top Navbar -->
        <nav class='navbar navbar-expand-lg navbar-dark'>
            <div class='container-fluid'>
                <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNav'
                    aria-controls='navbarNav' aria-expanded='false' aria-label='Toggle navigation' onclick='toggleSidebar()'>
                    <i class='fas fa-bars'></i>
                </button>
            </div>
        </nav>

        <x-main-notification />

        <div class='content'>
            @yield('content')
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        {{-- apex charts --}}

        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

        <!-- Bootstrap JS and dependencies -->
        <script src='{{ url('assets/bootstrap/bootstrap.bundle.min.js') }}'></script>

        <!-- Custom JavaScript -->
        <script src="{{ url('assets/custom/script.js') }}"></script>
        <script>
            function toggleSidebar() {
                document.getElementById('mobileSidebar').classList.toggle('active');
                document.getElementById('sidebar').classList.toggle('active');
            }

            // toggle menu

            $(document).ready(function () {
                // menu toggle

                $('.cloud-based-systems-menu-toggle-button').click(function () {
                    $('.cloud-based-systems-menu-dropdown').slideToggle()
                })

                $('.on-premise-systems-menu-toggle-button').click(function () {
                    $('.on-premise-systems-menu-dropdown').slideToggle()
                })
            })

            let lastFetchedData = null;  // Store last fetched data to compare and prevent unnecessary requests
            let isRequestInProgress = false;  // Prevent concurrent requests
            let retryCount = 0;  // Track how many retries we've made
            const maxRetries = 5;  // Maximum number of retries for failed requests
            let cachedDataVariable = 'usersData';
            let load = 0;

            // Utility function to compare two objects (shallow comparison for simplicity)
            function isEqual(a, b) {
                return JSON.stringify(a) === JSON.stringify(b);
            }

            // Get the current connection type to adjust polling frequency (e.g., '4g', '3g', '2g', 'slow-2g')
            function getConnectionType() {
                if (navigator.connection) {
                    return navigator.connection.effectiveType;  // Returns connection type
                }
                return 'unknown';  // Fallback for browsers without Network Information API
            }

            // Function to fetch data from the server
            function fetchData() {

                load++;

                if (isRequestInProgress) {
                    console.log('Skipping fetch as another request is still in progress.');
                    return;  // Prevent new fetch if a request is in progress
                }

                isRequestInProgress = true;  // Mark that a request is in progress

                fetch('/database-sync')  // Replace with your actual API endpoint
                    .then(response => response.json())
                    .then(data => {
                        if (!isEqual(data, lastFetchedData)) {
                            // console.log('Fetched new data:', data);
                            lastFetchedData = data;  // Update last fetched data

                            if (load != 1) {
                                // Check if the browser supports notifications
                                if ("Notification" in window) {
                                    // Function to request notification permission
                                    function requestNotificationPermission() {
                                        Notification.requestPermission().then(function(permission) {
                                            if (permission === "granted") {
                                                console.log("Notification permission granted.");
                                            } else {
                                                console.log("Notification permission denied.");
                                            }
                                        });
                                    }

                                    // Add event listener to button
                                    if (Notification.permission === "granted") {
                                        // Show a notification if permission is granted
                                        new Notification("Database Synced!", {
                                            body: "The database has been successfully synchronized!",
                                            icon: "assets/librify-logo.png",
                                        });
                                    } else {
                                        // Ask for permission if not granted yet
                                        requestNotificationPermission();
                                    }
                                } else {
                                    alert("Your browser does not support notifications.");
                                }
                                // end notification
                            }

                            localStorage.setItem(cachedDataVariable, JSON.stringify(data));  // Cache data in localStorage
                        } else {
                            // console.log('No new data to fetch.');
                        }
                        retryCount = 0;  // Reset retry count on success
                    })
                    .catch(error => {
                        console.error('API error:', error);
                        const cachedData = localStorage.getItem(cachedDataVariable);
                        if (cachedData) {
                            console.log('Using cached data:', JSON.parse(cachedData));  // Fallback to cached data
                        } else {
                            console.log('No cached data available.');
                        }

                        // Retry logic with exponential backoff if the request fails
                        if (retryCount < maxRetries) {
                            const delay = Math.pow(2, retryCount) * 1000;  // Exponential backoff (2^retryCount seconds)
                            console.log(`Retrying in ${delay / 1000} seconds...`);
                            setTimeout(fetchData, delay);  // Retry after delay
                            retryCount++;
                        } else {
                            console.log('Max retries reached, giving up.');
                        }
                    })
                    .finally(() => {
                        isRequestInProgress = false;
                        const pollingInterval = setPollingInterval();  // Get dynamic polling interval
                        setTimeout(fetchData, pollingInterval);  // Poll again after the calculated interval
                    });
            }

            // Function to dynamically adjust polling interval based on network type
            function setPollingInterval() {
                const connectionType = getConnectionType();
                let pollingInterval;

                switch (connectionType) {
                    case '4g':
                        pollingInterval = 5000;  // 5 seconds for fast connections
                        break;
                    case '3g':
                        pollingInterval = 10000;  // 10 seconds for moderate connections
                        break;
                    case '2g':
                    case 'slow-2g':
                        pollingInterval = 20000;  // 20 seconds for slow connections
                        break;
                    default:
                        pollingInterval = 10000;  // Default interval for unknown connections
                }

                // console.log(`Polling interval set to ${pollingInterval / 1000} seconds based on connection type: ${connectionType}`);
                return pollingInterval;  // Return calculated polling interval
            }

            // Handle online/offline events
            window.addEventListener('offline', () => {
                console.log('You are offline. Polling is paused.');
            });

            window.addEventListener('online', () => {
                console.log('You are back online. Fetching data...');
                fetchData();  // Try fetching data once the user is back online
            });

            // Start the polling process
            fetchData();
        </script>
    </body>
</html>
