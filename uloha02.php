<?php
require_once "connect.php";

// Set the number of items per page
$items_per_page = 10;

// Get the current page number from the URL, default to 1 if not set
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the offset for the SQL query
$offset = ($current_page - 1) * $items_per_page;

// Query to get the total number of records
$sql_total = "SELECT COUNT(*) as total FROM orders WHERE YEAR(orders.OrderDate) = 1996";
$result_total = $conn->query($sql_total);
$total_records = $result_total->fetch_assoc()['total'];

// Calculate the total number of pages
$total_pages = ceil($total_records / $items_per_page);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Database Output</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<h1>požiadavka 01</h1>
    <table>
        <tr>
            <th>OrderID</th>
            <th>CustomerID</th>
            <th>CompanyName</th>
        </tr>
        <?php
        // SQL query to get the current page of records
        $sql_1996_orders = "SELECT orders.OrderID, customers.CustomerID, customers.CompanyName 
                            FROM orders 
                            JOIN customers ON orders.CustomerID = customers.CustomerID 
                            WHERE YEAR(orders.OrderDate) = 1996
                            LIMIT $items_per_page OFFSET $offset";
        $result_1996_orders = $conn->query($sql_1996_orders);

        if ($result_1996_orders->num_rows > 0) {
            while ($row = $result_1996_orders->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['OrderID']}</td>
                        <td>{$row['CustomerID']}</td>
                        <td>{$row['CompanyName']}</td>
                      </tr>";
            }
        }
        ?>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        <?php
        // Display previous link if not on the first page
        if ($current_page > 1) {
            echo '<a href="?page=' . ($current_page - 1) . '">&laquo; Previous</a>';
        }

        // Display links for pages 1, 2, 3, 4, 5
        for ($page = 1; $page <= $total_pages && $page <= 5; $page++) {
            if ($page == $current_page) {
                echo '<a class="active">' . $page . '</a>';
            } else {
                echo '<a href="?page=' . $page . '">' . $page . '</a>';
            }
        }

        // Display next link if not on the last page
        if ($current_page < $total_pages) {
            echo '<a href="?page=' . ($current_page + 1) . '">Next &raquo;</a>';
        }
        ?>
    </div>

    <h1>požiadavka 02</h1>
    <table>
        <tr>
            <th>City</th>
            <th>Employees</th>
            <th>Customers</th>
        </tr>
        <?php
        $sql_employees_customers_city = "SELECT city, 
                                         (SELECT COUNT(*) FROM employees WHERE employees.City = e.City) AS Employees, 
                                         (SELECT COUNT(*) FROM customers WHERE customers.City = e.City) AS Customers 
                                         FROM employees e 
                                         GROUP BY city";
        $result_employees_customers_city = $conn->query($sql_employees_customers_city);

        if ($result_employees_customers_city->num_rows > 0) {
            while ($row = $result_employees_customers_city->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['city']}</td>
                        <td>{$row['Employees']}</td>
                        <td>{$row['Customers']}</td>
                      </tr>";
            }
        }
        ?>
    </table>

    <h1>požiadavka 03</h1>
    <table>
        <tr>
            <th>City</th>
            <th>Employees</th>
            <th>Customers</th>
        </tr>
        <?php
        $sql_customers_employees_city = "SELECT city, 
                                         (SELECT COUNT(*) FROM employees WHERE employees.City = c.City) AS Employees, 
                                         (SELECT COUNT(*) FROM customers WHERE customers.City = c.City) AS Customers 
                                         FROM customers c 
                                         GROUP BY city";
        $result_customers_employees_city = $conn->query($sql_customers_employees_city);

        if ($result_customers_employees_city->num_rows > 0) {
            while ($row = $result_customers_employees_city->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['city']}</td>
                        <td>{$row['Employees']}</td>
                        <td>{$row['Customers']}</td>
                      </tr>";
            }
        }
        ?>
    </table>

    <h1>požiadavka 04</h1>
    <table>
        <tr>
            <th>City</th>
            <th>Employees</th>
            <th>Customers</th>
        </tr>
        <?php
        $sql_employees_customers_all_cities = "SELECT city, 
                                               (SELECT COUNT(*) FROM employees WHERE employees.City = ec.City) AS Employees, 
                                               (SELECT COUNT(*) FROM customers WHERE customers.City = ec.City) AS Customers 
                                               FROM (
                                                   SELECT City FROM employees 
                                                   UNION 
                                                   SELECT City FROM customers
                                               ) ec 
                                               GROUP BY city";
        $result_employees_customers_all_cities = $conn->query($sql_employees_customers_all_cities);

        if ($result_employees_customers_all_cities->num_rows > 0) {
            while ($row = $result_employees_customers_all_cities->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['city']}</td>
                        <td>{$row['Employees']}</td>
                        <td>{$row['Customers']}</td>
                      </tr>";
            }
        }
        ?>
    </table>

    <h1>požiadavka 05</h1>
    <table>
        <tr>
            <th>OrderID</th>
            <th>EmployeeName</th>
        </tr>
        <?php
        $sql_late_orders = "SELECT orders.OrderID, CONCAT(employees.FirstName, ' ', employees.LastName) AS EmployeeName
                            FROM orders
                            JOIN employees ON orders.EmployeeID = employees.EmployeeID
                            WHERE orders.ShippedDate > orders.RequiredDate";
        $result_late_orders = $conn->query($sql_late_orders);

        if ($result_late_orders->num_rows > 0) {
            while ($row = $result_late_orders->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['OrderID']}</td>
                        <td>{$row['EmployeeName']}</td>
                      </tr>";
            }
        }
        ?>
    </table>

    <h1>požiadavka 06</h1>
    <table>
        <tr>
            <th>ProductID</th>
            <th>TotalQuantity</th>
        </tr>
        <?php
        $sql_low_quantity_products = "SELECT ProductID, SUM(Quantity) AS TotalQuantity
                                      FROM `order details`
                                      GROUP BY ProductID
                                      HAVING TotalQuantity < 200";
        $result_low_quantity_products = $conn->query($sql_low_quantity_products);

        if ($result_low_quantity_products->num_rows > 0) {
            while ($row = $result_low_quantity_products->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['ProductID']}</td>
                        <td>{$row['TotalQuantity']}</td>
                      </tr>";
            }
        }
        ?>
    </table>

    <h1>požiadavka 07</h1>
    <table>
        <tr>
            <th>CustomerID</th>
            <th>TotalOrders</th>
        </tr>
        <?php
        $sql_orders_per_customer = "SELECT CustomerID, COUNT(OrderID) AS TotalOrders
                                    FROM orders
                                    WHERE OrderDate > '1994-12-31'
                                    GROUP BY CustomerID
                                    HAVING TotalOrders > 15";
        $result_orders_per_customer = $conn->query($sql_orders_per_customer);

        if ($result_orders_per_customer->num_rows > 0) {
            while ($row = $result_orders_per_customer->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['CustomerID']}</td>
                        <td>{$row['TotalOrders']}</td>
                      </tr>";
            }
        }
        ?>
    </table>

</body>
</html>
