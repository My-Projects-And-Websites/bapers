$(function() {
    // buttons and modal form for adding task
    var openBtn = $('.add-task');
    var closeBtn = $('.close-form-task-btn');
    var taskModal = $('.add-task-form');

    // onclick of open button, modal container will fade in
    openBtn.on("click", function() {
        taskModal.fadeIn(300);
    });

    // onclick of close button, modal container will fade out
    closeBtn.on("click", function() {
        taskModal.fadeOut(300);
    });
});

function openDeleteTaskForm(id) {
    // button and form for delete task
    var taskDeleteForm = $('.delete-task-form-' + id);
    var cancelBtn = $('.cancel-deletion-' + id);

    // onclick of button delete modal form will fade In
    taskDeleteForm.fadeIn(300);

    // close delete task modal form
    cancelBtn.on("click", function() {
        taskDeleteForm.fadeOut(300);
    });
}

function openEditTaskForm(id) {
    // button and form for edit task
    var taskEditForm = $('.edit-task-form-' + id);
    var cancelBtn = $('.cancel-edit-' + id);

    // onclick of button edit modal form will fade In
    taskEditForm.fadeIn(300);

    // close edit task modal form
    cancelBtn.on("click", function() {
        taskEditForm.fadeOut(300);
    });
}