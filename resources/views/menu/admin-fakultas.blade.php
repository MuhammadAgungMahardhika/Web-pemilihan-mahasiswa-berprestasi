<li class="sidebar-item  {{ request()->is('admin-departmen') ? 'active' : '' }}">
    <a href="{{ url('admin-departmen') }}" class="sidebar-link">
        <i class="bi bi-grid-fill"></i>
        <span>Admin Departemen</span>
    </a>

</li>

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
    class="sidebar-item  has-sub {{ request()->is('capaian-unggulan') || request()->is('bidang') || request()->is('kategori') || request()->is('fakultas') || request()->is('departmen') ? 'active' : '' }}">
    <a href="#" class="sidebar-link">
        <i class="bi bi-stack"></i>
        <span>Data</span>
    </a>

    <ul class="submenu submenu-closed {{ request()->is('departmen') ? 'active' : '' }}"
        style="--submenu-height: 731px;">

        <li class="submenu-item  {{ request()->is('departmen') ? 'active' : '' }}">
            <a href="{{ url('departmen') }}" class="submenu-link">Departmen</a>

        </li>
    </ul>
</li>
