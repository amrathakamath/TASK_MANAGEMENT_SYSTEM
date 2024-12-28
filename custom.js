document.addEventListener("DOMContentLoaded", function() {
    const deadline = new Date("<?php echo $task['deadline']; ?>"); // Fetch deadline from PHP
    const currentTime = new Date();
    if (currentTime > deadline) {
        document.getElementById('statusDropdown').disabled = true; // Disable dropdown
        document.getElementById('updateButton').disabled = true; // Disable button
        alert("You cannot edit the task status after the deadline.");
    }
});