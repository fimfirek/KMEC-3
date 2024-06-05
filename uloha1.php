<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>


<?php
require_once "connect.php";

$items_per_page = 10;

function get_pagination_data($conn, $sql, $items_per_page, $page) {
    $offset = ($page - 1) * $items_per_page;
    $sql .= " LIMIT $items_per_page OFFSET $offset";
    return $conn->query($sql);
}

function display_pagination($page, $total_pages, $request) {
    echo '<div class="pagination">';
    if ($page > 1) {
        echo '<a href="?request=' . $request . '&page=' . ($page - 1) . '">&laquo;</a>';
    }
    for ($i = 1; $i <= $total_pages; $i++) {
        echo '<a href="?request=' . $request . '&page=' . $i . '" class="' . ($page == $i ? 'active' : '') . '">' . $i . '</a>';
    }
    if ($page < $total_pages) {
        echo '<a href="?request=' . $request . '&page=' . ($page + 1) . '">&raquo;</a>';
    }
    echo '</div>';
}

$request = isset($_GET['request']) ? $_GET['request'] : 'customers';
$page = isset($_GET['page']) ? $_GET['page'] : 1;

$total_customers = $conn->query("SELECT COUNT(*) AS total FROM customers")->fetch_assoc()['total'];
$total_orders = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
$total_suppliers = $conn->query("SELECT COUNT(*) AS total FROM suppliers")->fetch_assoc()['total'];

$total_pages_customers = ceil($total_customers / $items_per_page);
$total_pages_orders = ceil($total_orders / $items_per_page);
$total_pages_suppliers = ceil($total_suppliers / $items_per_page);

switch ($request) {
    case 'orders':
        $result = get_pagination_data($conn, "SELECT * FROM orders", $items_per_page, $page);
        $total_pages = $total_pages_orders;
        break;
    case 'suppliers':
        $result = get_pagination_data($conn, "SELECT * FROM suppliers", $items_per_page, $page);
        $total_pages = $total_pages_suppliers;
        break;
    case 'customers':
    default:
        $result = get_pagination_data($conn, "SELECT * FROM customers", $items_per_page, $page);
        $total_pages = $total_pages_customers;
        break;
}

$sql_customers = "SELECT * FROM customers ORDER BY Country, CompanyName";
$result_customers = $conn->query($sql_customers);

$sql_orders = "SELECT * FROM orders ORDER BY OrderDate";
$result_orders = $conn->query($sql_orders);

$sql_orders_1997 = "SELECT COUNT(*) as total_orders FROM orders WHERE YEAR(OrderDate) = 1997";
$result_orders_1997 = $conn->query($sql_orders_1997);
$row_orders_1997 = $result_orders_1997->fetch_assoc();

$sql_suppliers_manager = "SELECT ContactName FROM suppliers WHERE ContactTitle LIKE '%Manager%' ORDER BY ContactName";
$result_suppliers_manager = $conn->query($sql_suppliers_manager);

$sql_orders_specific_date = "SELECT * FROM orders WHERE OrderDate = '1997-05-19'";
$result_orders_specific_date = $conn->query($sql_orders_specific_date);
?>

    <h1>požiadavka 01</h1>
    <?php if ($request == 'customers') { ?>
        <h2>Customers</h2>
        <table>
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
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
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
        <?php display_pagination($page, $total_pages, 'customers'); ?>
    <?php } ?>

    <?php if ($request == 'orders') { ?>
        <h2>Orders</h2>
        <table>
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
        <?php display_pagination($page, $total_pages, 'orders'); ?>
    <?php } ?>

    <?php if ($request == 'suppliers') { ?>
        <h2>Suppliers</h2>
        <table>
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
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
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
        <?php display_pagination($page, $total_pages, 'suppliers'); ?>
    <?php } ?>

    <h1>požiadavka 02</h1>
    <table>
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

    <h1>požiadavka 03</h1>
<table>
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
<?php
// Display pagination links
display_pagination($page, $total_pages_orders, 'orders');
?>

    <h1>požiadavka 04</h1>
    <p>Total Orders in 1997: <?php echo $row_orders_1997['total_orders']; ?></p>

    <h1>požiadavka 05</h1>
    <table>
        <tr>
            <th>ContactName</th>
        </tr>
        <?php
        if ($result_suppliers_manager->num_rows > 0) {
            while ($row = $result_suppliers_manager->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['ContactName']}</td>
                      </tr>";
            }
        }
        ?>
    </table>

    <h1>požiadavka 06</h1>
    <table>
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
        if ($result_orders_specific_date->num_rows > 0) {
            while ($row = $result_orders_specific_date->fetch_assoc()) {
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