<?php
include 'db_conn.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Search</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <script src='main.js'></script>
</head>

<body onload="print()">
    <div class="container">
        <center>
            <h3 style="margin-top: 30px;">Monthly Report</h3>
            <hr>
        </center>
        <table id="manage-pay" class="table table-borderless table-hover">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Amount Paid</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM payments");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td>
                                <?php echo $row['id']; ?></a>
                            </td>
                            <td>
                                <?php echo $row['paid']; ?>
                            </td>
                            <td>
                                <?php echo $row['date_created']; ?>
                            </td>
                          
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                <tr>
                    <td colspan="4">No Data Found</td>
                </tr>
                <?php
                }
                ?>
        </table>
    </div>

    <?php
    $result = mysqli_query($conn, 'SELECT SUM(paid) AS value_sum FROM payments');
    $row = mysqli_fetch_assoc($result);
    $sum = $row['value_sum']; ?>
    <?php echo "Total amount paid: $sum </br></br>"; ?>

    <div class="container">
        <button type="" class="btn btn-info noprint" style="width: 100%"
            onclick="window.location.replace('month_report.php');">Cancel</button>
    </div>

</body>

</html>