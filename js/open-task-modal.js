$(function() {
    var openBtn = $('.add-task');
    var closeBtn = $('.close-form-task-btn');
    var taskModal = $('.add-task-form');

    openBtn.on("click", function() {
        taskModal.fadeIn(300);
    });

    closeBtn.on("click", function() {
        taskModal.fadeOut(300);
    });
});

function openDeleteTaskForm(id) {
    var taskDeleteForm = $('.delete-task-form-' + id);
    var cancelBtn = $('.cancel-deletion-' + id);

    taskDeleteForm.fadeIn(300);

    cancelBtn.on("click", function() {
        taskDeleteForm.fadeOut(300);
    });
}

function openEditTaskForm(id) {
    var taskEditForm = $('.edit-task-form-' + id);
    var cancelBtn = $('.cancel-edit-' + id);

    taskEditForm.fadeIn(300);

    cancelBtn.on("click", function() {
        taskEditForm.fadeOut(300);
    });
}