<?php require_once '../functions/register.function.php';

if($_POST){

    register($_POST);

}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simple Calculator</title>
    <style>
        body{font-family:Arial, sans-serif; margin:24px; justify-content: center; align-items: center; display: flex; min-height: 100vh;}
        input, select, button {padding:6px; margin:4px; border-radius: 8px;}
        form {display: flex; flex-direction: column; width: 300px; }
        button {margin-top: 15px;}
        .result {min-height: 30px; text-align: center;}
        .container {display: flex; flex-direction: column; align-items: center; gap: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="result">

    </div>
    <form method="post">
        <input type="number" step="any" name="num01" placeholder="Enter a number" required>
        <select name="operator" required>
            <option value="">--Choose--</option>
            <option value="add">+</option>
            <option value="subtract">-</option>
            <option value="multiply">*</option>
            <option value="divide">/</option>
        </select>
        <input type="number" step="any" name="num02" placeholder="Enter another number" required>
        <button type="submit">Calculate</button>
    </form>
</div>
</body>
</html>