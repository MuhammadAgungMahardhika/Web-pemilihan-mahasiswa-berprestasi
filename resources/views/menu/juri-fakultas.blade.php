<li class="sidebar-item  {{ request()->is('verifikasi-karya-ilmiah-fakultas') ? 'active' : '' }}">
    <a href="{{ url('verifikasi-karya-ilmiah-fakultas') }}" class="sidebar-link">
        <i class="bi bi-grid-fill"></i>
        <span>Verifikasi Karya Ilmiah</span>
    </a>
</li>
<li class="sidebar-item  {{ request()->is('uji-bahasa-inggris') ? 'active' : '' }}">
    <a href="{{ url('uji-bahasa-inggris') }}" class="sidebar-link">
        <i class="bi bi-grid-fill"></i>
        <span>Uji Bahasa Inggris</span>
    </a>
</li>
