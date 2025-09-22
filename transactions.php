<?php
include 'db.php';

$supplier_id = $_GET['supplier_id'];
$supplier = $conn->query("SELECT * FROM suppliers WHERE id=$supplier_id")->fetch_assoc();

// Add transaction
if (isset($_POST['add_transaction'])) {
    $amount = $_POST['amount'];
    $type = $_POST['type'];
    $mode = $_POST['mode'];
    $description = $_POST['description'];

    $conn->query("INSERT INTO transactions (supplier_id, amount, type, mode, description) 
                  VALUES ('$supplier_id', '$amount', '$type', '$mode', '$description')");
}

$transactions = $conn->query("SELECT * FROM transactions WHERE supplier_id=$supplier_id");

// Calculate balances
$total_paid = $conn->query("SELECT SUM(amount) AS total FROM transactions WHERE supplier_id=$supplier_id AND type='paid'")->fetch_assoc()['total'] ?? 0;
$total_due = $conn->query("SELECT SUM(amount) AS total FROM transactions WHERE supplier_id=$supplier_id AND type='due'")->fetch_assoc()['total'] ?? 0;
$balance = $total_due - $total_paid;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Transactions - <?= $supplier['name']; ?></title>
</head>
<body>
    <h2>Transactions with <?= $supplier['name']; ?></h2>
    <p><strong>Total Paid:</strong> <?= $total_paid; ?> | <strong>Total Due:</strong> <?= $total_due; ?> | <strong>Remaining:</strong> <?= $balance; ?></p>

    <form method="POST">
        <input type="number" step="0.01" name="amount" placeholder="Amount" required><br>
        <select name="type" required>
            <option value="paid">Paid</option>
            <option value="due">Due</option>
        </select><br>
        <select name="mode" required>
            <option value="money">Money</option>
            <option value="goods">Goods</option>
            <option value="services">Services</option>
        </select><br>
        <textarea name="description" placeholder="Transaction Description"></textarea><br>
        <button type="submit" name="add_transaction">Add Transaction</button>
    </form>

    <h3>Transaction History</h3>
    <table border="1">
        <tr><th>Amount</th><th>Type</th><th>Mode</th><th>Description</th><th>Date</th></tr>
        <?php while($row = $transactions->fetch_assoc()): ?>
        <tr>
            <td><?= $row['amount']; ?></td>
            <td><?= ucfirst($row['type']); ?></td>
            <td><?= ucfirst($row['mode']); ?></td>
            <td><?= $row['description']; ?></td>
            <td><?= $row['created_at']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <br><a href="suppliers.php">Back to Suppliers</a>
</body>
</html>
