<?php


// Kiểm tra user đã đăng nhập
if (!is_user_logged_in()) {
    echo '<p>Vui lòng đăng nhập để xem bảng từ đã lưu.</p>';
    get_footer();
    exit;
}

global $wpdb;
$current_user = wp_get_current_user();
$username = $current_user->user_login;

$table_name ='notation';
$notations = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT number, save_time, username, word_save, meaning_or_explanation, is_source, test_type, id_test 
         FROM $table_name 
         WHERE username = %s 
         ORDER BY save_time DESC",
        $username
    )
);
?>
<style>


/* CSS */
.button-10 {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 6px 14px;
  font-family: -apple-system, BlinkMacSystemFont, 'Roboto', sans-serif;
  border-radius: 6px;
  border: none;

  color: #fff;
  background: linear-gradient(180deg, #4B91F7 0%, #367AF6 100%);
   background-origin: border-box;
  box-shadow: 0px 0.5px 1.5px rgba(54, 122, 246, 0.25), inset 0px 0.8px 0px -0.25px rgba(255, 255, 255, 0.2);
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
}
.text-area-meaning{
   width: 100%;
  height: 150px;
  padding: 12px 20px;
  box-sizing: border-box;
  border: 2px solid #ccc;
  border-radius: 4px;
  background-color: #f8f8f8;
  font-size: 16px;
  resize: none;
}

.export-buttons {
        display: flex;
        gap: 10px; /* Khoảng cách giữa các nút */
        justify-content: center; /* Căn giữa các nút */
    }
.button-10:focus {
  box-shadow: inset 0px 0.8px 0px -0.25px rgba(255, 255, 255, 0.2), 0px 0.5px 1.5px rgba(54, 122, 246, 0.25), 0px 0px 0px 3.5px rgba(58, 108, 217, 0.5);
  outline: 0;
}
.controls{
    margin-bottom: 20px; display: flex; gap: 10px;}
</style>
<div class="notation-table-container">
    <h2>Bảng từ đã lưu của bạn</h2>

    <!-- Nút Xuất và In -->
    <div class="export-buttons" style="margin-bottom: 20px; display: flex; gap: 10px;">
        <button class="button-10" onclick="exportTableToCSV()">Xuất CSV</button>
        <button class="button-10" onclick="exportTableToDoc()">Xuất DOCX</button>
        <button class="button-10" onclick="window.print()">In Bảng</button>
    </div>

    <table id="notationTable" border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Number</th>
                <th>Save Time</th>
                <th>Username</th>
                <th>Word Save</th>
                <th>Meaning or Explanation</th>
                <th>Source</th>
                <th>Test Type</th>
                <th>ID Test</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($notations)) : ?>
                <?php $rowNumber = 1; ?>
                <?php foreach ($notations as $notation) : ?>
                    <tr id="row-<?php echo $notation->number; ?>">
                        <td><?php echo $rowNumber++; ?></td>
                        <td><?php echo esc_html(date('Y-m-d', strtotime($notation->save_time))); ?></td>
                        <td><?php echo esc_html($notation->username); ?></td>
                        <td id="word-<?php echo $notation->number; ?>"><?php echo esc_html($notation->word_save); ?></td>
                        <td id="meaning-<?php echo $notation->number; ?>"><?php echo esc_html($notation->meaning_or_explanation); ?></td>
                        <td><?php echo esc_html($notation->is_source); ?></td>
                        <td><?php echo esc_html($notation->test_type); ?></td>
                        <td><?php echo esc_html($notation->id_test); ?></td>
                        <td>
                            <button class="button-10" onclick="openEditPopup(<?php echo $notation->number; ?>)">Sửa</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="9">Không có từ nào được lưu.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<!-- Edit Popup -->
<div id="editPopup" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:#fff; padding:20px; box-shadow:0 2px 10px rgba(0,0,0,0.1); z-index:1000;">
    <h3>Chỉnh sửa từ đã lưu</h3>
    <input type="hidden" id="editNumber">
    <label for="editWord">Word Save:</label>
    <input type="text" id="editWord" style="width: 100%; margin-bottom: 10px;">
    <label for="editMeaning">Meaning or Explanation:</label>
    <textarea id="editMeaning" class = "text-area-meaning" style="width: 100%; margin-bottom: 10px;"></textarea>
    <div class = "controls">
        <button class="button-10" onclick="saveEdit()">Lưu</button>
        <button class="button-10" onclick="closeEditPopup()">Đóng</button>
    </div>
</div>

<script>




    function openEditPopup(number) {
        document.getElementById("editNumber").value = number;
        document.getElementById("editWord").value = document.getElementById("word-" + number).innerText;
        document.getElementById("editMeaning").value = document.getElementById("meaning-" + number).innerText;
        document.getElementById("editPopup").style.display = "block";
    }

    function closeEditPopup() {
        document.getElementById("editPopup").style.display = "none";
    }

    function saveEdit() {
        let number = document.getElementById("editNumber").value;
        let wordSave = document.getElementById("editWord").value;
        let meaningOrExplanation = document.getElementById("editMeaning").value;

        // Gửi AJAX request để cập nhật database
        let data = new FormData();
        data.append('action', 'update_notation');
        data.append('number', number);
        data.append('word_save', wordSave);
        data.append('meaning_or_explanation', meaningOrExplanation);

        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            body: data
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                document.getElementById("word-" + number).innerText = wordSave;
                document.getElementById("meaning-" + number).innerText = meaningOrExplanation;
                closeEditPopup();
                alert("Cập nhật thành công!");
            } else {
                alert("Cập nhật thất bại!");
            }
        });
    }

    const date = new Date().toISOString().split('T')[0]

    // Xuất bảng ra CSV
    function exportTableToCSV() {
        let table = document.getElementById("notationTable");
        let rows = table.querySelectorAll("tr");
        let csvContent = "";

        rows.forEach(row => {
            let cells = row.querySelectorAll("td, th");
            let rowData = [];
            cells.forEach(cell => rowData.push(`"${cell.innerText}"`));
            csvContent += rowData.join(",") + "\n";
        });

        let blob = new Blob([csvContent], { type: "text/csv" });
        let link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = `notation_${date}.csv`;
        link.click();
    }

    // Xuất bảng ra DOCX
    function exportTableToDoc() {
        let table = document.getElementById("notationTable");
        let rows = table.querySelectorAll("tr");
        let docContent = "<table border='1' style='border-collapse:collapse;width:100%;'>";

        rows.forEach(row => {
            docContent += "<tr>";
            let cells = row.querySelectorAll("td, th");
            cells.forEach(cell => docContent += `<td>${cell.innerText}</td>`);
            docContent += "</tr>";
        });

        docContent += "</table>";

        let blob = new Blob(
            [`<html><head><meta charset='utf-8'></head><body>${docContent}</body></html>`],
            { type: "application/msword" }
        );
        let link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = `notation_${date}.doc`;
        link.click();
    }
</script>

