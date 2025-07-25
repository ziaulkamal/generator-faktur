<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Input Struk Gold Cell</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #f0f0f0;
        }
        .form-container {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }
        label {
            margin-top: 15px;
            display: block;
        }
        input, button {
            width: 100%;
            padding: 8px;
            margin-top: 8px;
        }
        button {
            background: #222;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #000;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Input Struk Gold Cell</h2>
    <form action="{{ route('gold_cell.store') }}" method="POST">
        @csrf
        <label>Tanggal</label>
        <input type="date" name="tanggal" required>

        <label>ID Pelanggan</label>
        <input type="text" name="idpel" required>

        <label>Nama</label>
        <input type="text" name="nama" required>

        <label>Tarif / Daya</label>
        <input type="text" name="tarif_daya" required>

        <label>Nominal</label>
        <input type="number" name="nominal" required>

        <label>RP Token</label>
        <input type="number" name="rp_token" required>

        <label>Jumlah kWH</label>
        <input type="text" name="kwh" required>

        <label>Total Bayar</label>
        <input type="number" name="total_bayar" required>

        <label>Jasa Outlet</label>
        <input type="number" name="jasa_outlet" required>

        <label>Total Keseluruhan</label>
        <input type="number" name="total_final" required>

        <button type="submit">Generate</button>
    </form>
</div>
</body>
</html>
