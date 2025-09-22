<?php
include 'db.php';

// Add supplier
if (isset($_POST['add_supplier'])) {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $conn->query("INSERT INTO suppliers (name, contact, email, address) 
                  VALUES ('$name', '$contact', '$email', '$address')");
}

// Delete supplier
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM suppliers WHERE id=$id");
}

// Get suppliers
$result = $conn->query("SELECT * FROM suppliers");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Supplier Management</title>
</head>
<body>
    <h2>Add Supplier</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Supplier Name" required><br>
        <input type="text" name="contact" placeholder="Contact"><br>
        <input type="email" name="email" placeholder="Email"><br>
        <textarea name="address" placeholder="Address"></textarea><br>
        <button type="submit" name="add_supplier">Add Supplier</button>
    </form>

    <h2>Suppliers List</h2>
    <table border="1">
        <tr><th>Name</th><th>Contact</th><th>Email</th><th>Address</th><th>Actions</th></tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['name']; ?></td>
            <td><?= $row['contact']; ?></td>
            <td><?= $row['email']; ?></td>
            <td><?= $row['address']; ?></td>
            <td>
                <a href="transactions.php?supplier_id=<?= $row['id']; ?>">Transactions</a> | 
                <a href="?delete=<?= $row['id']; ?>" onclick="return confirm('Delete supplier?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
