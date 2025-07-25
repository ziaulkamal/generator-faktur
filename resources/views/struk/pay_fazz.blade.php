<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Input Struk Listrik ASA Cell Fast Pay</title>
    <style>
    html, body {
        height: auto;
        min-height: 100vh;
        margin: 0;
        padding: 0;
        background: #f7f7f7;
        font-family: 'Segoe UI', sans-serif;
    }

    body {
        display: block;
        padding: 40px 20px;
    }

    form {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
    }

    input, button {
        width: 100%;
        padding: 10px;
        margin-top: 8px;
        margin-bottom: 20px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 16px;
    }

    button {
        background-color: #1c87c9;
        color: white;
        border: none;
        cursor: pointer;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }
</style>

</head>
<body>
<form action="{{ route('payfazz.store') }}" method="POST">
    @csrf
    <h2>Input Struk Listrik <br />ASA Cell Fast Pay</h2>

    <label>Tanggal</label>
    <input type="date" name="tanggal" required>

    <label>Total</label>
    <input type="number" name="total" required>

    <label>No Meter</label>
    <input type="text" name="no_meter" required>

    <label>No Pelanggan</label>
    <input type="text" name="no_pelanggan" required>

    <label>Nama Pelanggan</label>
    <input type="text" name="nama" required>

    <label>Tarif / Daya</label>
    <input type="text" name="tarif_daya" required>

    <label>Jumlah kWh</label>
    <input type="number" step="0.01" name="kwh" required>

    <label>Rp Bayar</label>
    <input type="number" name="rp_bayar" required>

    <label>PPJ</label>
    <input type="number" step="0.01" name="ppj" required>

    <label>Rp Stroom / Token</label>
    <input type="number" name="rp_stroom" required>

    <label>Admin Bank</label>
    <input type="number" name="admin_bank" required>

    <button type="submit">Generate Struk</button>
</form>
</body>
</html>
