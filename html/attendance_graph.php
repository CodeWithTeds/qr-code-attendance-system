<?php
include('./conn/conn.php'); // Include the database connection file

try {
    // Query attendance data grouped by date
    $dailyQuery = $conn->query("SELECT attendance_date AS date, COUNT(*) AS total FROM tbl_attendance GROUP BY attendance_date");
    $dailyData = $dailyQuery->fetchAll(PDO::FETCH_ASSOC);

    // Prepare data for the bar chart and doughnut chart
    $dates = [];
    $totals = [];
    foreach ($dailyData as $row) {
        $dates[] = $row['date'];
        $totals[] = $row['total'];
    }

    // Query attendance data grouped by month
    $monthlyQuery = $conn->query("SELECT 
        DATE_FORMAT(attendance_date, '%Y-%m') AS month, 
        COUNT(*) AS total 
        FROM tbl_attendance 
        GROUP BY month 
        ORDER BY month ASC");
    $monthlyData = $monthlyQuery->fetchAll(PDO::FETCH_ASSOC);

    // Prepare data for the line chart
    $months = [];
    $monthlyTotals = [];
    foreach ($monthlyData as $row) {
        $months[] = $row['month'];
        $monthlyTotals[] = $row['total'];
    }
} catch (PDOException $e) {
    die("Error fetching attendance data: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Attendance Charts</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background-image: url('image/granby.jpg');
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            gap: 20px;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 50px;
        }
        .chart {
            padding: 2rem;
            border: 1px solid #f49131;
            border-radius: 1rem;
            background: #251c35;
            box-shadow: 0 0 16px rgba(0, 0, 0, 0.8);
        }
        .decorative-line {
            width: 80%;
            height: 3px;
            background: linear-gradient(to right, #ff7a18, #af002d, #319197);
            border-radius: 3px;
            margin: 20px auto;
        }
    </style>
</head>
<body>
    <!-- Decorative Line -->
    <div class="decorative-line"></div>

    <div class="container">
        <!-- Bar Chart -->
        <div class="chart">
            <canvas id="barchart" width="300" height="300"></canvas>
        </div>
        <!-- Doughnut Chart -->
        <div class="chart">
            <canvas id="doughnutchart" width="300" height="300"></canvas>
        </div>
    </div>

    <!-- Decorative Line -->
    <div class="decorative-line"></div>

    

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Dynamic data from PHP
        const dates = <?php echo json_encode($dates); ?>;
        const totals = <?php echo json_encode($totals); ?>;

        const months = <?php echo json_encode($months); ?>;
        const monthlyTotals = <?php echo json_encode($monthlyTotals); ?>;

        // Bar Chart
        const barCtx = document.getElementById('barchart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Daily Attendance Count',
                    data: totals,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Doughnut Chart
        const doughnutCtx = document.getElementById('doughnutchart').getContext('2d');
        new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Daily Attendance Distribution',
                    data: totals,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });

        // Line Chart
        const lineCtx = document.getElementById('linechart').getContext('2d');
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Monthly Attendance Count',
                    data: monthlyTotals,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>

