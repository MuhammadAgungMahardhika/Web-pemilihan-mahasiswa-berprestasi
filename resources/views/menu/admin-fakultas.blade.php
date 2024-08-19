<li class="sidebar-item  {{ request()->is('ranking-fakultas') ? 'active' : '' }}">
    <a href="{{ url('ranking-fakultas') }}" class="sidebar-link">
        <i class="bi bi-grid-fill"></i>
        <span>Ranking </span>
    </a>
</li>
<li class="sidebar-item  {{ request()->is('utusan-fakultas') ? 'active' : '' }}">
    <a href="{{ url('utusan-fakultas') }}" class="sidebar-link">
        <i class="bi bi-grid-fill"></i>
        <span>Utusan Fakultas</span>
    </a>
</li>
<li
    class="sidebar-item  has-sub {{ request()->is('departmen') || request()->is('admin-departmen') || request()->is('juri-fakultas') ? 'active' : '' }}">
    <a href="#" class="sidebar-link">
        <i class="bi bi-stack"></i>
        <span>Data</span>
    </a>
    <ul
        class="submenu submenu-closed {{ request()->is('departmen') || request()->is('admin-departmen') || request()->is('juri-fakultas') ? 'active' : '' }}">

        <li class="submenu-item  {{ request()->is('departmen') ? 'active' : '' }}">
            <a href="{{ url('departmen') }}" class="submenu-link">Departmen</a>
        </li>
        <li class="submenu-item  {{ request()->is('admin-departmen') ? 'active' : '' }}">
            <a href="{{ url('admin-departmen') }}" class="submenu-link">Admin Departemen</a>
        </li>
        <li class="submenu-item  {{ request()->is('juri-fakultas') ? 'active' : '' }}">
            <a href="{{ url('juri-fakultas') }}" class="submenu-link">Juri Fakultas</a>
        </li>

    </ul>
</li>
