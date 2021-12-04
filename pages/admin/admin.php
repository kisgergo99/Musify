<?php
require(__DIR__."/../../loadhelper.php");
$database = new Database();
$usertype = new Usertype();
$users = $database->getUsers();
$distributors = $database->getDistributorList();
echo "<script src='functions_admin.js'></script>";

if($usertype->isLoggedIn() && $usertype->getPrivilege($usertype->getUsername()) == 'admin'){
    if(isset($_REQUEST['editUser']) || isset($_REQUEST['editDist']) || isset($_REQUEST['addDist'])){
        if(isset($_REQUEST['editDist'])){
            editDist($database->getDistById($_REQUEST['editDist']));
        }
        if(isset($_REQUEST['editUser'])){
            editUser($database->getUserById($_REQUEST['editUser']), $distributors);
        }
        if(isset($_REQUEST['addDist'])){
            addDist();
        }
    }else{
        listing($users, $distributors);
    }

    if(isset($_POST['updateUser'])){
        $errors = 0;
        if($_POST['user_subscription_status'] == 'on' && empty($_POST['user_subscription_expiredate'])){
            $errors++;
        }
        if($_POST['user_type'] == 'distributor' && empty($_POST['distributor_select'])){
            $errors++;
        }
        if($errors==0){
            $database->editUser($_POST);
            redirect('admin.php');
        }else{
            echo "<h2>An error occurred while processing your request. False datas given!</h2>";
        }
    }
    if(isset($_POST['updateDist'])){
        $database->editDist($_POST);
        redirect('admin.php');
    }
    if(isset($_POST['addnewDist'])){
        $database->addDist($_POST);
        redirect('admin.php');
    }
    if(isset($_REQUEST['deleteDist'])){
        $database->deleteDist($_REQUEST['deleteDist']);
        redirect('admin.php');
    }
    if(isset($_REQUEST['deleteUser'])){
        $database->deleteUser($_REQUEST['deleteUser']);
        redirect('admin.php');
    }
}else{
    redirect("../../");
}

function listing($users, $distributors){
    echo "<h1 class='text-center'>Manage Users</h1><hr>";
    echo "<div class='table-responsive-sm p-3'><table class='table text-white' style=''>
    <tr class='table-active text-white'>
            <th scope='col'>User ID</th>
            <th scope='col'>Username</th>
            <th scope='col'>Email</th>
            <th scope='col'>First Name</td>
            <th scope='col'>Last Name</td>
            <th scope='col'>Subscription status</td>
            <th scope='col'>Role</td>
            <th scope='col'>Distributor ID</td>
            <th scope='col'> Edit </th>
            <th scope='col'> Delete User </th>
        </tr>
    ";
    foreach($users as $u){
        echo "<tr scope='row'>";
            echo "<td>".$u['user_id']."</td>";
            echo "<td>".$u['username']."</td>";
            echo "<td>".$u['user_email']."</td>";
            echo "<td>".$u['user_firstname']."</td>";
            echo "<td>".$u['user_lastname']."</td>";
            //echo "<td>".$u['user_subscription_status']."</td>";
            if($u['user_subscription_status']){
                echo "<td>Subscribed until: ".$u['user_subscription_expiredate']."</td>";
            }else{
                echo "<td>No Subsciption</td>";
            }
            
            if($u['user_type'] == 0){
                echo "<td>Listener</td>";
            }
            if($u['user_type'] == 1){
                echo "<td>Administrator</td>";
            }
            if($u['user_type'] == 2){
                echo "<td>Distributor</td>";
            }
            echo "<td>".$u['user_distributor_id']."</td>";
            
            echo "<td><a href='admin.php?editUser=".$u['user_id']."'>Edit user</a></td>";
            echo "<td><a href='admin.php?deleteUser=".$u['user_id']."' style='color: red;' onclick='return confirm(\"Are you sure you want to delete this user? This process is irreversible!\");'>Delete User</a></td>";
        echo "</tr>";
    }
    echo "</table>";
    
    listDistributors($distributors);
    
}

function editUser($userArray, $distributors){
    echo "<form action='".$_SERVER['PHP_SELF']."' method=POST>
    <div class='container text-white w-100 p-5'>
        <h1 class='text-center'>Edit user</h1>
        <div class='row border justify-content-sm-center rounded'> 
            <div class='col'>
                <div class='form-group'>
                    <h2 class='text-center'>User ID: ".$userArray['user_id']."</h2>
                    <input type='hidden' name='user_id' id='user_id' value='".$userArray['user_id']."'>
                    <hr>

                    <label for='username'>Username: </label>
                    <input type='text' name='username' id='username' value='".$userArray['username']."' class='form-control' required>

                    <label for='album_artist_name'>Email address: </label>
                    <h4>".$userArray['user_email']."</h4>

                    <label for='user_subscription_status'>Permission to listen music (Subsciption status):</label>
                    <div class='form-check form-switch'>
                        <input type='checkbox' class='form-check-input' name='user_subscription_status' id='user_subscription_status' ";
                        if($userArray['user_subscription_status']==1){
                            echo "checked";
                        }
                        echo ">
                    </div>

                    <div class='expiredate-form'>
                        <label for='user_subscription_expiredate'>Expire date of subsciption: </label>
                        <input type='date' name='user_subscription_expiredate' id='user_subscription_expiredate' value='".$userArray['user_subscription_expiredate']."' class='form-control' required>
                    </div>


                    <label for='user_type'>Role:</label><br>
                    <input type='radio' id='listener' name='user_type' class='user_type' value='listener' ";
                    if($userArray['user_type']==0){echo 'checked';} 
                    echo ">Listener<br>";
                    echo "<input type='radio' id='admin' name='user_type' class='user_type' value='admin' ";
                    if($userArray['user_type']==1){echo 'checked';} 
                    echo ">Admin<br>";
                    echo "<input type='radio' id='distributor' name='user_type' class='user_type' value='distributor' ";
                    if($userArray['user_type']==2){echo 'checked';} 
                    echo ">Distributor<br>";

                    echo "
                    <div id='distributor_list' name='distributor_list'>
                    <label for='distributor_select'>The user distributing music in the name of: </label>
                        <select name='distributor_select' id='distributor_select'>
                            ";
                            foreach($distributors as $d){
                                echo "<option value='".$d['distributor_id']."'>".$d['distributor_name']."</option>";
                            }
                            echo "
                        </select>
                    </div>
                    ";
                    echo "<button type='submit' class='btn btn-primary w-100' name='updateUser' id='updateUser'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil' viewBox='0 0 16 16'>
                        <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z'/>
                    </svg>
                        Save Changes
                    </button>";

                    echo "</div>";

                    echo "";

    echo "</div>
        </div>
    </div>
    </form>";
}

function listDistributors($distributors){
    echo "<h1 class='text-center'>Manage Distributors</h1><hr>";
    echo "<div class='table-responsive-sm p-3'><table class='table text-white' style=''>
        <tr class='table-active text-white'>
            <th scope='col'>Distributor ID</th>
            <th scope='col'>Distributor name</th>
            <th scope='col'>Publish status</th>
            <th scope='col'>Edit</th>
            <th scope='col'>Delete</th>
        </tr>
    ";

    foreach($distributors as $d){
        echo "<tr scope='row'>";
        echo "<td>".$d['distributor_id']."</td>";
        echo "<td>".$d['distributor_name']."</td>";
        if($d['distributor_publish_status'] == 1){
            echo "<td>Allowed</td>";
        }else{
            echo "<td>Denied</td>";
        }
        echo "<td><a href='admin.php?editDist=".$d['distributor_id']."'>Edit distributor</a></td>";
        echo "<td><a href='admin.php?deleteDist=".$d['distributor_id']."' style='color: red;' onclick='return confirm(\"Are you sure you want to delete this distributor? This process is irreversible!\");'>Delete distributor</a></td>";

        echo "<tr>";
    }
    echo "</table>";
    echo "<a href='admin.php?addDist' style='float: right'><button class='btn btn-primary'><i class='fa fa-plus-circle'></i> Add new Distributor</button></a>";
}

function editDist($dist){
    echo "<form action='".$_SERVER['PHP_SELF']."' method=POST>
    <div class='container text-white w-100 p-5'>
        <h1 class='text-center'>Edit distributor</h1>
        <div class='row border justify-content-sm-center rounded'> 
            <div class='col'>
                <div class='form-group'>
                    <h2 class='text-center'>Distributor ID: ".$dist['distributor_id']."</h2>
                    <input type='hidden' name='dist_id' id='dist_id' value='".$dist['distributor_id']."'>
                    <hr>

                    <label for='username'>Distributor name: </label>
                    <input type='text' name='dist_name' id='dist_name' value='".$dist['distributor_name']."' class='form-control' required>

                    <label for='user_subscription_status'>Publishing permission:</label>
                    <div class='form-check form-switch'>
                        <input type='checkbox' class='form-check-input' name='dist_publish_status' id='dist_publish_status' ";
                        if($dist['distributor_publish_status']==1){
                            echo "checked";
                        }
                        echo ">
                    </div>
                    ";
                    echo "<button type='submit' class='btn btn-primary w-100' name='updateDist' id='updateDist'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil' viewBox='0 0 16 16'>
                        <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z'/>
                    </svg>
                        Save Changes
                    </button>";

                    echo "</div>";

                    echo "";

    echo "</div>
        </div>
    </div>
    </form>";
}

function addDist(){
    echo "<form action='".$_SERVER['PHP_SELF']."' method=POST>
    <div class='container text-white w-100 p-5'>
        <h1 class='text-center'>Add new Distributor</h1>
        <div class='row border justify-content-sm-center rounded'> 
            <div class='col'>
                <div class='form-group'>
                    <hr>

                    <label for='username'>Distributor name: </label>
                    <input type='text' name='dist_name' id='dist_name' class='form-control' required>

                    <label for='user_subscription_status'>Publishing permission:</label>
                    <div class='form-check form-switch'>
                        <input type='checkbox' class='form-check-input' name='dist_publish_status' id='dist_publish_status'>
                    </div>
                    
                    <button type='submit' class='btn btn-primary w-100' name='addnewDist' id='addnewDist'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil' viewBox='0 0 16 16'>
                        <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z'/>
                    </svg>
                        Save Changes
                    </button>
                    
                    </div>
            </div>
        </div>
    </div>
    </form>";
}


?>