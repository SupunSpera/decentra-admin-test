<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
        <div class="sidebar-brand-icon ">
            {{-- <img src="{{ asset('images/logo.png') }}"> --}}
        </div>
        {{-- <div class="mx-3 sidebar-brand-text">Events </div> --}}
    </a>

    <!-- Divider -->
    <hr class="my-0 sidebar-divider">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ in_array($curr_url, ['home']) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Pages Collapse Menu -->
    {{-- <li
        class="nav-item {{ in_array($curr_url, ['events.all', 'events.new', 'events.edit', 'location.index', 'meta.index', 'social.index', 'event.date', 'contact.index', 'album.index', 'tickets.all', 'tickets.new', 'tickets.edit']) ? 'active' : '' }}">
        <a class="nav-link " href="{{ route('events.all') }}">
            <i class="fas fa-fw fa-music"></i>
            <span>Events</span></a>
    </li> --}}
    {{-- <li class="nav-item {{ in_array($curr_url, ['users.all', 'users.new']) ? 'active' : '' }}">
        <a class="nav-link " href="{{ route('users.all') }}">
            <i class="fas fa-fw fa-music"></i>
            <span>Users</span></a>
    </li> --}}

    <li class="nav-item {{ in_array($curr_url, ['customers.all', 'customers.new']) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('customers.all') }}">
            <i class="fas fa-fw fa-user"></i>
            <span>Customers</span></a>
    </li>

    <li class="nav-item {{ in_array($curr_url, ['items.all', 'items.new']) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('items.all') }}">
            <i class="fa fa-shopping-cart"></i>
            <span>Items</span></a>
    </li>

    <li
        class="nav-item {{ in_array($curr_url, ['products.all', 'products.new', 'products.edit', 'products.terms']) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('products.all') }}">
            <i class="fas fa-fw fa-boxes"></i>
            <span>Products</span></a>
    </li>

    <li
        class="nav-item {{ in_array($curr_url, ['projects.all', 'projects.new', 'projects.edit', 'projects.updates', 'projects.terms']) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('projects.all') }}">
            <i class="fas fa-project-diagram"></i>
            <span>Projects</span></a>
    </li>

    <li class="nav-item {{ in_array($curr_url, ['milestones.all', 'milestones.new']) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('milestones.all') }}">
            <i class="fas fa-bullseye"></i>
            <span>Milestones</span></a>
    </li>

    <li class="nav-item {{ in_array($curr_url, ['gifts.all', 'gifts.new']) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('gifts.all') }}">
            <i class="fas fa-gift"></i>
            <span>Gifts</span></a>
    </li>

    <li class="nav-item {{ in_array($curr_url, ['referrals.tree']) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('referrals.tree') }}">
            <i class="fas fa-project-diagram"></i>
            <span>Referrals Tree</span></a>
    </li>

    {{-- <li class="nav-item {{ in_array($curr_url, ['supporting_bonus.all']) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('supporting_bonus.all') }}">
            <i class="fas fa-coins"></i>
            <span>Supporting Bonus</span></a>
    </li> --}}

    <li
        class="nav-item {{ in_array($curr_url, ['supporting_bonus.all', 'supporting_bonus.allocated_shares', 'supporting_bonus.generated_bonus']) ? 'active' : '' }}">
        <a class="nav-link  {{ in_array($curr_url, ['supporting_bonus.all', 'supporting_bonus.allocated_shares', 'supporting_bonus.generated_bonus']) ? '' : 'collapsed' }} "
            href="#" data-toggle="collapse" data-target="#collapseSupportingBonus" aria-expanded="true"
            aria-controls="collapseSupportingBonus">
            <i class="fas fa-coins"></i>
            <span>Supporting Bonus</span>
        </a>
        <div id="collapseSupportingBonus"
            class="collapse {{ in_array($curr_url, ['supporting_bonus.all', 'supporting_bonus.allocated_shares', 'supporting_bonus.generated_bonus']) ? 'show' : '' }} "
            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="py-2 bg-white rounded collapse-inner">

                <a class="collapse-item {{ in_array($curr_url, ['supporting_bonus.all']) ? 'active' : '' }}"
                    href="{{ route('supporting_bonus.all') }}">Supporting Bonus Pool</a>
                <a class="collapse-item {{ in_array($curr_url, ['supporting_bonus.allocated_shares']) ? 'active' : '' }}"
                    href="{{ route('supporting_bonus.allocated_shares') }}">Allocated Shares</a>
                <a class="collapse-item {{ in_array($curr_url, ['supporting_bonus.generated_bonus']) ? 'active' : '' }}"
                    href="{{ route('supporting_bonus.generated_bonus') }}">Generated Bonus</a>

                {{-- <a class="collapse-item" href="{{route('bonus_summery.report')}}">Bonus Report</a> --}}
            </div>
        </div>
    </li>

    <li class="nav-item {{ in_array($curr_url, ['institutional_bonus.all']) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('institutional_bonus.all') }}">
            <i class="fas fa-coins"></i>
            <span>Institutional Bonus Pool</span></a>
    </li>


    {{-- <li class="nav-item {{ in_array($curr_url, ['institutes.all']) ? 'active' : '' }}">
        <a class="nav-link {{ in_array($curr_url, ['institutes.all']) ? '' : 'collapsed' }} " href="#" data-toggle="collapse" data-target="#collapseInstitute"
            aria-expanded="true" aria-controls="collapseInstitute">
            <i class="far fa-building"></i>
            <span>Institutes</span>
        </a>
        <div id="collapseInstitute" class="collapse {{ in_array($curr_url, ['institutes.all']) ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="py-2 bg-white rounded collapse-inner">

                <a class="collapse-item {{ in_array($curr_url, ['institutes.all']) ? 'active' : '' }}" href="{{ route('institutes.all') }}">All</a>


            </div>
        </div>
    </li> --}}

    <li
        class="nav-item {{ in_array($curr_url, ['withdrawals.pending', 'withdrawals.approved', 'withdrawals.sent', 'withdrawals.rejected']) ? 'active' : '' }}">
        <a class="nav-link {{ in_array($curr_url, ['withdrawals.pending', 'withdrawals.approved', 'withdrawals.sent', 'withdrawals.rejected']) ? '' : 'collapsed' }} "
            href="#" data-toggle="collapse" data-target="#collapseWithdrawals" aria-expanded="true"
            aria-controls="collapseWithdrawals">
            <i class="fas fa-money-check"></i>
            <span>Withdrawals</span>
        </a>
        <div id="collapseWithdrawals"
            class="collapse {{ in_array($curr_url, ['withdrawals.pending', 'withdrawals.approved', 'withdrawals.sent', 'withdrawals.rejected']) ? 'show' : '' }}"
            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="py-2 bg-white rounded collapse-inner">
                <a class="collapse-item {{ in_array($curr_url, ['withdrawals.pending']) ? 'active' : '' }}"
                    href="{{ route('withdrawals.pending') }}">Pending Withdrawals </a>
                <a class="collapse-item {{ in_array($curr_url, ['withdrawals.approved']) ? 'active' : '' }}"
                    href="{{ route('withdrawals.approved') }}">Approved Withdrawals </a>
                <a class="collapse-item {{ in_array($curr_url, ['withdrawals.sent']) ? 'active' : '' }}"
                    href="{{ route('withdrawals.sent') }}">Sent Withdrawals </a>
                <a class="collapse-item {{ in_array($curr_url, ['withdrawals.rejected']) ? 'active' : '' }}"
                    href="{{ route('withdrawals.rejected') }}">Rejected Withdrawals </a>
            </div>
        </div>
    </li>
    <li class="nav-item {{ in_array($curr_url, ['urbx-withdrawals.pending']) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('urbx-withdrawals.pending') }}">
            <i class="fas fa-upload"></i>
            <span>Urbx Wallet Redeems</span></a>
    </li>
    <li class="nav-item {{ in_array($curr_url, ['customer_gifts.all']) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('customer_gifts.all') }}">
            <i class="fas fa-gift"></i>
            <span>Gift Purchases</span></a>
    </li>
    <li class="nav-item {{ in_array($curr_url, ['settings.all']) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('settings.all') }}">
            <i class="fas fa-cogs"></i>
            <span>Settings</span></a>
    </li>
    <li class="nav-item {{ in_array($curr_url, ['crypto-networks.all','crypto-networks.new','crypto-networks.edit']) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('crypto-networks.all') }}">
            <i class="fas fa-network-wired"></i>
            <span>Crypto Networks</span></a>
    </li>
    <li class="nav-item {{ in_array($curr_url, ['connected-projects.all','connected-projects.new','connected-projects.edit']) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('connected-projects.all') }}">
            <i class="fas fa-link"></i>
            <span>Connected Projects</span></a>
    </li>
    <li class="nav-item {{ in_array($curr_url, ['reports.index']) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('reports.index') }}">
            <i class="fas fa-newspaper"></i>
            <span>Reports</span></a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="border-0 rounded-circle" id="sidebarToggle"></button>
    </div>

</ul>
