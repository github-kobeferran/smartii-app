

        <style>
            @import url(//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css);
            }
            @import url(https://fonts.googleapis.com/css?family=Titillium+Web:300);
            .fa-2x {
                font-size: 2em;
            }
            .sidebar-icon {
                position: relative;
                display: table-cell;
                width: 60px;
                height: 36px;
                text-align: center;
                vertical-align: middle;
                font-size:30px;
            }
            
            
            .main-menu:hover,nav.main-menu.expanded {
                width:250px;
                overflow:visible;
            }
            
            .main-menu {
                background:#e6c694;
                border-right:1px solid #e5e5e5;
                position:absolute;
                top:0;
                bottom:0;
                height:100%;
                left:0;
                width:60px;
                overflow:hidden;
                -webkit-transition:width .05s linear;
                transition:width .05s linear;
                -webkit-transform:translateZ(0) scale(1,1);
                z-index:1000;                           
            }
            
            .main-menu>ul {
                margin:10px 0;
            }
            
            .main-menu li {
            position:relative;
            padding-bottom: 10px;
            display:block;
            width:250px;
            }
            
            .main-menu li>a {
                position:relative;
                display:table;
                border-collapse:collapse;
                border-spacing:0;
                color:rgb(78, 76, 76);
                
                font-size: 20px;
                text-decoration:none;
                -webkit-transform:translateZ(0) scale(1,1);
                -webkit-transition:all .1s linear;
                transition:all .1s linear;              
            }
            
            .main-menu .nav-icon {
                position:relative;
                display:table-cell;
                width:60px;
                height:36px;
                text-align:center;
                vertical-align:middle;
                font-size:18px;
            }
            
            .main-menu .nav-text {
                position:relative;
                display:table-cell;
                vertical-align:middle;
                width:190px;
            }
            
            .main-menu>ul.logout {
                position:absolute;
                left:0;
                bottom:0;
            }
            
            .no-touch .scrollable.hover {
                overflow-y:hidden;
            }
            
            .no-touch .scrollable.hover:hover {
                overflow-y:auto;
                overflow:visible;   
            }
            
            a:hover,a:focus {
                text-decoration:none;
            }
            
            nav {
                -webkit-user-select:none;
                -moz-user-select:none;
                -ms-user-select:none;
                -o-user-select:none;
                user-select:none;
            }
            
            nav ul,nav li {
                outline:0;
                margin:0;
                padding:0;
            }
            .main-menu li:hover>a,nav.main-menu li.active>a,.dropdown-menu>li>a:hover,.dropdown-menu>li>a:focus,.dropdown-menu>.active>a,.dropdown-menu>.active>a:hover,.dropdown-menu>.active>a:focus,.no-touch .dashboard-page nav.dashboard-menu ul li:hover a,.dashboard-page nav.dashboard-menu ul li.active a {
                color:#fff;
                background-color:#04471680;
                
            }
           
            @font-face {              
              font-style: normal;
              font-weight: 300;
              src: local('Titillium WebLight'), local('TitilliumWeb-Light'), url(http://themes.googleusercontent.com/static/fonts/titilliumweb/v2/anMUvcNT0H1YN4FII8wpr24bNCNEoFTpS2BTjF6FB5E.woff) format('woff');
            }
            
</style>



<nav class="main-menu">
    <ul>

        <?php

        $admin = \App\Models\Admin::find(auth()->user()->member->member_id);

        ?>

        @if ($admin->position == 'superadmin')

            <li>
                <a href="/admin">
                    <i class="fa fa-map-o sidebar-icon" aria-hidden="true"></i>
                    <span class="nav-text">
                        Dashboard
                    </span>
                </a>
            
            </li>
            <li class="has-subnav">
                <a href="/admin/view">
                    <i class="fa fa-laptop fa-2x sidebar-icon"></i>
                    <span class="nav-text">
                        View
                    </span>
                </a>
                
            </li>
            <li class="has-subnav">
                <a href="{{ route('adminCreate') }}">
                    <i class="fa fa-pencil-square-o sidebar-icon" aria-hidden="true"></i>
                    <span class="nav-text">
                        Create
                    </span>
                </a>
                
            </li>
            <li class="has-subnav">
                <a href="/admin/classes">
                    <i class="fa fa-puzzle-piece sidebar-icon" aria-hidden="true"></i>
                    <span class="nav-text">
                        Classes
                    </span>
                </a>
                
            </li>
            <li class="has-subnav">
                <a href="/admin/payment">
                    <i class="fa fa-money sidebar-icon" aria-hidden="true"></i>
                    <span class="nav-text">
                    Payments
                    </span>
                </a>
            
            </li>
            <li class="has-subnav">
                @if(\App\Models\PaymentRequest::pendingRequestCount() > 0)
                    <span class="badge badge-danger rounded-circle">{{\App\Models\PaymentRequest::pendingRequestCount()}}</span>
                @endif
                <a href="/admin/paymentrequests/">
                    <i  class="fa fa-bell sidebar-icon" aria-hidden="true"></i>
                    <span class="nav-text">
                    Payment Requests
                    </span>                    
                </a>            
            
            </li>
            <li>
                <a href="/admin/settings">
                    <i class="fa fa-cogs sidebar-icon" aria-hidden="true"></i>
                    <span class="nav-text">
                        Settings
                    </span>
                </a>
            </li>

        @elseif($admin->position == 'registrar')

            <li class="has-subnav">
                <a href="/admin/view">
                    <i class="fa fa-laptop fa-2x sidebar-icon"></i>
                    <span class="nav-text">
                        View
                    </span>
                </a>
                
            </li>
            <li class="has-subnav">
                <a href="{{ route('adminCreate') }}">
                    <i class="fa fa-pencil-square-o sidebar-icon" aria-hidden="true"></i>
                    <span class="nav-text">
                        Create
                    </span>
                </a>
                
            </li>
            <li class="has-subnav">
                <a href="/admin/classes">
                    <i class="fa fa-puzzle-piece sidebar-icon" aria-hidden="true"></i>
                    <span class="nav-text">
                        Classes
                    </span>
                </a>
                
            </li>

        @elseif($admin->position == 'accounting')

            <li class="has-subnav">
                <a href="/admin/payment">
                    <i class="fa fa-money sidebar-icon" aria-hidden="true"></i>
                    <span class="nav-text">
                    Payments
                    </span>
                </a>
            
            </li>
            <li class="has-subnav">
                @if(\App\Models\PaymentRequest::pendingRequestCount() > 0)
                    <span class="badge badge-primary ">{{\App\Models\PaymentRequest::pendingRequestCount()}}</span>
                @endif
                <a href="/admin/paymentrequests/">
                    <i  class="fa fa-bell sidebar-icon" aria-hidden="true"></i>
                    <span class="nav-text">
                    Payment Requests
                    </span>
                    
                </a>            
            
            </li>
            
        @endif

       
       
    </ul>

   
</nav>



<script>


</script>


       