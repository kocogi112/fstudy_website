<?php

add_filter('document_title_parts', function ($title) {
 
        $title['title'] = sprintf('Test Library');
    
    return $title;
});


get_header();
// Define the post types with labels for the navigation
$post_types = [
    'all' => 'Tất cả',
    'thptqg' => 'THPTQG',
    'digitalsat' => 'Digital SAT',
    'ieltsspeakingtests' => 'Ielts Speaking',
    'ieltsreadingtest' => 'Ielts Reading',
    'ieltswritingtests' => 'Ielts Writing',
    'dictationexercise' => 'Dictation Exercise',
    'studyvocabulary' => 'Study Vocabulary',
    'conversation_ai' => 'Speak With AI',

];

// Get the current post type and search term from the URL
$current_post_type = get_query_var('post_type', 'all');
$search_term = get_query_var('term', '');

// Display the navigation buttons
?>
<div class="post-type-navigation">
    <?php foreach ($post_types as $type => $label) : ?>
        <a href="<?php echo esc_url(home_url("/tests/" . ($type === 'all' ? '' : $type) . ($search_term ? '?term=' . $search_term : ''))); ?>" 
           class="nav-button <?php echo $current_post_type === $type || ($type === 'all' && $current_post_type === '') ? 'active' : ''; ?>">
            <?php echo esc_html($label); ?>
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
    'post_type' => $current_post_type !== 'all' ? $current_post_type : 'any',
    's' => $search_term,
    'posts_per_page' => 8,
    'paged' => $paged,
];

// Run the query
$query = new WP_Query($args);
?>

<div class="test-library">
    <!--<h1><?php echo ucfirst($current_post_type) ?: 'Tests'; ?> Results</h1> -->
    <?php if ($query->have_posts()) : ?>
        <div class="test-grid">
        <?php 
        global $wpdb;
        $current_user = wp_get_current_user(); // Lấy thông tin user đang đăng nhập
        $username = $current_user->user_login; // Lấy username để đối chiếu trong bảng lưu kết quả
        
        while ($query->have_posts()) : $query->the_post(); 
    $post_id = get_the_ID();
    $custom_number = get_post_meta($post_id, "_{$current_post_type}_custom_number", true);

    // Kiểm tra xem người dùng đã làm bài kiểm tra này hay chưa
    $is_completed = false;

    if ($current_post_type === 'digitalsat') {
        $table_name = 'save_user_result_digital_sat';
        $query_result = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) 
                FROM {$table_name} 
                WHERE username = %s 
                AND idtest = %s 
                AND resulttest != ''", // Kiểm tra resulttest không trống
                $username, $custom_number
            )
        );
        $is_completed = $query_result > 0; // Nếu tìm thấy kết quả, đánh dấu là đã làm bài
    }

    else if ($current_post_type === 'ieltsreadingtest') {
        $table_name = 'save_user_result_ielts_reading';
        $query_result = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) 
                FROM {$table_name} 
                WHERE username = %s 
                AND idtest = %s 
                AND overallband != ''", // Kiểm tra resulttest không trống
                $username, $custom_number
            )
        );
        $is_completed = $query_result > 0; // Nếu tìm thấy kết quả, đánh dấu là đã làm bài
    }
    
    // Sử dụng $is_completed để đánh dấu hoặc hiển thị trạng thái bài kiểm tra


        ?>
    <div class="test-item">
        <!-- Hiển thị Custom Number -->
        <?php if ($is_completed): ?>
            <div class="completed-icon">
               
                <i class="fa-solid fa-check" style="color: #63E6BE;"></i>
            </div>

            
        <?php endif; ?>
        <div class="custom-number"><?php echo esc_html($custom_number); ?></div>

        <h2><?php the_title(); ?></h2>
        <div class="test-meta">
            <p>⏱️ <?php echo get_post_meta(get_the_ID(), '_duration', true) ?: '60 phút'; ?></p>
            <p>📄 <?php echo get_post_meta(get_the_ID(), '_total_questions', true) ?: '22 phần thi | 22 câu hỏi'; ?></p>
            <p>💬 <?php echo get_post_meta(get_the_ID(), '_comments_count', true) ?: '4'; ?></p>
        </div>
        <div class="test-tags">
            <?php the_tags('<span>#', '</span> <span>#', '</span>'); ?>
        </div>
        <a href="<?php the_permalink(); ?>" class="detail-button">Take Test</a>
    </div>
<?php endwhile; ?>


        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php
            echo paginate_links([
                'total' => $query->max_num_pages,
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
