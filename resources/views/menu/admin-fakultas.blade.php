<li class="sidebar-item  {{ request()->is('admin-departmen') ? 'active' : '' }}">
    <a href="{{ url('admin-departmen') }}" class="sidebar-link">
        <i class="bi bi-grid-fill"></i>
        <span>Admin Departemen</span>
    </a>

</li>

<li class="sidebar-item  {{ request()->is('utusan-departmen') ? 'active' : '' }}">
    <a href="{{ url('utusan-departmen') }}" class="sidebar-link">
        <i class="bi bi-grid-fill"></i>
        <span>Utusan Departemen</span>
    </a>
</li>
<li class="sidebar-item  {{ request()->is('utusan-fakultas') ? 'active' : '' }}">
    <a href="{{ url('utusan-fakultas') }}" class="sidebar-link">
        <i class="bi bi-grid-fill"></i>
        <span>Utusan Fakultas</span>
    </a>
</li>
