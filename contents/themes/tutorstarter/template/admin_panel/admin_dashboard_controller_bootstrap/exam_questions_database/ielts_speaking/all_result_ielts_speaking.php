<?php
/*
 * Template Name: All Ielts Speaking Results
 */

$servername = "localhost";
$username = "root";
$password = ""; // No password by default
$dbname = "wordpress";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get filter value from the form input
$idtest_filter = isset($_GET['idtest_filter']) ? $_GET['idtest_filter'] : '';
$username_filter = isset($_GET['username_filter']) ? $_GET['username_filter'] : '';

// Pagination logic
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
$offset = ($page - 1) * $limit; // Calculate offset

$total_sql = "SELECT COUNT(*) FROM save_user_result_ielts_speaking";
if ($idtest_filter) {
    $total_sql .= " WHERE idtest LIKE '%$idtest_filter%'"; // Apply filter to total count
}
if ($username_filter) {
    $total_sql .= " WHERE username LIKE '%$username_filter%'"; // Apply filter to total count
}
$total_result = $conn->query($total_sql);
$total_rows = $total_result->fetch_row()[0];
$total_pages = ceil($total_rows / $limit); // Calculate total pages



$sql = "SELECT * FROM save_user_result_ielts_speaking";
if ($idtest_filter) {
    $sql .= " WHERE idtest LIKE '%$idtest_filter%'"; // Apply filter to the SQL query
}
if ($username_filter) {
    $sql .= " WHERE username LIKE '%$username_filter%'"; // Apply filter to the SQL query
}
$sql .= " LIMIT $limit OFFSET $offset"; // Add pagination limits
$result = $conn->query($sql);




$sql2 = "SELECT * FROM ielts_speaking_test_list";
$result_tests = $conn->query($sql2);

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">


    <!-- Custom fonts for this template-->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

    <meta charset="UTF-8">
    <title>All Ielts Speaking Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script src="/wordpress/contents/themes/tutorstarter/ielts-reading-tookit/script_database_1.js"></script>

    
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
       
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

    <?php include('../../sidebar.php'); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

            <?php include('../../topbar.php'); ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                <h1>All Ielts Speaking Results</h1>

<!-- Filter form -->
<form method="GET" action="">
        <div class="mb-3">
            <label for="idtest_filter" class="form-label">Filter by ID Test:</label>
            <input type="text" name="idtest_filter" id="idtest_filter" class="form-control" value="<?php echo isset($_GET['idtest_filter']) ? $_GET['idtest_filter'] : ''; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="?" class="btn btn-secondary">Clear Filter</a>
</form>
    
<form method="GET" action="">
    <div class="mb-3">
        <label for="username_filter" class="form-label">Filter by Username:</label>
        <input type="text" name="username_filter" id="username_filter" class="form-control" value="<?php echo isset($_GET['username_filter']) ? $_GET['username_filter'] : ''; ?>">
    </div>
    <button type="submit" class="btn btn-primary">Filter</button>
    <a href="?" class="btn btn-secondary">Clear Filter</a>
</form>


<div class="container mt-4">
        <!-- Tab navigation -->
        <ul class="nav nav-tabs" id="testTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="full-test-tab" data-bs-toggle="tab" data-bs-target="#fullTest" type="button" role="tab" aria-controls="fullTest" aria-selected="true">Full Test</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="practice-tab" data-bs-toggle="tab" data-bs-target="#practice" type="button" role="tab" aria-controls="practice" aria-selected="false">Practice</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="any-content-tab" data-bs-toggle="tab" data-bs-target="#anyContent" type="button" role="tab" aria-controls="anyContent" aria-selected="false">Quick Stats</button>
            </li>
        </ul>

        <!-- Tab content -->
        <div class="tab-content" id="testTabContent">
            <!-- Full Test Tab -->
            <div class="tab-pane fade show active" id="fullTest" role="tabpanel" aria-labelledby="full-test-tab">
                <table class="table table-bordered mt-3">
                    <caption><strong>Full Test</strong></caption>
                    <tr>
                        <th>Number</th>
                        <th>Username</th>
                        <th>Date</th>
                        <th>Test Name</th>
                        <th>Test ID</th>
                        <th>Test Type</th>
                        <th>resulttest</th>
                        <th>band_detail</th>
                        <th>Actions</th>
                    </tr>

                    <?php
                    if ($result->num_rows > 0) {
                        mysqli_data_seek($result, 0); // Reset pointer
                        while ($row = $result->fetch_assoc()) {
                            if ($row['test_type'] == 'Full Test') {
                                echo "<tr id='row_{$row['number']}'>
                                        <td>{$row['number']}</td>
                                        <td>{$row['username']}</td>
                                        <td>{$row['dateform']}</td>
                                        <td>{$row['testname']}</td>
                                        <td>{$row['idtest']}</td>
                                        <td>{$row['test_type']}</td>
                                        <td>{$row['resulttest']}</td>
                                        <td>{$row['band_detail']}</td>
                                        <td>
                                            <button class='btn btn-primary btn-sm' onclick='openEditModal({$row['number']})'>Edit</button>
                                            <button class='btn btn-danger btn-sm' onclick='deleteRecord({$row['number']})'>Delete</button>
                                        </td>
                                    </tr>";
                            }
                        }
                    } else {
                        echo "<tr><td colspan='10'>No data found</td></tr>";
                    }
                    ?>
                </table>
            </div>

            <!-- Practice Tab -->
            <div class="tab-pane fade" id="practice" role="tabpanel" aria-labelledby="practice-tab">
                <table class="table table-bordered mt-3">
                    <caption><strong>Practice</strong></caption>
                    <tr>
                    <th>Number</th>
                        <th>Username</th>
                        <th>Date</th>
                        <th>Test Name</th>
                        <th>Test ID</th>
                        <th>Test Type</th>
                        <th>resulttest</th>
                        <th>band_detail</th>
                        <th>Actions</th>
                    </tr>

                    <?php
                    if ($result->num_rows > 0) {
                        mysqli_data_seek($result, 0); // Reset pointer
                        while ($row = $result->fetch_assoc()) {
                            if ($row['test_type'] == 'Practice') {
                                echo "<tr id='row_{$row['number']}'>
                                <td>{$row['number']}</td>
                                <td>{$row['username']}</td>
                                <td>{$row['dateform']}</td>
                                <td>{$row['testname']}</td>
                                <td>{$row['idtest']}</td>
                                <td>{$row['test_type']}</td>
                                <td>{$row['resulttest']}</td>
                                <td>{$row['band_detail']}</td>
                                <td>
                                    <button class='btn btn-primary btn-sm' onclick='openEditModal({$row['number']})'>Edit</button>
                                    <button class='btn btn-danger btn-sm' onclick='deleteRecord({$row['number']})'>Delete</button>
                                </td>
                            </tr>";
                            }
                        }
                    } else {
                        echo "<tr><td colspan='10'>No data found</td></tr>";
                    }
                    ?>
                </table>
            </div>

            <!-- Any Content Tab -->
            <div class="tab-pane fade" id="anyContent" role="tabpanel" aria-labelledby="any-content-tab">
    <table class="table table-bordered mt-3">
        <h3>Tests Statistic</h3>
        <tr>
            <th>Number</th>
            <th>Test</th>
            <th>ID Test</th>
            <th>Test Type</th>
            <th>Average Point (Overall)</th>
            <th>Test Quantity</th>
            <th>Details</th>
        </tr>

        <?php
        if ($result_tests->num_rows > 0) {
            $number = 1;
            while ($row = $result_tests->fetch_assoc()) {
                $id_test = $row['id_test'];
                $test_type = $row['test_type'];

                // Truy vấn tính Average Point và Test Times
                $stats_sql = "SELECT 
                                AVG(resulttest) AS avg_point, 
                                COUNT(*) AS test_times 
                              FROM save_user_result_ielts_speaking 
                              WHERE idtest = '$id_test' AND test_type = 'Full Test'";

                $stats_result = $conn->query($stats_sql);
                $stats = $stats_result->fetch_assoc();

                $avg_point = $stats['avg_point'] ? round($stats['avg_point'], 2) : 0; // Làm tròn 2 chữ số
                $test_times = $stats['test_times'] ? $stats['test_times'] : 0;

                // Hiển thị thông tin trong bảng
                echo "<tr>
                        <td>{$number}</td>
                        <td>{$row['testname']}</td>
                        <td>{$row['id_test']}</td>
                        <td>{$row['test_type']}</td>
                        <td>{$avg_point}</td>
                        <td>{$test_times}</td>
                        <td>
                            <button class='btn btn-info btn-sm' onclick='openDetailsModal(\"{$row['id_test']}\")'>Details</button>
                        </td>
                    </tr>";


                $number++;
            }
        } else {
            echo "<tr><td colspan='7'>No data found</td></tr>";
        }
        ?>
    </table>
</div>


  <!-- Pagination buttons -->
  <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>&idtest_filter=<?php echo $idtest_filter; ?>">Previous</a></li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&idtest_filter=<?php echo $idtest_filter; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>&idtest_filter=<?php echo $idtest_filter; ?>">Next</a></li>
            <?php endif; ?>
        </ul>
    </nav>
<!-- Button to Add New Record -->
<button class="btn btn-success" onclick="openAddModal()">Add New Result</button>
<!-- View More Modal -->
<div class="modal" id="viewMoreModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewMoreTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewMoreContent">
                <!-- Full content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Edit Modal -->
<div class="modal" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title">Edit Result</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="edit_number" name="number">
                    
                    Username: <input type="text" id="edit_username" name="username" class="form-control" disabled><br>
                    Test Name: <input type="text" id="edit_testname" name="testname" class="form-control" required><br>
                    Test ID: <input type="text" id="edit_idtest" name="idtest" class="form-control" required><br>
                    Test Type: <input type="text" id="edit_test_type" name="test_type" class="form-control" required><br>
                    resulttest: <input type="text" id="edit_resulttest" name="resulttest" class="form-control" required><br>
                    band_detail: <input type="text" id="edit_band_detail" name="band_detail" class="form-control" required><br>
  
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="saveEdit()">Save Changes</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Add Modal -->
<div class="modal" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addForm">
                    Username: <input type="text" id="add_username" name="username" class="form-control" disabled><br>
                    Test Name: <input type="text" id="add_testname" name="testname" class="form-control" required><br>
                    Test ID: <input type="text" id="add_idtest" name="idtest" class="form-control" required><br>
                    Test Type: <input type="text" id="add_test_type" name="test_type" class="form-control" required><br>
                    resulttest: <input type="text" id="add_resulttest" name="resulttest" class="form-control" required><br>
                    band_detail: <input type="text" id="add_band_detail" name="band_detail" class="form-control" required><br>
  
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="saveNew()">Add Result</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">Test Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Test Type</th>
                            <th>Result Test</th>
                            <th>Band Details</th>                            
                            <th>Date</th>
                            <th>ID Result</th>
                        </tr>
                    </thead>
                    <tbody id="detailsTableBody">
                        <!-- Dynamic content here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <?php include('../../footer.php'); ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <!-- Bootstrap core JavaScript-->
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../../js/sb-admin-2.min.js"></script>

</body>


<!-- jQuery and JavaScript for AJAX -->
<script>
// Open the edit modal and populate it with data
function openEditModal(number) {
    $.ajax({
        url: 'http://localhost/wordpress/contents/themes/tutorstarter/template/admin_panel/result_admin_page/ielts_speaking_results/get_result.php', // Fetch the question details
        type: 'POST',
        data: { number: number },
        success: function(response) {
            var data = JSON.parse(response);
            $('#edit_number').val(data.number);
            $('#edit_username').val(data.username);
            $('#edit_testname').val(data.testname);
            $('#edit_idtest').val(data.idtest);
            $('#edit_test_type').val(data.test_type);
            $('#edit_resulttest').val(data.resulttest);
            $('#edit_band_detail').val(data.band_detail);
            $('#editModal').modal('show');
        }
    });
}


// Save the edited data
function saveEdit() {
    $.ajax({
        url: 'http://localhost/wordpress/contents/themes/tutorstarter/template/admin_panel/result_admin_page/ielts_speaking_results/update_result.php',
        type: 'POST',
        data: $('#editForm').serialize(),
        success: function(response) {
            location.reload(); // Reload the page to reflect changes
        }
    });
}
function openDetailsModal(idTest) {
    $.ajax({
        url: 'http://localhost/wordpress/contents/themes/tutorstarter/template/admin_panel/result_admin_page/ielts_speaking_results/get_test_details.php',
        method: 'POST',
        data: { id_test: idTest },
        success: function(response) {
            // Đổ dữ liệu vào bảng
            $('#detailsTableBody').html(response);
            // Mở modal
            $('#detailsModal').modal('show');
        },
        error: function() {
            alert('Failed to fetch test details.');
        }
    });
}


function openAddModal() {
    $('#addForm')[0].reset(); // Clear form data
    $('#addModal').modal('show'); // Show the modal
}

// Save the new question
function saveNew() {
    $.ajax({
        url: 'http://localhost/wordpress/contents/themes/tutorstarter/template/admin_panel/result_admin_page/ielts_speaking_results/add_result.php',
        type: 'POST',
        data: $('#addForm').serialize(),
        success: function(response) {
            location.reload(); // Reload to show the new record
        }
    });
}

// Delete a record
function deleteRecord(number) {
    if (confirm('Are you sure you want to delete this result?')) {
        $.ajax({
            url: 'http://localhost/wordpress/contents/themes/tutorstarter/template/admin_panel/result_admin_page/ielts_speaking_results/delete_result.php',
            type: 'POST',
            data: { number: number },
            success: function(response) {
                location.reload(); // Reload after deletion
            }
        });
    }
}
function showFullContent(title, content) {
    // Set the title of the modal
    $('#viewMoreTitle').text(title);

    // Set the content with HTML allowed
    $('#viewMoreContent').html(content);

    // Show the modal
    $('#viewMoreModal').modal('show');
}


</script>

</html>