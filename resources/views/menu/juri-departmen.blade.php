<li class="sidebar-item  {{ request()->is('penilaian-karya-ilmiah-departmen') ? 'active' : '' }}">
    <a href="{{ url('penilaian-karya-ilmiah-departmen') }}" class="sidebar-link">
        <i class="bi bi-grid-fill"></i>
        <span>Penilaian Karya Ilmiah</span>
    </a>
</li>
<li class="sidebar-item  {{ request()->is('uji-bahasa-inggris-departmen') ? 'active' : '' }}">
    <a href="{{ url('uji-bahasa-inggris-departmen') }}" class="sidebar-link">
        <i class="bi bi-grid-fill"></i>
        <span>Uji Bahasa Inggris</span>
    </a>
</li>
