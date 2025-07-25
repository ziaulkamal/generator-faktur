<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #fdfcfb 0%, #e2d1c3 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .menu-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .menu-title {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .brand-header {
            font-size: 2rem;
            font-weight: 700;
            color: #343a40;
        }

        .card-icon {
            font-size: 2.5rem;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="brand-header">📄 Sistem Cetak Struk</h1>
            <p class="text-muted">Pilih jenis struk yang ingin Anda proses</p>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

            <div class="col">
                <a href="{{ route('geutanyo_cell.create') }}" class="text-decoration-none">
                    <div class="card menu-card shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="card-icon text-primary mb-3">📱</div>
                            <div class="menu-title">Geutanyo Cell</div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="{{ route('dana_pay.create') }}" class="text-decoration-none">
                    <div class="card menu-card shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="card-icon text-success mb-3">💸</div>
                            <div class="menu-title">Dana Pay</div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="{{ route('payfazz.create') }}" class="text-decoration-none">
                    <div class="card menu-card shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="card-icon text-warning mb-3">🏧</div>
                            <div class="menu-title">Payfazz</div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="{{ route('gold_cell.create') }}" class="text-decoration-none">
                    <div class="card menu-card shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="card-icon text-danger mb-3">📶</div>
                            <div class="menu-title">Gold Cell</div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="{{ route('asa_cellular.form') }}" class="text-decoration-none">
                    <div class="card menu-card shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="card-icon text-info mb-3">📞</div>
                            <div class="menu-title">Asa Cellular</div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="{{ route('pesan.form') }}" class="text-decoration-none">
                    <div class="card menu-card shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="card-icon text-dark mb-3">✉️</div>
                            <div class="menu-title">Pesan Telkomsel</div>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
</body>
</html>
