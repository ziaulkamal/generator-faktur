<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>ASA CELLULAR Pay</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f2f4f8;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2d3436;
        }

        label {
            font-weight: 600;
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            color: #2d3436;
        }

        input {
            width: 100%;
            padding: 10px 14px;
            border-radius: 8px;
            border: 1px solid #dcdde1;
            font-size: 15px;
            outline: none;
        }

        input:focus {
            border-color: #0984e3;
            box-shadow: 0 0 0 2px rgba(9, 132, 227, 0.1);
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 25px;
            background-color: #0984e3;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: 0.2s ease-in-out;
        }

        button:hover {
            background-color: #74b9ff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Form ASA CELLULAR Pay</h2>
        <form action="{{ route('asa_cellular.store') }}" method="POST">
            @csrf

            <label for="tanggal">Tanggal Transaksi</label>
            <input type="date" name="tanggal" id="tanggal" required>

            <label for="no_hp">Nomor HP</label>
            <input type="text" name="no_hp" id="no_hp" required placeholder="Contoh: 0852xxxxxxx">

            <label for="nominal">Nominal (Rp)</label>
            <input type="number" name="nominal" id="nominal" required placeholder="Contoh: 100000">

            <button type="submit">Generate Struk</button>
        </form>
    </div>
</body>
</html>
