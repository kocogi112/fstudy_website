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
            global $wpdb;

            $current_user = wp_get_current_user();
            $current_username = $current_user->user_login;
            echo'<i>Xem chi tiết phân tích, lịch sử bài làm theo từng dạng tại đây</i>
            <a class="button-12" role="button"  href="http://localhost/wordpress/analysis/">Xem chi tiết</a>';

            // Function to render table data for a specific table
            function render_table_data_with_links($table_name, $base_url, $meta_key, $current_username, $date_column, $result_column, $wpdb) {
                $dates_query = $wpdb->prepare("SELECT DISTINCT $date_column FROM $table_name WHERE username = %s ORDER BY $date_column DESC", $current_username);
                $distinct_dates = $wpdb->get_col($dates_query);
                

                foreach ($distinct_dates as $date) {
                    $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE username = %s AND $date_column = %s", $current_username, $date));

                    if ($results) {
                        echo '<b>Danh sách bài làm từ bảng: ' . $table_name . '</b>';
                        echo '<table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tài khoản</th>
                                        <th>Ngày làm bài</th>
                                        <th>Đề thi</th>
                                        <th>ID Đề thi</th>
                        
                                        <th>Kết quả</th>
                                        <th>ID Kết quả</th>
                                        <th>Xem chi tiết</th>
                                    </tr>
                                </thead>
                                <tbody>';

                        foreach ($results as $row) {
                            // Fetch post_id from wp_postmeta
                            $post_id = $wpdb->get_var($wpdb->prepare(
                                "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = %s",
                                $meta_key,
                                $row->idtest
                            ));

                            // Generate post link if post_id exists
                            $post_link = $post_id ? get_permalink($post_id) : 'Không tìm thấy link';

                            echo '<tr>
                                    <td>' . esc_html($row->username) . '</td>
                                    <td>' . esc_html($row->dateform) . '</td>
                                    <td>' . esc_html($row->testname) . '</td>
                                    <td>' . esc_html($row->idtest) . '</td>
                                    <td>' . esc_html($row->$result_column) . '</td>
                                    <td>' . esc_html($row->testsavenumber) . '</td>
                                    
                                    <td>' . ($post_id ? '<a href="' . esc_url($post_link . 'result/' . $row->testsavenumber) . '/' .'" target="_blank">Xem chi tiết</a>' : 'Không tìm thấy kết quả') . '</td>
                                </tr>';
                        }

                        echo '</tbody></table>';
                    } else {
                        echo '<p>Không có kết quả nào từ bảng ' . $table_name . '.</p>';
                    }
                }
            }

            // Render table for digital SAT
            render_table_data_with_links('save_user_result_digital_sat', 'http://localhost/wordpress/digitalsat/', '_digitalsat_custom_number', $current_username, 'dateform', 'resulttest', $wpdb);

            // Render table for IELTS Reading
            render_table_data_with_links('save_user_result_ielts_reading', 'http://localhost/wordpress/ieltsreading/', '_ieltsreadingtest_custom_number', $current_username, 'dateform', 'overallband', $wpdb);

            // Render table for digital SAT
            render_table_data_with_links('save_user_result_ielts_speaking', 'http://localhost/wordpress/digitalsat/', '_ieltsspeakingtests_custom_number', $current_username, 'dateform', 'resulttest', $wpdb);

            // Render table for IELTS Reading
            render_table_data_with_links('save_user_result_ielts_writing', 'http://localhost/wordpress/ieltswriting/', '_ieltswritingtests_custom_number', $current_username, 'dateform', 'band_score', $wpdb);
            

            render_table_data_with_links('save_user_result_ielts_listening', 'http://localhost/wordpress/ieltswriting/', '_ieltslisteningtest_custom_number', $current_username, 'dateform', 'overallband', $wpdb);


        } else {
            echo 'Vui lòng đăng nhập để xem kết quả làm bài.';
        }
        ?>
    </main>
</div>