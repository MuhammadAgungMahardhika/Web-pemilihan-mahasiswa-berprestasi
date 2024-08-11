<li class="sidebar-item  {{ request()->is('mahasiswa') ? 'active' : '' }}">
    <a href="{{ url('mahasiswa') }}" class="sidebar-link">
        <i class="bi bi-grid-fill"></i>
        <span>Mahasiswa</span>
    </a>
</li>

<li class="sidebar-item  {{ request()->is('verifikasi-dokumen') ? 'active' : '' }}">
    <a href="{{ url('verifikasi-dokumen') }}" class="sidebar-link">
        <i class="bi bi-grid-fill"></i>
        <span>Verifikasi Dokumen</span>
    </a>
</li>
<li class="sidebar-item  {{ request()->is('ranking') ? 'active' : '' }}">
    <a href="{{ url('ranking') }}" class="sidebar-link">
        <i class="bi bi-grid-fill"></i>
        <span>Ranking</span>
    </a>
</li>
<li class="sidebar-item  {{ request()->is('utusan-departmen') ? 'active' : '' }}">
    <a href="{{ url('utusan-departmen') }}" class="sidebar-link">
        <i class="bi bi-grid-fill"></i>
        <span>Utusan Departmen</span>
    </a>
</li>
