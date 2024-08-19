<li class="sidebar-item  {{ request()->is('portal') ? 'active' : '' }}">
    <a href="{{ url('portal') }}" class="sidebar-link">
        <i class="bi bi-grid-fill"></i>
        <span>Portal</span>
    </a>

</li>

<li class="sidebar-item  {{ request()->is('ranking-universitas') ? 'active' : '' }}">
    <a href="{{ url('ranking-universitas') }}" class="sidebar-link">
        <i class="bi bi-grid-fill"></i>
        <span>Ranking</span>
    </a>

</li>
<li class="sidebar-item  {{ request()->is('utusan-universitas') ? 'active' : '' }}">
    <a href="{{ url('utusan-universitas') }}" class="sidebar-link">
        <i class="bi bi-grid-fill"></i>
        <span>Utusan Universitas</span>
    </a>

</li>
<li
    class="sidebar-item  has-sub {{ request()->is('capaian-unggulan') || request()->is('bidang') || request()->is('kategori') || request()->is('fakultas') || request()->is('departmen') || request()->is('admin-fakultas') || request()->is('juri-universitas') ? 'active' : '' }}">
    <a href="#" class="sidebar-link">
        <i class="bi bi-stack"></i>
        <span>Data</span>
    </a>

    <ul
        class="submenu submenu-closed {{ request()->is('capaian-unggulan') || request()->is('bidang') || request()->is('kategori') || request()->is('fakultas') || request()->is('admin-fakultas') || request()->is('juri-universitas') ? 'active' : '' }}">

        <li class="submenu-item  {{ request()->is('fakultas') ? 'active' : '' }}">
            <a href="{{ url('fakultas') }}" class="submenu-link">Fakultas</a>

        </li>
        <li class="submenu-item  {{ request()->is('admin-fakultas') ? 'active' : '' }}">
            <a href="{{ url('admin-fakultas') }}" class="submenu-link">Admin Fakultas</a>

        </li>
        <li class="submenu-item  {{ request()->is('juri-universitas') ? 'active' : '' }}">
            <a href="{{ url('juri-universitas') }}" class="submenu-link">Juri Universitas</a>

        </li>

        <li class="submenu-item  {{ request()->is('capaian-unggulan') ? 'active' : '' }}">
            <a href="{{ url('capaian-unggulan') }}" class="submenu-link">Capaian Unggulan</a>

        </li>
        <li class="submenu-item  {{ request()->is('bidang') ? 'active' : '' }}">
            <a href="{{ url('bidang') }}" class="submenu-link">Bidang</a>

        </li>
        <li class="submenu-item {{ request()->is('kategori') ? 'active' : '' }} ">
            <a href="{{ url('kategori') }}" class="submenu-link">Kategori</a>

        </li>


    </ul>
</li>
