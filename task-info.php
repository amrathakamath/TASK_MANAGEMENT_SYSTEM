<?php

require 'authentication.php'; // admin authentication check 

// auth check
$user_id = $_SESSION['admin_id'];
$user_name = $_SESSION['name'];
$security_key = $_SESSION['security_key'];
if ($user_id == NULL || $security_key == NULL) {
    header('Location: index.php');
}

// check admin
$user_role = $_SESSION['user_role'];

if (isset($_GET['delete_task'])) {
    $action_id = $_GET['task_id'];
    $sql = "DELETE FROM task_info WHERE task_id = :id";
    $sent_po = "task-info.php";
    $obj_admin->delete_data_by_this_method($sql, $action_id, $sent_po);
}

if (isset($_POST['add_task_post'])) {
    $obj_admin->add_new_task($_POST);
}

$page_name = "Task_Info";
include("include/sidebar.php");

if (isset($_POST['create_task'])) {
    $task_title = $_POST['task_title'];
    $task_description = $_POST['task_description'];
    $t_start_time = $_POST['t_start_time'];
    $t_end_time = $_POST['t_end_time'];
    $deadline = $_POST['deadline']; // Get the deadline from the form

    // Insert into the database
    $sql = "INSERT INTO task_info (t_title, t_description, t_start_time, t_end_time, deadline, t_user_id) VALUES (:title, :description, :start_time, :end_time, :deadline, :user_id)";
    // Prepare and execute the statement...
}
$deadline = new DateTime($task['deadline']);
    $current_time = new DateTime();
if (isset($_POST['update_task_status'])) {
    $task_id = $_POST['task_id'];
    $new_status = $_POST['status'];
    $task = $obj_admin->get_task_by_id($task_id); // Fetch task from the database

    // Ensure the deadline is in the correct format
    $deadline = new DateTime($task['deadline']);
    $current_time = new DateTime(); // Get the current time

    // Check if the current time exceeds the deadline
    if ($current_time > $deadline) {
        echo "Error: You cannot edit the task status after the deadline.";
    } else {
        // Proceed with updating the task status
        $obj_admin->update_task_status($task_id, $new_status);
        echo "Task status updated successfully.";
    }
}

?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog add-category-modal">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title text-center">Assign New Task</h2>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form role="form" action="" method="post" autocomplete="off">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-sm-5">Task Title</label>
                                    <div class="col-sm-7">
                                        <input type="text" placeholder="Task Title" id="task_title" name="task_title" list="expense" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-5">Task Description</label>
                                    <div class="col-sm-7">
                                        <textarea name="task_description" id="task_description" placeholder="Text Description" class="form-control" rows="5" cols="5"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-5">Start Time</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="t_start_time" id="t_start_time" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-5">End Time</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="t_end_time" id="t_end_time" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-5">Assign To</label>
                                    <div class="col-sm-7">
                                        <?php 
                                            $sql = "SELECT user_id, fullname FROM tbl_admin WHERE user_role = 2";
                                            $info = $obj_admin->manage_all_info($sql);   
                                        ?>
                                        <select class="form-control" name="assign_to" id="aassign_to" required>
                                            <option value="">Select student...</option>
                                            <?php while($row = $info->fetch(PDO::FETCH_ASSOC)){ ?>
                                                <option value="<?php echo $row['user_id']; ?>"><?php echo $row['fullname']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-3">
                                        <button type="submit" name="add_task_post" class="btn btn-success-custom">Assign Task</button>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="button" class="btn btn-danger-custom" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="well well-custom">
            <div class="gap"></div>
            <div class="row">
                <div class="col-md-8">
                    <div class="btn-group">
                        <?php if($user_role == 1){ ?>
                            <button class="btn btn-warning btn-menu" data-toggle="modal" data-target="#myModal">Assign New Task</button>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <center><h3>Task Management Section</h3></center>
            <div class="gap"></div>
            <div class="table-responsive">
                <table class="table table-condensed table-custom">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Task Title</th>
                            <th>Assigned To</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            if($user_role == 1){
                                $sql = "SELECT a.*, b.fullname 
                                        FROM task_info a
                                        INNER JOIN tbl_admin b ON(a.t_user_id = b.user_id)
                                        ORDER BY a.task_id DESC";
                            } else {
                                $sql = "SELECT a.*, b.fullname 
                                        FROM task_info a
                                        INNER JOIN tbl_admin b ON(a.t_user_id = b.user_id)
                                        WHERE a.t_user_id = $user_id
                                        ORDER BY a.task_id DESC";
                            } 
                            
                            $info = $obj_admin->manage_all_info($sql);
                            $serial  = 1;
                            $num_row = $info->rowCount();
                            if($num_row == 0){
                                echo '<tr><td colspan="7">No Data found</td></tr>';
                            }
                            while($row = $info->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <tr>
                            <td><?php echo $serial; $serial++; ?></td>
                            <td><?php echo $row['t_title']; ?></td>
                            <td><?php echo $row['fullname']; ?></td>
                            <td><?php echo $row['t_start_time']; ?></td>
                            <td><?php echo $row['t_end_time']; ?></td>
                            <td>
                                <?php  
                                    if($row['status'] == 1){
                                        echo "In Progress <span style='color:#d4ab3a;' class='glyphicon glyphicon-refresh'></span>";
                                    } elseif($row['status'] == 2){
                                        echo "Completed <span style='color:#00af16;' class='glyphicon glyphicon-ok'></span>";
                                    } else {
                                        echo "Incomplete <span style='color:#d00909;' class='glyphicon glyphicon-remove'></span>";
                                    } 
                                ?>
                            </td>
                            <td>
                                <a title="Update Task" href="edit-task.php?task_id=<?php echo $row['task_id'];?>"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                                <a title="View" href="task-details.php?task_id=<?php echo $row['task_id']; ?>"><span class="glyphicon glyphicon-folder-open"></span></a>&nbsp;&nbsp ```php
                                <?php if($user_role == 1){ ?>
                                    <a title="Delete" href="?delete_task=delete_task&task_id=<?php echo $row['task_id']; ?>" onclick="return check_delete();"><span class="glyphicon glyphicon-trash"></span></a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include("include/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script type="text/javascript">
    flatpickr('#t_start_time', {
        enableTime: true
    });

    flatpickr('#t_end_time', {
        enableTime: true
    });
</script>