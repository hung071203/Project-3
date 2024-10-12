
    document.addEventListener('DOMContentLoaded', function() {
    // Lấy tất cả các nút "Edit" có class "edit-button"
    var editButtons = document.querySelectorAll('.edit-button');

    // Xử lý sự kiện khi nút "Edit" được click
    editButtons.forEach(function(button) {
    button.addEventListener('click', function(event) {
    event.preventDefault();
    var row = button.closest('.report-row');
    var reportId = row ? row.dataset.reportId : null;

    // Gửi yêu cầu cập nhật thông qua Fetch API
    fetch(`/update-report/${reportId}`, {
    method: 'PUT', // Sử dụng phương thức PUT để cập nhật
    headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
},
    body: JSON.stringify({ status: button.dataset.status }) // Truyền trạng thái qua body
})
    .then(response => response.json())
    .then(data => {
    // Ẩn nút "Edit" của dòng được cập nhật
    button.style.display = 'none';
})
    .catch(error => {
    console.error('Error:', error);
});
});
});
});
