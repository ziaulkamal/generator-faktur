<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form Dana Pay</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Roboto&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #f0f2f5;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            font-size: 16px;
        }
        label {
            margin-top: 15px;
            display: block;
            font-weight: bold;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Input Dana Pay Struk</h2>
    <form action="{{ route('dana_pay.store') }}" method="POST">
        @csrf
        <label>Tanggal</label>
        <input type="date" name="tgl" required>

        <label>Nomor Meter</label>
        <input type="text" name="no_meter" required>

        <label>Nama</label>
        <input type="text" name="nama" required>

        <label>Jumlah Bayar (Rp)</label>
        <input type="number" name="rp_bayar" required>

        <label>KWH</label>
        <input type="number" step="0.01" name="kwh" required>

        <button type="submit">Generate</button>
    </form>
</div>

</body>
</html>
