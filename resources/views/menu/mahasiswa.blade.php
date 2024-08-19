<li class="sidebar-item  {{ request()->is('dokumen-prestasi') ? 'active' : '' }}">
    <a href="{{ url('dokumen-prestasi') }}" class="sidebar-link">
        <i class="bi bi-grid-fill"></i>
        <span>Dokumen Prestasi</span>
    </a>

</li>
<li class="sidebar-item  {{ request()->is('karya-ilmiah') ? 'active' : '' }}">
    <a href="{{ url('karya-ilmiah') }}" class="sidebar-link">
        <i class="bi bi-grid-fill"></i>
        <span>Karya Ilmiah</span>
    </a>
</li>
<li class="sidebar-item  {{ request()->is('bahasa-inggris') ? 'active' : '' }}">
    <a href="{{ url('bahasa-inggris') }}" class="sidebar-link">
        <i class="bi bi-grid-fill"></i>
        <span>Bahasa Inggris</span>
    </a>
</li>
