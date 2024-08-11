<li class="sidebar-item  {{ request()->is('admin-fakultas') ? 'active' : '' }}">
    <a href="{{ url('admin-fakultas') }}" class="sidebar-link">
        <i class="bi bi-grid-fill"></i>
        <span>Admin Fakultas</span>
    </a>

</li>
<li class="sidebar-item  {{ request()->is('utusan-universitas') ? 'active' : '' }}">
    <a href="{{ url('utusan-universitas') }}" class="sidebar-link">
        <i class="bi bi-grid-fill"></i>
        <span>Utusan </span>
    </a>

</li>
<li class="sidebar-item  {{ request()->is('utusan-universitas') ? 'active' : '' }}">
    <a href="{{ url('utusan-universitas') }}" class="sidebar-link">
        <i class="bi bi-grid-fill"></i>
        <span>Calon utusan </span>
    </a>

</li>
<li
    class="sidebar-item  has-sub {{ request()->is('capaian-unggulan') || request()->is('bidang') || request()->is('kategori') || request()->is('fakultas') || request()->is('departmen') ? 'active' : '' }}">
    <a href="#" class="sidebar-link">
        <i class="bi bi-stack"></i>
        <span>Data</span>
    </a>

    <ul class="submenu submenu-closed {{ request()->is('capaian-unggulan') || request()->is('bidang') || request()->is('kategori') || request()->is('fakultas') || request()->is('departmen') ? 'active' : '' }}"
        style="--submenu-height: 731px;">

        <li class="submenu-item  {{ request()->is('fakultas') ? 'active' : '' }}">
            <a href="{{ url('fakultas') }}" class="submenu-link">Fakultas</a>

        </li>
        <li class="submenu-item  {{ request()->is('departmen') ? 'active' : '' }}">
            <a href="{{ url('departmen') }}" class="submenu-link">Departmen</a>

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
