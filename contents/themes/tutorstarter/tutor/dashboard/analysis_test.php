<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>

<div>
    <style>
        /* CSS */
        .button-12 {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 6px 14px;
            font-family: -apple-system, BlinkMacSystemFont, 'Roboto', sans-serif;
            border-radius: 6px;
            border: none;
            background: #6E6D70;
            box-shadow: 0px 0.5px 1px rgba(0, 0, 0, 0.1), inset 0px 0.5px 0.5px rgba(255, 255, 255, 0.5), 0px 0px 0px 0.5px rgba(0, 0, 0, 0.12);
            color: #DFDEDF;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
        }

        .button-12:focus {
            box-shadow: inset 0px 0.8px 0px -0.25px rgba(255, 255, 255, 0.2), 0px 0.5px 1px rgba(0, 0, 0, 0.1), 0px 0px 0px 3.5px rgba(58, 108, 217, 0.5);
            outline: 0;
        }
    </style>

<main>
<?php
if (is_user_logged_in()) {
    global $wpdb, $current_user;
    
    $current_username = $current_user->user_login;

    $tables = [
        ['table' => 'save_user_result_digital_sat', 'base_url' => 'http://localhost/wordpress/digitalsat/', 'result_column' => 'resulttest', 'type' => 'sat'],
        ['table' => 'save_user_result_ielts_reading', 'base_url' => 'http://localhost/wordpress/ielts/r/', 'result_column' => 'overallband', 'type' => 'ielts_reading'],
        ['table' => 'save_user_result_ielts_speaking', 'base_url' => 'http://localhost/wordpress/ielts/s/', 'result_column' => 'resulttest', 'type' => 'ielts_speaking'],
        ['table' => 'save_user_result_ielts_writing', 'base_url' => 'http://localhost/wordpress/ielts/w/', 'result_column' => 'band_score', 'type' => 'ielts_writing'],
        ['table' => 'save_user_result_ielts_listening', 'base_url' => 'http://localhost/wordpress/ielts/l/', 'result_column' => 'overallband', 'type' => 'ielts_listening']
    ];

    $all_results = [];
    
    foreach ($tables as $table_info) {
        $table_name = $table_info['table'];
        $base_url = $table_info['base_url'];
        $result_column = $table_info['result_column'];
        $test_type = $table_info['type'];

        $query = $wpdb->prepare("SELECT * FROM $table_name WHERE username = %s ORDER BY dateform DESC", $current_username);
        $results = $wpdb->get_results($query);

        foreach ($results as $row) {
            $all_results[] = [
                'username' => esc_html($row->username),
                'date' => esc_html($row->dateform),
                'testname' => esc_html($row->testname),
                'idtest' => esc_html($row->idtest),
                'result' => esc_html($row->$result_column),
                'testsavenumber' => esc_html($row->testsavenumber),
                'detail_url' => esc_url($base_url . 'result/' . $row->testsavenumber . '/'),
                'test_type' => $test_type
            ];
        }
    }

    echo '<script> var testResults = ' . json_encode($all_results) . ';</script>';
} else {
    echo 'Vui lòng đăng nhập để xem kết quả làm bài.';
}
?>

<!-- Tabs để chọn loại test -->
<div class="tab-container">
    <button class="tab active" data-filter="all">Tất cả</button>
    <button class="tab" data-filter="sat">Digital SAT</button>
    <button class="tab" data-filter="ielts_reading">IELTS Reading</button>
    <button class="tab" data-filter="ielts_speaking">IELTS Speaking</button>
    <button class="tab" data-filter="ielts_writing">IELTS Writing</button>
    <button class="tab" data-filter="ielts_listening">IELTS Listening</button>
</div>

<!-- Bảng kết quả -->
<div id="test-results">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Tài khoản</th>
                <th>Ngày làm bài</th>
                <th>Đề thi</th>
                <th>ID Đề thi</th>
                <th>Kết quả</th>
                <!--<th>ID Kết quả</th> -->
                <th>Xem chi tiết</th>
            </tr>
        </thead>
        <tbody id="results-table">
            <!-- Dữ liệu sẽ được render ở đây -->
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div id="pagination" class="pagination-container"></div>

<style>
    .tab-container {
        margin-bottom: 15px;
        display: flex;
        gap: 10px;
    }
    .tab {
        padding: 8px 12px;
        background-color: #f1f1f1;
        border: 1px solid #ccc;
        cursor: pointer;
    }
    .tab.active {
        background-color: #0073aa;
        color: white;
    }
    .pagination-container {
        text-align: center;
        margin-top: 20px;
    }
    .pagination-container button {
        padding: 8px 12px;
        margin: 5px;
        border: 1px solid #0073aa;
        background-color: white;
        cursor: pointer;
    }
    .pagination-container button.active {
        background-color: #0073aa;
        color: white;
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const resultsPerPage = 20;
    let currentPage = 1;
    let activeFilter = "all";

    function filterResults() {
        return testResults.filter(row => activeFilter === "all" || row.test_type === activeFilter);
    }

    function renderResults(page) {
        const startIndex = (page - 1) * resultsPerPage;
        const endIndex = startIndex + resultsPerPage;
        const filteredResults = filterResults();
        const resultsTable = document.getElementById("results-table");
        resultsTable.innerHTML = "";

        filteredResults.slice(startIndex, endIndex).forEach(row => {
            resultsTable.innerHTML += `
                <tr>
                    <td>${row.username}</td>
                    <td>${row.date}</td>
                    <td>${row.testname}</td>
                    <td>${row.idtest}</td>
                    <td>${row.result}</td>
                    <!--<td>${row.testsavenumber}</td> -->
                    <td><a href="${row.detail_url}" target="_blank">Xem chi tiết</a></td>
                </tr>
            `;
        });

        updatePagination(page, filteredResults.length);
    }

    function updatePagination(activePage, totalItems) {
        const totalPages = Math.ceil(totalItems / resultsPerPage);
        const paginationContainer = document.getElementById("pagination");
        paginationContainer.innerHTML = "";

        for (let i = 1; i <= totalPages; i++) {
            paginationContainer.innerHTML += `<button class="${i === activePage ? 'active' : ''}" onclick="changePage(${i})">${i}</button>`;
        }
    }

    window.changePage = function (page) {
        currentPage = page;
        renderResults(page);
    };

    document.querySelectorAll(".tab").forEach(tab => {
        tab.addEventListener("click", function () {
            document.querySelectorAll(".tab").forEach(t => t.classList.remove("active"));
            this.classList.add("active");
            activeFilter = this.getAttribute("data-filter");
            currentPage = 1;
            renderResults(currentPage);
        });
    });

    renderResults(currentPage);
});
</script>

    </main>
</div>