<nav class="main-header navbar navbar-expand navbar-orange" role = "navigation">

  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" style="color:white"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <div class="navbar-custom-menu margin-responsivo">

      <ul class="navbar-nav ml-auto">

          <li class="nav-item dropdown">

              <a class="nav-link" data-toggle="dropdown" href="#" style="color: green !important">
                  <i class="far fa-bell" style="font-size: 20px;"></i>
                  <span class="badge badge-warning navbar-badge count-notify" style="color: white;font-size: 12px;"></span>
              </a>

              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                  <span class="dropdown-item dropdown-header"><span class="count-notify"></span>
                  Notificaciones
                  </span>

                  <div class="dropdown-divider"></div>

                <div id="content-notify">

                </div>

                <!-- <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a> -->
              </div>

          </li>

      </ul>

    </div>

  <div class="navbar-custom-menu ml-auto">

    <ul class="nav navbar-nav" style="margin: 0px;">

      <li class="dropdown user user-menu generic">

        <a href="#" class = "dropdown-toggle" data-toggle="dropdown"> 

            <img src="{{ Croppa::url(current_user('admin')->photo, 200,200) }}" class="user-image">                       
            <span class = "hidden-xs" style="color: white !important;">{{ Auth::guard('admin')->user()->email }}</span>

        </a>

        <ul class="dropdown-menu" >
    
          <li class="user-body">

            <div class = "pull-right">
              
              <a href="{{ route('admin.logout') }}" class="btn btn-default btn-flat">salir</a>
              
            </div>

          </li>

        </ul>
            
      </li>
               
    </ul>

  </div>

</nav>