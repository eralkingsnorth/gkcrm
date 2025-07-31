<!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav first-nav">
    <li class="nav-item">
      <a class="nav-link" href="{{ route('dashboard') }}">
        <i class="mdi mdi-home menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#client-hub" aria-expanded="false" aria-controls="client-hub">
        <i class="mdi mdi-account-multiple menu-icon"></i>
        <span class="menu-title">Client Hub</span>
        <i class="menu-arrow"></i>
        
      </a>
      <div class="collapse" id="client-hub">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{ route('clients.create') }}"> Create A New Client</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('clients.index') }}"> View Existing Clients </a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('clients.bulk-download') }}"> Bulk Download </a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('clients.deleted') }}"> Deleted Clients</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('clients.bulk-import') }}"> Bulk Import</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('clients.bulk-status-updater') }}"> Bulk Status Updater</a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#case-hub" aria-expanded="false" aria-controls="case-hub">
        <i class="mdi mdi-folder-multiple menu-icon"></i>
        <span class="menu-title">Case Hub</span>
        <i class="menu-arrow"></i>
        
      </a>
      <div class="collapse" id="case-hub">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{ route('cases.create') }}"> Create A New Case</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('cases.index') }}"> View All Cases </a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('case-statuses.index') }}"> Case Statuses </a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#creditor-hub" aria-expanded="false" aria-controls="creditor-hub">
        <i class="mdi mdi-bank menu-icon"></i>
        <span class="menu-title">Creditors</span>
        <i class="menu-arrow"></i>
       
      </a>
      <div class="collapse" id="creditor-hub">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{ route('creditors.index') }}"> Manage Creditors</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('lead-sources.index') }}">
        <i class="mdi mdi-source-branch menu-icon"></i>
        <span class="menu-title">Lead Source</span>
      </a>
    </li>
  </ul>
</nav>
<!-- partial --> 