
@extends('awal')
@section('content')
 <main class="main">
    

    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <div class="hero-container">
        <div class="hero-content">
          <h1>SPMB <br> SMK Bakti Nusantara 666</h1>
          <p>Sistem Penerimaan Murid Baru - Bergabunglah dengan SMK Bakti Nusantara 666 dan wujudkan masa depan cemerlang Anda bersama kami.</p>
          <div class="cta-buttons">
            @auth('pengguna')
              @php
                $userPendaftar = \App\Models\Pendaftar\Pendaftar::where('user_id', Auth::guard('pengguna')->id())->first();
              @endphp
              @if($userPendaftar && $userPendaftar->status == 'PAID')
                <a href="{{ route('pendaftar.status') }}" class="btn-apply">Lihat Status</a>
                <a href="{{ route('pendaftar.cetak-kartu') }}" class="btn-tour">Cetak Kartu</a>
              @else
                <a href="{{ route('pendaftar.pendaftaran') }}" class="btn-apply">Mulai Pendaftaran</a>
                <a href="{{ route('pendaftar.status') }}" class="btn-tour">Cek Status</a>
              @endif
            @else
              <a href="{{ route('pendaftar.register') }}" class="btn-apply">Daftar Akun</a>
              <a href="{{ route('login') }}" class="btn-tour">Login</a>
            @endauth
          </div>
          <div class="announcement">
            <div class="announcement-badge">Baru</div>
            <p>Pendaftaran Tahun Ajaran 2025/2026 Dibuka - Batas Akhir 30 Nov 2025</p>
          </div>
        </div>
      </div>

      <div class="highlights-container container">
        <div class="row gy-4">
          <div class="col-md-6">
            <div class="highlight-item">
              <div class="icon">
                <i class="bi bi-calendar-event"></i>
              </div>
              <h3>Jadwal Gelombang</h3>
              @php
                $allGelombang = \App\Models\Pendaftar\Gelombang::orderBy('tgl_mulai')->get();
                $today = now()->toDateString();
              @endphp
              @foreach($allGelombang as $gelombang)
                @php
                  $isActive = $gelombang->status === 'aktif';
                  $isPast = $gelombang->tgl_selesai < $today;
                  $isUpcoming = $gelombang->tgl_mulai > $today;
                  $statusClass = $isPast ? 'past' : ($isUpcoming ? 'upcoming' : ($isActive && !$isPast ? 'active' : 'inactive'));
                @endphp
                <div class="gelombang-item {{ $statusClass }}">
                  <strong>{{ $gelombang->nama }}</strong><br>
                  {{ \Carbon\Carbon::parse($gelombang->tgl_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($gelombang->tgl_selesai)->format('d M Y') }}<br>
                  Rp {{ number_format($gelombang->biaya_daftar, 0, ',', '.') }}
                  @if($isPast)
                    <span class="gelombang-status" style="color: #9e9e9e;">● SELESAI</span>
                  @elseif($isActive && !$isPast && !$isUpcoming)
                    <span class="gelombang-status">● AKTIF</span>
                  @elseif($isUpcoming)
                    <span class="gelombang-status" style="color: #ff9800;">● AKAN DATANG</span>
                  @else
                    <span class="gelombang-status" style="color: #9e9e9e;">● TIDAK AKTIF</span>
                  @endif
                </div>
              @endforeach
            </div>
          </div>
          <div class="col-md-6">
            <div class="highlight-item">
              <div class="icon">
                <i class="bi bi-people-fill"></i>
              </div>
              <h3>Kuota Setiap Jurusan</h3>
              @php
                $allJurusan = \App\Models\Pendaftar\Jurusan::all();
              @endphp
              @foreach($allJurusan as $jurusan)
                @php
                  $terisi = \App\Models\Pendaftar\Pendaftar::where('jurusan_id', $jurusan->id)
                      ->whereIn('status', ['ADM_PASS', 'PAYMENT_PENDING', 'PAID'])
                      ->count();
                  $sisaKuota = $jurusan->kuota - $terisi;
                  $persentase = $jurusan->kuota > 0 ? ($terisi / $jurusan->kuota) * 100 : 0;
                @endphp
                <div class="jurusan-item {{ $sisaKuota <= 0 ? 'full' : 'available' }}">
                  <strong>{{ $jurusan->nama }}</strong><br>
                  Sisa: {{ $sisaKuota }}/{{ $jurusan->kuota }} siswa
                  @if($sisaKuota <= 0) <span class="jurusan-status" style="color: #c62828;">● PENUH</span> @endif
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>

      @php
        $today = now()->toDateString();
        $gelombangAktif = \App\Models\Pendaftar\Gelombang::where('status', 'aktif')
            ->where('tgl_mulai', '<=', $today)
            ->where('tgl_selesai', '>=', $today)
            ->first();
      @endphp
      
      @if($gelombangAktif)
      <div class="event-banner">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-md-2">
              <div class="event-date">
                <span class="month">{{ $gelombangAktif->tgl_selesai->format('M') }}</span>
                <span class="day">{{ $gelombangAktif->tgl_selesai->format('d') }}</span>
              </div>
            </div>
            <div class="col-md-8">
              <h3>{{ $gelombangAktif->nama }}</h3>
              <p>Batas akhir pendaftaran {{ $gelombangAktif->nama }} sampai {{ $gelombangAktif->tgl_selesai->format('d F Y') }}. Biaya pendaftaran Rp {{ number_format($gelombangAktif->biaya_daftar, 0, ',', '.') }}</p>
            </div>
            <div class="col-md-2">
              @auth('pengguna')
                @if($userPendaftar && $userPendaftar->status == 'PAID')
                  <a href="{{ route('pendaftar.status') }}" class="btn-register">Status</a>
                @else
                  <a href="{{ route('pendaftar.pendaftaran') }}" class="btn-register">Daftar</a>
                @endif
              @else
                <a href="{{ route('pendaftar.register') }}" class="btn-register">Daftar</a>
              @endauth
            </div>
          </div>
        </div>
      </div>
      @endif

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

      <div class="container">

        <div class="row gy-5">

          <div class="col-lg-6">
            <div class="content">
              <h3>Sistem Penerimaan Murid Baru Dibuka!</h3>
              <p>SMK Bakti Nusantara 666 membuka kesempatan bagi calon siswa untuk bergabung dalam tahun ajaran 2025/2026. Proses pendaftaran mudah, cepat, dan transparan dengan berbagai jalur masuk yang tersedia.</p>

              @php
                $totalKuota = \App\Models\Pendaftar\Jurusan::sum('kuota');
                $jumlahJurusan = \App\Models\Pendaftar\Jurusan::count();
                $jumlahGelombang = \App\Models\Pendaftar\Gelombang::count();
              @endphp
              <div class="stats-row">
                <div class="stat-item">
                  <div class="number">{{ $totalKuota }}</div>
                  <div class="label">Kuota Tersedia</div>
                </div>
                <div class="stat-item">
                  <div class="number">{{ $jumlahJurusan }}</div>
                  <div class="label">Program Keahlian</div>
                </div>
                <div class="stat-item">
                  <div class="number">{{ $jumlahGelombang }}</div>
                  <div class="label">Gelombang</div>
                </div>
              </div>

              <div class="mission-statement">
                <p><em>"Daftarkan diri Anda sekarang dan raih kesempatan menjadi bagian dari SMK terbaik dengan fasilitas modern dan program keahlian yang sesuai dengan kebutuhan industri."</em></p>
              </div>

              @auth('pengguna')
                @if($userPendaftar && $userPendaftar->status == 'PAID')
                  <a href="{{ route('pendaftar.status') }}" class="btn-learn-more">
                    Lihat Status
                    <i class="bi bi-arrow-right"></i>
                  </a>
                @else
                  <a href="{{ route('pendaftar.pendaftaran') }}" class="btn-learn-more">
                    Mulai Pendaftaran
                    <i class="bi bi-arrow-right"></i>
                  </a>
                @endif
              @else
                <a href="{{ route('pendaftar.register') }}" class="btn-learn-more">
                  Daftar Akun
                  <i class="bi bi-arrow-right"></i>
                </a>
              @endauth
            </div>
          </div>

          <div class="col-lg-6">
            <div class="image-wrapper">
              <img src="{{ asset('assets/img/baknus/poster-spmb.jpeg') }}" alt="SPMB Poster" class="img-fluid">
              <div class="experience-badge">
                <div class="years">SPMB</div>
                <div class="text">2025/2026</div>
              </div>
            </div>
          </div>

        </div>

      </div>

    </section><!-- /About Section -->



    <!-- Featured Programs Section -->
    <section id="featured-programs" class="featured-programs section">

      <!-- Section Title -->
      <div class="container section-title">
        <h2>Program Keahlian SPMB 2025/2026</h2>
        <p>Pilih program keahlian yang sesuai dengan minat dan bakat Anda. Kuota terbatas, daftar sekarang!</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="featured-programs-wrapper">

          <div class="programs-overview">
            <div class="overview-content">
              <h2>Program Keahlian Berkualitas</h2>
              <p>SMK Bakti Nusantara 666 menawarkan berbagai program keahlian yang disesuaikan dengan kebutuhan industri dan perkembangan teknologi terkini.</p>
              <div class="overview-stats">
                <div class="stat-item">
                  <span class="stat-number">{{ $totalKuota }}</span>
                  <span class="stat-label">Total Kuota</span>
                </div>
                <div class="stat-item">
                  <span class="stat-number">{{ \App\Models\Pendaftar\Pendaftar::count() }}</span>
                  <span class="stat-label">Pendaftar</span>
                </div>
                <div class="stat-item">
                  <span class="stat-number">{{ $jumlahJurusan }}</span>
                  <span class="stat-label">Program Keahlian</span>
                </div>
              </div>
            </div>
            <div class="overview-image">
              <img src="{{ asset('assets/img/baknus/bn1.webp') }}" alt="Education" class="img-fluid">
            </div>
          </div>

          <div class="programs-showcase">

            @php
              $jurusanList = \App\Models\Pendaftar\Jurusan::all();
              $featuredJurusan = $jurusanList->first();
              $otherJurusan = $jurusanList->skip(1);
            @endphp
            
            @if($featuredJurusan)
            <div class="program-card featured-program">
              <div class="card-image">
                <img src="{{ asset('assets/img/baknus/pplg.jpeg') }}" alt="Program" class="img-fluid">
                <div class="program-badge">
                  <i class="bi bi-star-fill"></i>
                  <span>Kuota {{ $featuredJurusan->kuota }}</span>
                </div>
              </div>
              

              
              <div class="card-content">
                <div class="program-category">{{ $featuredJurusan->kode }}</div>
                <h3>{{ $featuredJurusan->nama }}</h3>
                @php
                  $deskripsiJurusan = [
                    'PPLG' => 'Mempelajari pengembangan aplikasi, website, dan game dengan teknologi terkini.',
                    'DKV' => 'Mengembangkan kreativitas dalam desain grafis, branding, dan komunikasi visual.',
                    'AKT' => 'Menguasai sistem akuntansi, laporan keuangan, dan manajemen bisnis.',
                    'PM' => 'Mempelajari strategi pemasaran digital, manajemen produk, dan analisis pasar.',
                    'ANM' => 'Mengembangkan keterampilan animasi 2D/3D, motion graphics, dan multimedia.'
                  ];
                @endphp
                <p>{{ $deskripsiJurusan[$featuredJurusan->kode] ?? 'Program keahlian berkualitas dengan kuota ' . $featuredJurusan->kuota . ' siswa.' }}</p>
              </div>
            </div>


            
            @endif

            <div class="programs-list">
              @php
                $availableImages = ['dkv.jpeg', 'akt.jpeg', 'bdp.jpeg', 'anm.jpeg'];
              @endphp
              @foreach($otherJurusan as $index => $jurusan)
              <div class="program-item">
                <div class="item-visual">
                  <img src="{{ asset('assets/img/baknus/' . ($availableImages[$index] ?? 'dkv.jpeg')) }}" alt="Program" class="img-fluid">
                </div>
                <div class="item-details">
                  <div class="item-category">{{ $jurusan->kode }}</div>
                  <h4>{{ $jurusan->nama }}</h4>
                  @php
                    $deskripsiJurusan = [
                      'PPLG' => 'Mempelajari pengembangan aplikasi, website, dan game dengan teknologi terkini.',
                      'DKV' => 'Mengembangkan kreativitas dalam desain grafis, branding, dan komunikasi visual.',
                      'AKT' => 'Menguasai sistem akuntansi, laporan keuangan, dan manajemen bisnis.',
                      'PM' => 'Mempelajari strategi pemasaran digital, manajemen produk, dan analisis pasar.',
                      'ANM' => 'Mengembangkan keterampilan animasi 2D/3D, motion graphics, dan multimedia.'
                    ];
                  @endphp
                  <p>Kuota: {{ $jurusan->kuota }} siswa. {{ $deskripsiJurusan[$jurusan->kode] ?? 'Program keahlian berkualitas sesuai kebutuhan industri.' }}</p>
                </div>
              </div>
              @endforeach
            </div>

          </div>

        </div>

      </div>

    </section><!-- /Featured Programs Section -->

    <!-- Students Life Block Section -->
    <section id="students-life-block" class="students-life-block section">

      <!-- Section Title -->
      <div class="container section-title">
        <h2>Fasilitas Sekolah</h2>
        <p>Fasilitas lengkap dan modern untuk mendukung pembelajaran di SMK Bakti Nusantara 666</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row align-items-center g-5">
          <div class="col-lg-6">
            <div class="content-wrapper">
              <div class="section-tag">
                Fasilitas SPMB
              </div>
              <h2>Fasilitas Lengkap untuk Calon Siswa</h2>
              <p class="description">SMK Bakti Nusantara 666 menyediakan fasilitas modern dan lengkap untuk mendukung proses pembelajaran yang optimal.</p>

              <div class="stats-row">
                <div class="stat-item">
                  <span class="stat-number">15+</span>
                  <span class="stat-label">Laboratorium</span>
                </div>
                <div class="stat-item">
                  <span class="stat-number">30+</span>
                  <span class="stat-label">Ruang Kelas</span>
                </div>
              </div>

              <div class="action-links">
                <a href="#" class="primary-link">Lihat Fasilitas Lengkap</a>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="visual-grid">
              <div class="main-visual">
                <img src="{{ asset('assets/img/baknus/lab1.jpg') }}" alt="Campus Life" class="img-fluid">
                <div class="overlay-badge">
                  <i class="bi bi-heart-fill"></i>
                  <span>Fasilitas Modern</span>
                </div>
              </div>

              <div class="secondary-visuals">
                <div class="small-visual">
                  <img src="{{ asset('assets/img/baknus/lab3.webp') }}" alt="Student Activities" class="img-fluid">
                  <div class="visual-caption">
                    <span>Laboratorium</span>
                  </div>
                </div>

                <div class="small-visual">
                  <img src="{{ asset('assets/img/baknus/perpus.jpg') }}" alt="Academic Excellence" class="img-fluid">
                  <div class="visual-caption">
                    <span>Perpustakaan</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="highlights-section">
          <div class="row g-4">
            <div class="col-lg-4">
              <div class="highlight-card">
                <div class="highlight-image">
                  <img src="{{ asset('assets/img/baknus/lab-amimasi.webp') }}" alt="Leadership Programs" class="img-fluid">
                </div>
                <div class="highlight-content">
                  <h5>Laboratorium Komputer</h5>
                  <p>Laboratorium komputer modern dengan perangkat terbaru untuk mendukung pembelajaran teknologi</p>
                </div>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="highlight-card">
                <div class="highlight-image">
                  <img src="{{ asset('assets/img/baknus/lab-studio-desain.avif') }}" alt="Cultural Events" class="img-fluid">
                </div>
                <div class="highlight-content">
                  <h5>Studio Desain</h5>
                  <p>Studio kreatif lengkap untuk praktik desain grafis, multimedia, dan komunikasi visual</p>
                </div>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="highlight-card">
                <div class="highlight-image">
                  <img src="{{ asset('assets/img/baknus/perpusdigi.jpg') }}" alt="Innovation Hub" class="img-fluid">
                </div>
                <div class="highlight-content">
                  <h5>Perpustakaan Digital</h5>
                  <p>Perpustakaan modern dengan koleksi buku dan akses digital untuk mendukung pembelajaran</p>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Students Life Block Section -->

 

    <!-- Call To Action Section -->
    <section id="call-to-action" class="call-to-action section light-background">

      <div class="container">

        <div class="row align-items-center">

          <div class="col-lg-5">
            <div class="content-wrapper">
              <div class="badge">
                <i class="bi bi-mortarboard-fill"></i>
                <span>Pendidikan Berkualitas</span>
              </div>

              <h2>Daftar Sekarang di SMK Bakti Nusantara 666</h2>

              <p>Jangan lewatkan kesempatan bergabung dengan SMK terbaik. Pendaftaran SPMB 2025/2026 sudah dibuka dengan kuota terbatas.</p>

              <div class="highlight-stats">
                <div class="stat-group">
                  <div class="stat-item">
                    <span class="number purecounter" data-purecounter-start="0" data-purecounter-end="{{ $totalKuota }}" data-purecounter-duration="2">0</span>
                    <span class="label">Total Kuota</span>
                  </div>
                  <div class="stat-item">
                    <span class="number purecounter" data-purecounter-start="0" data-purecounter-end="{{ $jumlahJurusan }}" data-purecounter-duration="2">0</span>
                    <span class="label">Program Keahlian</span>
                  </div>
                </div>
              </div>

              <div class="action-buttons">
                @auth('pengguna')
                  @if($userPendaftar && $userPendaftar->status == 'PAID')
                    <a href="{{ route('pendaftar.status') }}" class="btn-primary">Lihat Status</a>
                    <a href="{{ route('pendaftar.cetak-kartu') }}" class="btn-secondary">
                      <span>Cetak Kartu</span>
                      <i class="bi bi-arrow-right"></i>
                    </a>
                  @else
                    <a href="{{ route('pendaftar.pendaftaran') }}" class="btn-primary">Mulai Pendaftaran</a>
                    <a href="{{ route('pendaftar.status') }}" class="btn-secondary">
                      <span>Cek Status</span>
                      <i class="bi bi-arrow-right"></i>
                    </a>
                  @endif
                @else
                  <a href="{{ route('pendaftar.register') }}" class="btn-primary">Daftar SPMB</a>
                  <a href="{{ route('login') }}" class="btn-secondary">
                    <span>Login</span>
                    <i class="bi bi-arrow-right"></i>
                  </a>
                @endauth
              </div>
            </div>
          </div>

          <div class="col-lg-7">
            <div class="visual-section">
              <div class="main-image-container">
                <img src="{{ asset('assets/img/baknus/lpng.jpeg') }}" alt="Students Learning" class="main-image">
                <div class="overlay-gradient"></div>
              </div>

              <div class="feature-cards">
                <div class="feature-card achievement">
                  <div class="icon">
                    <i class="bi bi-trophy-fill"></i>
                  </div>
                  <div class="content">
                    <h4>Sertifikat Kompetensi</h4>
                    <p>Sertifikat yang diakui industri</p>
                  </div>
                </div>

                <div class="feature-card flexibility">
                  <div class="icon">
                    <i class="bi bi-clock-fill"></i>
                  </div>
                  <div class="content">
                    <h4>Pembelajaran Fleksibel</h4>
                    <p>Metode pembelajaran yang adaptif</p>
                  </div>
                </div>

                <div class="feature-card community">
                  <div class="icon">
                    <i class="bi bi-people-fill"></i>
                  </div>
                  <div class="content">
                    <h4>Jaringan Alumni</h4>
                    <p>Terhubung dengan alumni sukses</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>

    </section><!-- /Call To Action Section -->


   

  </main>
    
@endsection

