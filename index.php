<?php
session_start();

if(isset($_SESSION['user'])){
    $user = $_SESSION['user'];
}

if(isset($_SESSION['maker'])){
    $maker = $_SESSION['maker'];
}

if(isset($_SESSION['category'])){
    $category = $_SESSION['category'];
}

if(isset($_SESSION['cars'])){
    $cars = $_SESSION['cars'];
}

if(isset($_SESSION['order'])){
    $order = $_SESSION['order'];
}

if(isset($_SESSION['compare'])){
    $compare = $_SESSION['compare'];
}

if(isset($_SESSION['ctrcompare'])){
    $ctrcompare = $_SESSION['ctrcompare'];
}

if(isset($_POST['btnRegister']))
{
    $lengkap = true;
    $benar = true;
    if(isset($_POST['firstName']) && $_POST['firstName'] != ''){
        $firstName = $_POST['firstName'];
    }
    else{
        $lengkap = false;
    }

    if(isset($_POST['lastName']) && $_POST['lastName'] != ''){
        $lastName = $_POST['lastName'];
    }
    else{
        $lengkap = false;
    }

    if(isset($_POST['username']) && $_POST['username'] != ''){
        $username = $_POST['username'];
    }
    else{
        $lengkap = false;
    }

    if(isset($_POST['password']) && $_POST['password'] != ''){
        $password = $_POST['password'];
    }
    else{
        $lengkap = false;
    }

    if(isset($_POST['cpassword']) && $_POST['cpassword'] != ''){
        $cpassword = $_POST['cpassword'];
    }
    else{
        $lengkap = false;
    }

    if(isset($_POST['address']) && $_POST['address'] != ''){
        $address = $_POST['address'];
    }
    else{
        $lengkap = false;
    }

    if(isset($_POST['city']) && $_POST['city'] != ''){
        $city = $_POST['city'];
    }
    else{
        $lengkap = false;
    }

    if(isset($_POST['role']) && $_POST['role'] != ''){
        $role = $_POST['role'];
    }
    else{
        $lengkap = false;
    }

    if($lengkap == true){
        if($password != $cpassword){
            $benar = false;
        }
    }

    if($lengkap == true && $benar == true){
        $dataBaru = array(
            "firstName" => $firstName,
            "lastName" => $lastName,
            "username" => $username,
            "password" => $password,
            "address" => $address,
            "city" => $city,
            "role" => $role,
            "cars" => array()
        );

        //masukkan data baru ke dalam penyimpanan yaitu $data, gunakan gabungan dari nama depan dan belakang sebagai key
        $user[$username] = $dataBaru;
        $_SESSION['user'] = $user;
    }
    else if($lengkap == false){
        echo "<script type='text/javascript'>alert('Data belum terisi lengkap!');</script>";
    }
    else if($benar == false){
        echo "<script type='text/javascript'>alert('Password dan Confirm Password tidak sama!');</script>";
    }
}


if(isset($_POST['btnLogin'])){
    $u = $_POST['username'];
    $p = $_POST['password'];

    $ada = false;
    if($user != null){
        foreach($user as $key => $value){
            if($value['username'] == $u){
                $ada = true;
                if($value['password'] == $p){
                    if($value['role'] == 'admin'){
                        $_SESSION['compare'] = [];
                        $_SESSION['login'] = $u;
                        header("location: admin.php?menu=Dashboard");
                        exit;
                    }
                    else if($value['role'] == 'member'){
                        $_SESSION['compare'] = [];
                        $_SESSION['login'] = $u;
                        header("location: user.php?menu=Dashboard");
                        exit;                    
                    }
                }
                else{
                    echo "<script type='text/javascript'>alert('password salah!');</script>";
                }
            }
        }
    }
    
    if($ada == false){
        echo "<script type='text/javascript'>alert('username tidak ada!');</script>";
    }
}

if(isset($_POST['btnSearch'])){
    $sMaker = $_POST['sMaker'];
    $sCategory = $_POST['sCategory'];
    $sMinimum = $_POST['sMinimum'];
    $sMaximum = $_POST['sMaximum'];
}

if(isset($_POST['btnBuy'])){
    $sMaker = $_POST['sMaker'];
    $sCategory = $_POST['sCategory'];
    if(!isset($login)){
        echo "<script type='text/javascript'>alert('Belum login! Silahkan login terlebih dahulu!');</script>";
    }
}

if(isset($_POST['btnCompare'])){
    $sMaker = $_POST['sMaker'];
    $sCategory = $_POST['sCategory'];
    $id_barang = $_POST['id_barang'];
    if(!isset($login)){
        echo "<script type='text/javascript'>alert('Belum login! Silahkan login terlebih dahulu!');</script>";
    }
}

if(!isset($user)){
    $user = [];
    $_SESSION['user'] = $user;
}
if(!isset($maker)){
    $maker = [];
    $_SESSION['maker'] = $maker;
}
if(!isset($category)){
    $category = [];
    $_SESSION['category'] = $category;
}
if(!isset($order)){
    $order = [];
    $_SESSION['order'] = $order;
}
if(!isset($cars)){
    $cars = [];
    $_SESSION['cars'] = $cars;
}
if(!isset($compare)){
    $compare = [];
    $_SESSION['compare'] = $compare;
}
if(!isset($ctrcompare)){
    $ctrcompare = 0;
    $_SESSION['ctrcompare'] = $ctrcompare;
}

if(isset($_POST['btnDestroy'])){
    session_destroy();
    header("location: index.php");
}

?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/color.css">
        <link rel="stylesheet" href="css/index.css">

        <title>Home</title>
    </head>
    <body>
        <script src="js/jquery-3.4.1.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>

        <!-- Modal Register -->
        <form method="post">
            <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="registerModalTitle">Register</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <div class="form-group row">
                            <div class="col">
                            <label for="inputFirstName">First Name</label>
                            <input type="text" name="firstName" id="inputFirstName" class="form-control" placeholder="Sherlock">
                            </div>

                            <div class="col">
                            <label for="inputLastName">Last Name</label>
                            <input type="text" name="lastName" id="inputLastName" class="form-control"
                            placeholder="Holmes">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputUsername">Username</label>
                            <input type="text" name="username" id="inputUsername" class="form-control"
                            placeholder="sherly@gmail.com">
                        </div>

                        <div class="form-group">
                            <label for="inputPassword">Password</label>
                            <input type="password" name="password" id="inputPassword" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="inputCPassword">Confirm Password</label>
                            <input type="password" name="cpassword" id="inputCPassword" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Address</label>
                            <input type="text" name="address" id="inputAddress" class="form-control"
                            placeholder="221b Bakerstreet">
                        </div>

                        <div class="form-group">
                            <label for="inputCity">City</label>
                            <input type="text" name="city" id="inputCity" class="form-control"
                            placeholder="London">
                        </div>

                        <div class="form-group">
                            <label for="inputRole">Role</label>
                            <select id="inputRole" name="role" class="form-control">
                                <option selected value="admin">Admin</option>
                                <option value="member">Member</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" >Cancel</button>
                        <button type="submit" class="btn btn-success" name="btnRegister">Register</button>
                    </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Modal Login -->
        <form method="post">
            <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loginModalTitle">Login</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="inputUsername" class="col-form-label col-2">Username</label>
                            <div class="col-10">
                                <input type="text" name="username" id="inputUsername" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword" class="col-form-label col-2">Password</label>
                            <div class="col-10">
                                <input type="password" name="password" id="inputPassword" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="btnLogin" class="btn btn-success">Login</button>
                    </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Navbar -->
        <nav class="navbar navbar-dark bg-dark">
            <div class="container">
                <a href="#" class="navbar-brand">Carsold</a>
                <div class="col-9"></div>
                <button class="btn btn-outline-success my-2 mr-1" data-toggle="modal" data-target="#registerModal" type="button">Be A Member</button>
                <button class="btn btn-info my-2" type="button" data-toggle="modal" data-target="#loginModal">Login</button>
                <!-- <form method="post"><button class="btn btn-warning my-2" type="submit" name="btnDestroy">Destroy</button></form> -->
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            <!-- Search Form -->
            <div class="container mt-5 jumbotron bglight">
                <h1 class="text-white">Search</h1>
                <br>
                <form method="post">
                    <div class="row">
                        <div class="col-6">
                            <select class="form-control" name="sMaker" placeholder="Maker">
                                <option value="" selected>Maker</option>
                                <?php 
                                    foreach($maker as $key => $value){
                                        echo "<option value='$value[name]'>$value[name]</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <select class="form-control" name="sCategory" 
                            placeholder="Category">
                            <option value="" selected>Category</option>
                                <?php 
                                    foreach($category as $key => $value){
                                        echo "<option value='$value[name]'>$value[name]</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <br>
                        <br>
                        <div class="col-6">
                            <input class="form-control" type="number" name="sMinimum" placeholder="Minimum Price">
                        </div>
                        <div class="col-6">
                            <input class="form-control" type="number" name="sMaximum" placeholder="Maximum Price">
                        </div>
                    </div>
                    <br><br>
                    <div class="text-center">
                        <button type="submit" id="btnSearch" name="btnSearch" class="btn btn-lg btn-success">Search</button>
                    </div>
                </form>
            </div>

            <!-- Search Content -->
            <div class="container-fluid bg-dark mt-1 pb-3<?php
                if(!isset($_POST['btnSearch'])){
                    echo 'd-none';
                }
            ?>">
                <div class="container pt-5 <?php
                if(!isset($_POST['btnSearch'])){
                    echo 'd-none';
                }
            ?>">
                    <h2 class="text-light pb-2">Your Search Result</h2>
                    <?php
                    if(isset($_POST['btnSearch'])){
                        $arrkey = array();
                        foreach($cars as $key => $value){
                            if($value['stock'] > 0){
                                if($sMinimum != '' && $sMaximum != ''){
                                    if($value['price'] >= $sMinimum && $value['price'] <= $sMaximum){
                                        if($sMaker != '' && $sCategory != ''){
                                            if($value['maker'] == $sMaker && $value['category'] == $sCategory){
                                                array_push($arrkey, $key);
                                            }
                                        }
                                        else if($sMaker != ''){
                                            if($value['maker'] == $sMaker){   
                                                array_push($arrkey, $key);
                                            }
                                        }
                                        else if($sCategory != ''){
                                            if($value['category'] == $sCategory){
                                                array_push($arrkey, $key);
                                            }
                                        }
                                        else{
                                            array_push($arrkey, $key);
                                        }
                                    }
                                }
                                else if($sMinimum != ''){
                                    if($value['price'] >= $sMinimum){
                                        if($sMaker != '' && $sCategory != ''){
                                            if($value['maker'] == $sMaker && $value['category'] == $sCategory){
                                                array_push($arrkey, $key);
                                            }
                                        }
                                        else if($sMaker != ''){
                                            if($value['maker'] == $sMaker){   
                                                array_push($arrkey, $key);
                                            }
                                        }
                                        else if($sCategory != ''){
                                            if($value['category'] == $sCategory){
                                                array_push($arrkey, $key);
                                            }
                                        }
                                        else{
                                            array_push($arrkey, $key);
                                        }
                                    }
                                }
                                else if($sMaximum != ''){
                                    if($value['price'] <= $sMaximum){
                                        if($sMaker != '' && $sCategory != ''){
                                            if($value['maker'] == $sMaker && $value['category'] == $sCategory){
                                                array_push($arrkey, $key);
                                            }
                                        }
                                        else if($sMaker != ''){
                                            if($value['maker'] == $sMaker){   
                                                array_push($arrkey, $key);
                                            }
                                        }
                                        else if($sCategory != ''){
                                            if($value['category'] == $sCategory){
                                                array_push($arrkey, $key);
                                            }
                                        }
                                        else{
                                            array_push($arrkey, $key);
                                        }
                                    }
                                }
                                else if($sMaker != '' && $sCategory != ''){
                                    if($value['maker'] == $sMaker && $value['category'] == $sCategory){
                                        array_push($arrkey, $key);
                                    }
                                }
                                else if($sMaker != ''){
                                    if($value['maker'] == $sMaker){   
                                        array_push($arrkey, $key);
                                    }
                                }
                                else if($sCategory != ''){
                                    if($value['category'] == $sCategory){
                                        array_push($arrkey, $key);
                                    }
                                }
                                else{
                                    array_push($arrkey, $key);
                                }
                            }
                        }
                        if(count($arrkey) == 0){
                            echo '<h1 class="text-center text-light pt-3 pb-5"> - NO SEARCH RESULT FOUND - </h1>';
                        }
                        else{
                            echo '<div class="row">';
                            foreach($arrkey as $value){
                                echo '<div class="col-4 my-4">';
                                echo '<div class="card p-0 mx-1">';
                                echo '<div class="container-fluid border border-dark" style="height: 200px;"></div>';
                                echo '<div class="card-body">';
                                echo '<h5 class="card-title">' . $cars[$value]['name'] . '</h5>';
                                echo '<p class="card-text">';
                                echo 'Category : ' . $cars[$value]['category'] . '<br>';
                                echo 'Maker : ' . $cars[$value]['maker'] . '<br>';
                                echo 'Price : $' . $cars[$value]['price'] . '<br>';
                                echo '<form method="post">';
                                echo '<input class="form-control" name="count" placeholder="Count"><br>';
                                echo '<input type="hidden" name="id_barang" value="' . $cars[$value]['id'] . '">';
                                echo "<input type='hidden' name='sCategory' value='" .  (isset($sCategory) ? json_encode($sCategory) : "") . "'>";
                                echo "<input type='hidden' name='sMaker' value='" .  (isset($sMaker) ? json_encode($sMaker) : "") . "'>";
                                echo '<button type="submit" class="btn btn-success" name="btnBuy">Buy</button>';
                                echo '<button type="submit" class="btn btn-info ml-1" name="btnCompare">Compare</button>';
                                echo '</form>';
                                echo '</p>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </main>       
    </body>
</html>