<link rel="stylesheet" type="text/css" href="style.css">
<?php
require_once "connect.php";

$results_per_page = 10; // Number of entries to show in a page

// Determine the total number of pages available
$sql = "SELECT COUNT(*) AS total_orders FROM orders";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_pages = ceil($row['total_orders'] / $results_per_page);

// Determine which page number visitor is currently on
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = (int)$_GET['page'];
} else {
    $page = 1; // Default to the first page
}

// Calculate the starting limit for the results on the displaying page
$start_limit = ($page - 1) * $results_per_page;

// Fetch the selected results from database 
$sql = "SELECT * FROM orders ORDER BY OrderDate LIMIT $start_limit, $results_per_page";
$result_orders = $conn->query($sql);

// Fetch customers and suppliers data
$sql_customers = "SELECT * FROM customers";
$sql_suppliers = "SELECT * FROM suppliers";

$result_customers = $conn->query($sql_customers);
$result_suppliers = $conn->query($sql_suppliers);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Database Output</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>požiadavka 01</h1>
    <h2>Customers</h2>
    <table border="1">
        <!-- Customer table headers -->
        <tr>
            <th>CustomerID</th>
            <th>CompanyName</th>
            <th>ContactName</th>
            <th>ContactTitle</th>
            <th>Address</th>
            <th>City</th>
            <th>Region</th>
            <th>PostalCode</th>
            <th>Country</th>
            <th>Phone</th>
            <th>Fax</th>
        </tr>
        <?php
        if ($result_customers->num_rows > 0) {
            while ($row = $result_customers->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['CustomerID']}</td>
                        <td>{$row['CompanyName']}</td>
                        <td>{$row['ContactName']}</td>
                        <td>{$row['ContactTitle']}</td>
                        <td>{$row['Address']}</td>
                        <td>{$row['City']}</td>
                        <td>{$row['Region']}</td>
                        <td>{$row['PostalCode']}</td>
                        <td>{$row['Country']}</td>
                        <td>{$row['Phone']}</td>
                        <td>{$row['Fax']}</td>
                      </tr>";
            }
        }
        ?>
    </table>

    <h2>Orders</h2>
    <table border="1">
        <!-- Orders table headers -->
        <tr>
            <th>OrderID</th>
            <th>CustomerID</th>
            <th>EmployeeID</th>
            <th>OrderDate</th>
            <th>RequiredDate</th>
            <th>ShippedDate</th>
            <th>ShipVia</th>
            <th>Freight</th>
            <th>ShipName</th>
            <th>ShipAddress</th>
            <th>ShipCity</th>
            <th>ShipRegion</th>
            <th>ShipPostalCode</th>
            <th>ShipCountry</th>
        </tr>
        <?php
        if ($result_orders->num_rows > 0) {
            while ($row = $result_orders->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['OrderID']}</td>
                        <td>{$row['CustomerID']}</td>
                        <td>{$row['EmployeeID']}</td>
                        <td>{$row['OrderDate']}</td>
                        <td>{$row['RequiredDate']}</td>
                        <td>{$row['ShippedDate']}</td>
                        <td>{$row['ShipVia']}</td>
                        <td>{$row['Freight']}</td>
                        <td>{$row['ShipName']}</td>
                        <td>{$row['ShipAddress']}</td>
                        <td>{$row['ShipCity']}</td>
                        <td>{$row['ShipRegion']}</td>
                        <td>{$row['ShipPostalCode']}</td>
                        <td>{$row['ShipCountry']}</td>
                      </tr>";
            }
        }
        ?>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        <?php
        $pagination_length = 5; // Number of pagination links to display
        $ellipsis = "...";

        if ($page > 1) {
            echo '<a href="?page=' . ($page - 1) . '">&laquo; Prev</a>';
        }

        if ($page > $pagination_length) {
            echo '<a href="?page=1">1</a>';
            echo '<span>' . $ellipsis . '</span>';
        }

        for ($i = max(1, $page - $pagination_length); $i <= min($total_pages, $page + $pagination_length); $i++) {
            if ($i == $page) {
                echo '<a class="active" href="?page=' . $i . '">' . $i . '</a>';
            } else {
                echo '<a href="?page=' . $i . '">' . $i . '</a>';
            }
        }

        if ($page < $total_pages - $pagination_length) {
            echo '<span>' . $ellipsis . '</span>';
            echo '<a href="?page=' . $total_pages . '">' . $total_pages . '</a>';
        }

        if ($page < $total_pages) {
            echo '<a href="?page=' . ($page + 1) . '">Next &raquo;</a>';
        }
        ?>
    </div>

    <h2>Suppliers</h2>
    <table border="1">
        <!-- Suppliers table headers -->
        <tr>
            <th>SupplierID</th>
            <th>CompanyName</th>
            <th>ContactName</th>
            <th>ContactTitle</th>
            <th>Address</th>
            <th>City</th>
            <th>Region</th>
            <th>PostalCode</th>
            <th>Country</th>
            <th>Phone</th>
            <th>Fax</th>
            <th>HomePage</th>
        </tr>
        <?php
        if ($result_suppliers->num_rows > 0) {
            while ($row = $result_suppliers->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['SupplierID']}</td>
                        <td>{$row['CompanyName']}</td>
                        <td>{$row['ContactName']}</td>
                        <td>{$row['ContactTitle']}</td>
                        <td>{$row['Address']}</td>
                        <td>{$row['City']}</td>
                        <td>{$row['Region']}</td>
                        <td>{$row['PostalCode']}</td>
                        <td>{$row['Country']}</td>
                        <td>{$row['Phone']}</td>
                        <td>{$row['Fax']}</td>
                        <td>{$row['HomePage']}</td>
                      </tr>";
            }
        }
        ?>
    </table>

    <?php
    // Fetch total orders in 1997 (corrected to 1995 as per original instruction)
    $sql = "SELECT COUNT(*) as total_orders FROM orders WHERE YEAR(OrderDate) = 1995";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    ?>
    <h1>požiadavka 04</h1>
    <p>Total Orders in 1995: <?php echo $row['total_orders']; ?></p>

    <?php
    // Fetch suppliers' contact names with 'Manager' in their title
    $sql = "SELECT ContactName FROM suppliers WHERE ContactTitle LIKE '%Manager%' ORDER BY ContactName";
    $result = $conn->query($sql);
    ?>
    <h1>požiadavka 05</h1>
    <table border="1">
        <tr>
            <th>ContactName</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['ContactName']}</td>
                      </tr>";
            }
        }
        ?>
    </table>

    <?php
    // Fetch orders from '1995-09-28'
    $sql = "SELECT * FROM orders WHERE OrderDate = '1995-09-28'";
    $result = $conn->query($sql);
    ?>
    <h1>požiadavka 06</h1>
    <table border="1">
        <tr>
            <th>OrderID</th>
            <th>CustomerID</th>
            <th>EmployeeID</th>
            <th>OrderDate</th>
            <th>RequiredDate</th>
            <th>ShippedDate</th>
            <th>ShipVia</th>
            <th>Freight</th>
            <th>ShipName</th>
            <th>ShipAddress</th>
            <th>ShipCity</th>
            <th>ShipRegion</th>
            <th>ShipPostalCode</th>
            <th>ShipCountry</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['OrderID']}</td>
                        <td>{$row['CustomerID']}</td>
                        <td>{$row['EmployeeID']}</td>
                        <td>{$row['OrderDate']}</td>
                        <td>{$row['RequiredDate']}</td>
                        <td>{$row['ShippedDate']}</td>
                        <td>{$row['ShipVia']}</td>
                        <td>{$row['Freight']}</td>
                        <td>{$row['ShipName']}</td>
                        <td>{$row['ShipAddress']}</td>
                        <td>{$row['ShipCity']}</td>
                        <td>{$row['ShipRegion']}</td>
                        <td>{$row['ShipPostalCode']}</td>
                        <td>{$row['ShipCountry']}</td>
                      </tr>";
            }
        }
        ?>
    </table>
</body>
</html>
