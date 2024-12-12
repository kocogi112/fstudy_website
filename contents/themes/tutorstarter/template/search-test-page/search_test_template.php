<?php
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
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <div class="test-item">
                    <h2><?php the_title(); ?></h2>
                    <div class="test-meta">
                        <p>⏱️ <?php echo get_post_meta(get_the_ID(), '_duration', true) ?: '60 phút'; ?></p>
                        <p>📄 <?php echo get_post_meta(get_the_ID(), '_total_questions', true) ?: '22 phần thi | 22 câu hỏi'; ?></p>
                        <p>💬 <?php echo get_post_meta(get_the_ID(), '_comments_count', true) ?: '4'; ?></p>
                    </div>
                    <div class="test-tags">
                        <?php the_tags('<span>#', '</span> <span>#', '</span>'); ?>
                    </div>
                    <a href="<?php the_permalink(); ?>" class="detail-button">Chi tiết</a>
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

</style>
