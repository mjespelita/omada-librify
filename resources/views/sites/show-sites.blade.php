
@extends('layouts.main')

@section('content')
    <h1>{{ $item->name }}</h1>

    <!-- Sidebar for Desktop View -->
    <div class='sidebar' id='mobileSidebar'>
        <div class='logo'>
            <div class="p-3">
                <img src='{{ url('assets/librify-logo.png') }}' alt=''> <br>
            </div>
            <div class="p-3">
                <small>Powered by</small>
                <img src='{{ url('assets/logo.png') }}' alt='' style="width: 60px !important">
            </div>
        </div>

        <div class="p-2">
            <button type='button' class='btn btn-outline-secondary dropdown-toggle' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false' style="width: 100%;">
                {{ $item->name }}
            </button>
            <div class='dropdown-menu' style="width: 95%; border: 1px solid #212529">
                @forelse (App\Models\Sites::all() as $site)
                    <a class='dropdown-item bulk-move-to-trash' href='{{ url('/show-sites/'.$site->siteId) }}'><i class='fa fa-eye'></i> {{ $site->name }}</a>
                @empty
                    <p>No Sites</p>
                @endforelse
            </div>
        </div>

        <a href='{{ url('sites') }}'><i class='fas fa-arrow-left'></i> Back</a>
        <div class="p-2">
            <b class="text-secondary">Monitoring</b>
        </div>
        <a href='{{ url('show-sites/'.$item->id) }}' class='{{ request()->is('show-sites/*') ? 'active' : '' }}'><i class='fas fa-tachometer-alt'></i> Dashboard</a>
        <a href='{{ url('customers') }}' class='{{ request()->is('customers', 'trash-customers', 'create-customers', 'show-customers/*', 'edit-customers/*', 'delete-customers/*', 'customers-search*') ? 'active' : '' }}'>
            <i class="fas fa-line-chart"></i> Statistics
        </a>
        <a href='{{ url('customers') }}' class='{{ request()->is('customers', 'trash-customers', 'create-customers', 'show-customers/*', 'edit-customers/*', 'delete-customers/*', 'customers-search*') ? 'active' : '' }}'>
            <i class="fas fa-desktop"></i> Devices
        </a>
        <a href='{{ url('customers') }}' class='{{ request()->is('customers', 'trash-customers', 'create-customers', 'show-customers/*', 'edit-customers/*', 'delete-customers/*', 'customers-search*') ? 'active' : '' }}'>
            <i class="fas fa-users"></i> Clients
        </a>
        <a href='{{ url('customers') }}' class='{{ request()->is('customers', 'trash-customers', 'create-customers', 'show-customers/*', 'edit-customers/*', 'delete-customers/*', 'customers-search*') ? 'active' : '' }}'>
            <i class="fas fa-chart-line"></i> Insights
        </a>
        <a href='{{ url('customers') }}' class='{{ request()->is('customers', 'trash-customers', 'create-customers', 'show-customers/*', 'edit-customers/*', 'delete-customers/*', 'customers-search*') ? 'active' : '' }}'>
            <i class="fas fa-clipboard-list"></i> Logs
        </a>
        <a href='{{ url('customers') }}' class='{{ request()->is('customers', 'trash-customers', 'create-customers', 'show-customers/*', 'edit-customers/*', 'delete-customers/*', 'customers-search*') ? 'active' : '' }}'>
            <i class="fas fa-history"></i> Audit Logs
        </a>
        <a href='{{ url('customers') }}' class='{{ request()->is('customers', 'trash-customers', 'create-customers', 'show-customers/*', 'edit-customers/*', 'delete-customers/*', 'customers-search*') ? 'active' : '' }}'>
            <i class="fas fa-file-alt"></i> Reports
        </a>
        
        <div class="p-2">
            <b class="text-secondary">Tools</b>
        </div>
        
        <a href='{{ url('customers') }}' class='{{ request()->is('customers', 'trash-customers', 'create-customers', 'show-customers/*', 'edit-customers/*', 'delete-customers/*', 'customers-search*') ? 'active' : '' }}'>
            <i class="fas fa-map-marked-alt"></i> Map
        </a>
        <a href='{{ url('customers') }}' class='{{ request()->is('customers', 'trash-customers', 'create-customers', 'show-customers/*', 'edit-customers/*', 'delete-customers/*', 'customers-search*') ? 'active' : '' }}'>
            <i class="fas fa-cogs"></i> Network Tools
        </a>
        
        <div class="p-2">
            <b class="text-secondary">Configuration</b>
        </div>
        
        <a href='{{ url('customers') }}' class='{{ request()->is('customers', 'trash-customers', 'create-customers', 'show-customers/*', 'edit-customers/*', 'delete-customers/*', 'customers-search*') ? 'active' : '' }}'>
            <i class="fas fa-cogs"></i> Settings
        </a>
        <a href='{{ url('customers') }}' class='{{ request()->is('customers', 'trash-customers', 'create-customers', 'show-customers/*', 'edit-customers/*', 'delete-customers/*', 'customers-search*') ? 'active' : '' }}'>
            <i class="fas fa-wifi"></i> Hotspot
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <h5>WAN</h5>
                    <h1>{{ $item->wan }}</h1>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <h5>AP</h5>
                    <h1>{{ $item->connectedApNum }}</h1>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <h5>Switch</h5>
                    <h1>{{ $item->connectedSwitchNum }}</h1>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <h5>Isolated AP</h5>
                    <h1>{{ $item->connectedSwitchNum }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class='card'>
        <div class='card-body'>

            <div class='table-responsive'>
                <table class='table'>
                    <tr>
                        <th>ID</th>
                        <td>{{ $item->id }}</td>
                    </tr>
                    
        <tr>
            <th>Name</th>
            <td>{{ $item->name }}</td>
        </tr>
    
        <tr>
            <th>Site Id</th>
            <td>{{ $item->siteId }}</td>
        </tr>
    
        <tr>
            <th>Customer Id</th>
            <td>{{ $item->customerId }}</td>
        </tr>
    
        <tr>
            <th>Customer Name</th>
            <td>{{ $item->customerName }}</td>
        </tr>
    
        <tr>
            <th>Region</th>
            <td>{{ $item->region }}</td>
        </tr>
    
        <tr>
            <th>Timezone</th>
            <td>{{ $item->timezone }}</td>
        </tr>
    
        <tr>
            <th>Scenario</th>
            <td>{{ $item->scenario }}</td>
        </tr>
    
        <tr>
            <th>Wan</th>
            <td>{{ $item->wan }}</td>
        </tr>
    
        <tr>
            <th>Connected Ap Number</th>
            <td>{{ $item->connectedApNum }}</td>
        </tr>
    
        <tr>
            <th>Disconnected Ap Number</th>
            <td>{{ $item->disconnectedApNum }}</td>
        </tr>
    
        <tr>
            <th>Isolated AP Number</th>
            <td>{{ $item->isolatedApNum }}</td>
        </tr>
    
        <tr>
            <th>Connected Switch Number</th>
            <td>{{ $item->connectedSwitchNum }}</td>
        </tr>
    
        <tr>
            <th>Disconnected Switch Number</th>
            <td>{{ $item->disconnectedSwitchNum }}</td>
        </tr>
    
        <tr>
            <th>Type</th>
            <td>{{ $item->type }}</td>
        </tr>
    
                    <tr>
                        <th>Created At</th>
                        <td>{{ Smark\Smark\Dater::humanReadableDateWithDayAndTime($item->created_at) }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ Smark\Smark\Dater::humanReadableDateWithDayAndTime($item->updated_at) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <a href='{{ route('sites.index') }}' class='btn btn-primary'>Back to List</a>
@endsection
