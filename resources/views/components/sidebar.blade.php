  <!-- Sidebar  data-background-color="dark"-->
  <div class="sidebar">
      <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header">
              <a href="/" class="logo text-white">
                  LU-QMS
              </a>
              <div class="nav-toggle">
                  <button class="btn btn-toggle toggle-sidebar">
                      <i class="gg-menu-right"></i>
                  </button>
                  <button class="btn btn-toggle sidenav-toggler">
                      <i class="gg-menu-left"></i>
                  </button>
              </div>
              <button class="topbar-toggler more">
                  <i class="gg-more-vertical-alt"></i>
              </button>
          </div>
          <!-- End Logo Header -->
      </div>
      <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
              <ul class="nav nav-secondary">
                  <li class="nav-item {{ request()->is('/') ? 'active' : '' }}">
                      {{-- <a data-bs-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="false"> --}}
                      <a href="/">
                          <i class="fas fa-home"></i>
                          <p>Home</p>
                          {{-- <span class="caret"></span> --}}
                      </a>
                  </li>

                  <li class="nav-section">
                      <span class="sidebar-mini-icon">
                          <i class="fa fa-ellipsis-h"></i>
                      </span>
                      <h4 class="text-section">Components</h4>
                  </li>

                  <li class="nav-item">
                      <a data-bs-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="false">
                          <i class="fas fa-th-large"></i>
                          <p>Window</p>
                          <span class="caret"></span>
                      </a>
                      <div class="collapse" id="dashboard">
                          <ul class="nav nav-collapse">
                              <li>
                                  <a href="/window" target="_blank">
                                      <i class="fas fa-th-list"></i>
                                      <p>Active Window</p>
                                  </a>
                              </li>
                              <li>
                                  @auth
                                      @php
                                          $isDeptHead = DB::table('dms_departments')
                                              ->where('dept_head', auth()->user()->p_id)
                                              ->exists();
                                      @endphp

                                      @if ($isDeptHead)
                                          <a href="/personnel">
                                              <i class="fas fa-users"></i>
                                              <p>Manage Window</p>
                                          </a>
                                      @endif
                                  @endauth

                              </li>
                          </ul>
                      </div>
                  </li>


                  {{-- <li class="nav-item {{ request()->is('window') ? 'active' : '' }}">
                      <a href="/window" target="_blank">
                          <i class="fas fa-th-list"></i>
                          <p>Active Window</p>
                      </a>
                  </li> --}}

                  @auth
                      @php
                          $isDeptHead = DB::table('dms_departments')
                              ->where('dept_head', auth()->user()->p_id)
                              ->exists();
                      @endphp

                      @if ($isDeptHead)
                          {{-- <li class="nav-item {{ request()->is('personnel') ? 'active' : '' }}">
                              <a href="/personnel">
                                  <i class="fas fa-users"></i>
                                  <p>Manage Window</p>
                              </a>
                          </li> --}}
                          <li class="nav-item {{ request()->is('logs') ? 'active' : '' }}">
                              <a href="/logs">
                                  <i class="fas fa-file"></i>
                                  <p>Logs</p>
                              </a>
                          </li>
                      @endif
                  @endauth
              </ul>
          </div>
      </div>
  </div>
  <!-- End Sidebar -->
