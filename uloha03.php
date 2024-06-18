<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Úloha 03</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
require_once "connect.php";
?>

<h1>požiadavka 01</h1>
<?php
$sql = "
    SELECT SUM(od.UnitPrice * od.Quantity) as TotalRevenue
    FROM `order details` od
    JOIN orders o ON od.OrderID = o.OrderID
    WHERE YEAR(o.OrderDate) = 1994
";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
echo "Celkové príjmy v roku 1994: " . $row['TotalRevenue'] . " USD";
?>

<h1>požiadavka 02</h1>
<?php
$sql = "
    SELECT c.CustomerID, c.CompanyName, SUM(od.UnitPrice * od.Quantity) as TotalPaid
    FROM customers c
    JOIN orders o ON c.CustomerID = o.CustomerID
    JOIN `order details` od ON o.OrderID = od.OrderID
    GROUP BY c.CustomerID, c.CompanyName
";
$result = $conn->query($sql);
echo "<table>";
echo "<tr><th>Customer ID</th><th>Company Name</th><th>Total Paid</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>{$row['CustomerID']}</td><td>{$row['CompanyName']}</td><td>{$row['TotalPaid']}</td></tr>";
}
echo "</table>";
?>

<h1>požiadavka 03</h1>
<?php
$sql = "
    SELECT p.ProductID, p.ProductName, SUM(od.Quantity) as TotalSold
    FROM products p
    JOIN `order details` od ON p.ProductID = od.ProductID
    GROUP BY p.ProductID, p.ProductName
    ORDER BY TotalSold DESC
    LIMIT 10
";
$result = $conn->query($sql);
echo "<table>";
echo "<tr><th>Product ID</th><th>Product Name</th><th>Total Sold</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>{$row['ProductID']}</td><td>{$row['ProductName']}</td><td>{$row['TotalSold']}</td></tr>";
}
echo "</table>";
?>

<h1>požiadavka 04</h1>
<?php
$sql = "
    SELECT c.CustomerID, c.CompanyName, SUM(od.UnitPrice * od.Quantity) as TotalRevenue
    FROM customers c
    JOIN orders o ON c.CustomerID = o.CustomerID
    JOIN `order details` od ON o.OrderID = od.OrderID
    GROUP BY c.CustomerID, c.CompanyName
";
$result = $conn->query($sql);
echo "<table>";
echo "<tr><th>Customer ID</th><th>Company Name</th><th>Total Revenue</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>{$row['CustomerID']}</td><td>{$row['CompanyName']}</td><td>{$row['TotalRevenue']}</td></tr>";
}
echo "</table>";
?>

<h1>požiadavka 05</h1>
<?php
$sql = "
    SELECT c.CustomerID, c.CompanyName, SUM(od.UnitPrice * od.Quantity) as TotalPaid
    FROM customers c
    JOIN orders o ON c.CustomerID = o.CustomerID
    JOIN `order details` od ON o.OrderID = od.OrderID
    WHERE c.Country = 'UK'
    GROUP BY c.CustomerID, c.CompanyName
    HAVING TotalPaid > 1000
";
$result = $conn->query($sql);
echo "<table>";
echo "<tr><th>Customer ID</th><th>Company Name</th><th>Total Paid</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>{$row['CustomerID']}</td><td>{$row['CompanyName']}</td><td>{$row['TotalPaid']}</td></tr>";
}
echo "</table>";
?>

<h1>požiadavka 06</h1>
<?php
$sql = "
    SELECT c.CustomerID, c.CompanyName, c.Country,
           SUM(od.UnitPrice * od.Quantity) as TotalPaid,
           SUM(CASE WHEN YEAR(o.OrderDate) = 1995 THEN od.UnitPrice * od.Quantity ELSE 0 END) as TotalPaid1995
    FROM customers c
    JOIN orders o ON c.CustomerID = o.CustomerID
    JOIN `order details` od ON o.OrderID = od.OrderID
    GROUP BY c.CustomerID, c.CompanyName, c.Country
";
$result = $conn->query($sql);
echo "<table>";
echo "<tr><th>Customer ID</th><th>Company Name</th><th>Country</th><th>Total Paid</th><th>Total Paid in 1995</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>{$row['CustomerID']}</td><td>{$row['CompanyName']}</td><td>{$row['Country']}</td><td>{$row['TotalPaid']}</td><td>{$row['TotalPaid1995']}</td></tr>";
}
echo "</table>";
?>

<h1>požiadavka 07</h1>
<?php
$sql = "
    SELECT COUNT(DISTINCT CustomerID) as TotalCustomers
    FROM orders
";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
echo "Celkový počet zákazníkov: " . $row['TotalCustomers'];
?>

<h1>požiadavka 08</h1>
<?php
$sql = "
    SELECT COUNT(DISTINCT o.CustomerID) as TotalCustomers1997
    FROM orders o
    WHERE YEAR(o.OrderDate) = 1997
";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
echo "Celkový počet zákazníkov v roku 1997: " . $row['TotalCustomers1997'];
?>

<?php $conn->close(); ?>

</body>
</html>
