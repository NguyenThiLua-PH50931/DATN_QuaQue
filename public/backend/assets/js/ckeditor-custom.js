document.addEventListener('DOMContentLoaded', function () {
    const editorElement = document.querySelector('#editor');
    if (editorElement) { // Chỉ khởi tạo nếu phần tử tồn tại
        ClassicEditor
            .create(editorElement)
            .catch(error => {
                console.error(error);
            });
    }
});
