<?php

add_filter('document_title_parts', function ($title) {
 
        $title['title'] = sprintf('Test Library');
    
    return $title;
});


get_header();
// Define the post types with labels for the navigation
$test_tables = [
    'all' => '', // Leave blank for all tables
    'digitalsat' => 'digital_sat_test_list',
    'ieltsreadingtest' => 'ielts_reading_test_list',
    'thptqg' => 'thptqg_question',
    'ieltsspeakingtests' => 'ielts_speaking_test_list',
    'ieltslisteningtest' => 'ielts_listening_test_list',
    'conversation_ai' => 'conversation_with_ai_list',
    'studyvocabulary' => 'list_test_vocabulary_book',
    'thptqg' => 'thptqg_question',
    'ieltswritingtests' => 'ielts_writing_test_list',
    'dictation' => 'dictation_question',
    'shadowing' => 'shadowing_question',


];

// Get the current post type and search term
$current_post_type = get_query_var('search_url', 'all');
$search_term = $_GET['term'] ?? '';
global $wpdb;

// Determine the table to query
// Determine the table to query
$table_name = $test_tables[$current_post_type] ?? '';
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$limit = 12;
$offset = ($paged - 1) * $limit;

if (!empty($table_name)) {
    $query = $wpdb->prepare(
        "SELECT * FROM {$table_name} WHERE testname LIKE %s LIMIT %d OFFSET %d",
        '%' . $wpdb->esc_like($search_term) . '%',
        $limit,
        $offset
    );
    $results = $wpdb->get_results($query);
} else {
    $results = [];
}


// Display the navigation buttons
?>
<div class="post-type-navigation">
    <?php foreach ($test_tables as $type => $table) : ?>
        <a href="<?php echo esc_url(home_url("/tests/" . ($type === 'all' ? '' : $type))); ?>" 
           class="nav-button <?php echo $current_post_type === $type ? 'active' : ''; ?>">
            <?php echo esc_html($type === 'all' ? 'Tất cả' : ucfirst($type)); ?>
        </a>
    <?php endforeach; ?>
</div>


<!-- Search Form -->
<form method="get" action="<?php echo esc_url(home_url('/tests/' . ($current_post_type ? $current_post_type : ''))); ?>" class="search-form">
    <input type="text" name="term" placeholder="Nhập từ khóa..." value="<?php echo esc_attr($search_term); ?>" />
    <button type="submit">Tìm kiếm</button>
</form>

<?php
// Set up the query arguments
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = [
    'search_url' => $current_post_type !== 'all' ? $current_post_type : 'any',
    's' => $search_term,
    'posts_per_page' => 12,
    'paged' => $paged,
];

// Run the query
$query = new WP_Query($args);
?>

<div class="test-library">
    <?php if (!empty($results)) : ?>
        <div class="test-grid">
    <?php 
    // Lấy thông tin username hiện tại (ví dụ sử dụng hàm của WordPress)
    $current_user = wp_get_current_user();
    $username = $current_user->user_login;

    foreach ($results as $test) : 
        // Xác định bảng lưu kết quả và cột cần kiểm tra dựa trên loại bài kiểm tra
        $table_res_name = '';
        $check_column = '';
        if ($current_post_type === 'digitalsat') {
            $table_res_name = 'save_user_result_digital_sat';
            $check_column = 'resulttest'; // Cột cần kiểm tra
        } elseif ($current_post_type === 'ieltsreadingtests') {
            $table_res_name = 'save_user_result_ielts_reading';
            $check_column = 'overallband'; // Cột cần kiểm tra
        }

        // Kiểm tra kết quả trong bảng (dựa trên idtest và username)
        $completed = false;
        if (!empty($table_res_name) && !empty($username)) {
            $completed_query = $wpdb->prepare(
                "SELECT {$check_column} FROM {$table_res_name} 
                 WHERE {$check_column} IS NOT NULL AND idtest = %d AND username = %s",
                $test->id_test,
                $username
            );
            $completed = $wpdb->get_var($completed_query); // Kết quả trả về
        }
    ?>
        <div class="test-item">
            <h2><?php echo esc_html($test->testname); ?></h2>
            <div class="test-meta">
                <p>⏱️ <?php echo esc_html($test->duration ?? '60 phút'); ?></p>
                <p>📄 <?php echo esc_html($test->total_questions ?? 'Không rõ số câu'); ?></p>
            </div>
            <?php if ($completed) : ?>
                <div class="completed-icon">
                    <i class="fa-solid fa-check" style="color: #63E6BE;"></i>
                </div>
            <?php endif; ?>
            <a href="<?php echo esc_url(home_url("/{$current_post_type}/{$test->id_test}")); ?>" class="detail-button">Take Test</a>
        </div>
    <?php endforeach; ?>
</div>


        <!-- Pagination -->
        <div class="pagination">
            <?php
            $total_tests_query = $wpdb->prepare(
                "SELECT COUNT(*) FROM {$table_name} WHERE testname LIKE %s",
                '%' . $wpdb->esc_like($search_term) . '%'
            );
            $total_tests = $wpdb->get_var($total_tests_query);
            
            $total_pages = ceil($total_tests / $limit);

            echo paginate_links([
                'total' => $total_pages, // Số trang tính toán chính xác
                'current' => $paged,
                'format' => '?paged=%#%',
                'prev_text' => '&laquo; Prev',
                'next_text' => 'Next &raquo;',
                'add_args' => ['term' => $search_term],
            ]);
            ?>
        </div>
    <?php else : ?>
        <p>Không tìm thấy bài thi nào với từ khóa "<?php echo esc_html($search_term); ?>" cho loại bài thi "<?php echo ucfirst($current_post_type); ?>"</p>
    <?php endif; ?>

    <?php wp_reset_postdata(); ?>
</div>
<script>
    console.log('<?php echo esc_js($username); ?>');

</script>
<style>
    .post-type-navigation {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }
    .nav-button {
        padding: 8px 12px;
        border-radius: 5px;
        text-decoration: none;
        color: #333;
        background-color: #f1f1f1;
        transition: background-color 0.3s;
    }
    .nav-button.active {
        color: #fff;
        background-color: #0073aa;
    }
    .nav-button:hover {
        background-color: #ddd;
    }
    .test-library {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
}

.search-form {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.search-form input[type="text"] {
    flex: 1;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.search-form button {
    padding: 8px 16px;
    background-color: #0073aa;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.test-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.test-item {
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    text-align: center;
}

.test-item h2 {
    font-size: 18px;
    margin: 10px 0;
}

.test-meta p {
    margin: 5px 0;
    color: #666;
    font-size: 14px;
}

.test-tags span {
    display: inline-block;
    margin: 5px 5px 0 0;
    background-color: #e0e0e0;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 13px;
}

.detail-button {
    display: inline-block;
    margin-top: 10px;
    padding: 8px 12px;
    background-color: #0073aa;
    color: white;
    border-radius: 4px;
    text-decoration: none;
}

.detail-button:hover {
    background-color: #005a8c;
}

.pagination {
    margin-top: 20px;
    text-align: center;
}

.pagination a {
    padding: 5px 10px;
    color: #0073aa;
    text-decoration: none;
    margin: 0 5px;
}

.pagination a:hover {
    background-color: #0073aa;
    color: white;
    border-radius: 4px;
}
.post-id {
    position: absolute;
    top: 10px;
    left: 10px;
    background-color: #f1f1f1;
    padding: 5px 10px;
    border-radius: 3px;
    font-size: 12px;
    font-weight: bold;
    color: #333;
}
.test-item {
    position: relative;
    padding-top: 40px;
}
.custom-number {
    position: absolute;
    top: 10px;
    right: 10px;
    background-color: #0073aa;
    color: white;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: bold;
}

.test-item {
    position: relative; /* Để custom number nằm trong item */
    padding-top: 40px; /* Dành không gian cho custom number */
}
.completed-icon {
    position: absolute;
    top: 10px;
    left: 10px;
    background-color: #fff;
    color: #63E6BE;
    padding: 5px;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    font-size: 16px;
}
.test-item {
    position: relative; /* Giúp biểu tượng nằm trong item */
    padding-top: 40px; /* Dành không gian cho biểu tượng */
}

</style>
