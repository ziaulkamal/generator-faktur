<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Input Struk Geutanyo Cell</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .form-container {
            background: #fff;
            padding: 32px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            max-width: 400px;
            width: 100%;
        }

        h2 {
            text-align: center;
            margin-bottom: 24px;
            font-weight: 600;
            font-size: 22px;
            color: #111827;
        }

        label {
            font-weight: 500;
            display: block;
            margin-bottom: 6px;
            color: #374151;
        }

        input {
            width: 100%;
            padding: 10px 14px;
            margin-bottom: 20px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background-color: #f9fafb;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
            background-color: #fff;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #2563eb;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.25s ease;
        }

        button:hover {
            background-color: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Input Struk Listrik <br />Geutanyo Cell Fast Pay</h2>
        <form action="{{ route('geutanyo_cell.store') }}" method="POST">
            @csrf
            <label for="no_meter">No Meter</label>
            <input type="text" name="no_meter" required>

            <label for="tgl_bayar">Tanggal Bayar</label>
            <input type="date" name="tgl_bayar" required>

            <label for="id_pelanggan">ID Pelanggan</label>
            <input type="text" name="id_pelanggan" required>

            <label for="nama">Nama</label>
            <input type="text" name="nama" required>

            <label for="tarif_daya">Tarif / Daya</label>
            <input type="text" name="tarif_daya" required>

            <label for="rp_bayar">Rp Bayar</label>
            <input type="number" name="rp_bayar" required>

            <button type="submit">Generate Struk</button>
        </form>
    </div>
</body>
</html>
