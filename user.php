<?php
    session_start();
    include "cek.php";
    // include "cetak.php";

    if(isset($_SESSION['login'])){
        if($user[$login]['role'] == 'admin'){
            header("location: admin.php?menu=Dashboard");
        }
    }
    if(isset($login) && isset($user)){
        foreach($user as $key => $value){
            if($login == $value['username']){
                $namaLengkap = $value['firstName'] . ' ' . $value['lastName'];
            break;
            }
        }
    }

    if(isset($_GET['menu'])){
        $menu = $_GET['menu'];
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

        $id_barang = $_POST['id_barang'];
        $count = intval($_POST['count']);

        if(isset($login)){
            if($count > 0){
                $idxcars = -1;
                foreach($cars as $key => $value){
                    if($value['id'] == $id_barang){
                        $idxcars = $key;
                    break;
                    }
                }
                if($idxcars != -1){
                    if($cars[$idxcars]['stock'] >= $count){
                        $dataBaru = array(
                            "id_user" => $login,
                            "id_car" => $id_barang,
                            "count" => $count,
                            "totalprice" => $count * $cars[$idxcars]['price'],
                            "status" => "Pending"
                        );
                        if($order == ''){
                            $order = array();
                        }
                        array_push($order, $dataBaru);
                        $_SESSION['order'] = $order;
                    }
                    else{
                        echo "<script type='text/javascript'>alert('Stock tidak cukup!');</script>";
                    }
                }
            }
            else{
                echo "<script type='text/javascript'>alert('Tidak boleh membeli 0 atau minus!');</script>";
            }
        }
        else{
            echo "<script type='text/javascript'>alert('Belum login! Silahkan login terlebih dahulu!');</script>";
        }
    }

    if(isset($_POST['btnSell'])){
        $id_user_cars = $_POST['btnSell'];
        unset($user[$login]['cars'][$id_user_cars]);
        $_SESSION['user'] = $user;
    }

    if(isset($_POST['btnCancel'])){
        $id_order = $_POST['btnCancel'];
        $order[$id_order]['status'] = "Cancelled";
        $_SESSION['order'] = $order;
    }

    if(isset($_POST['btnCompare'])){
        $sMaker = $_POST['sMaker'];
        $sCategory = $_POST['sCategory'];
        $id_barang = $_POST['id_barang'];
        $idxada = -1;
        foreach($cars as $keys => $values){
            if($values['id'] == $id_barang){
                $idxada = $keys;
            }
        }
        if($idxada != -1){
            $compare[$ctrcompare] = $cars[$idxada];
            $_SESSION['compare'] = $compare;
            $ctrcompare++;
            $_SESSION['ctrcompare'] = $ctrcompare;
            if($ctrcompare == 2){
                if($compare[0] != $compare[1]){
                    unset($_POST['btnCompare']);
                    $ctrcompare = 0;
                    $_SESSION['ctrcompare'] = $ctrcompare;
                    header("location: user.php?menu=Compare");
                }
                else{
                    $ctrcompare--;
                    $_SESSION['ctrcompare'] = $ctrcompare;
                }
            }
        }   
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

        <title>Member</title>
    </head>
    <body>
        <script src="js/jquery-3.4.1.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>

        
        <!-- Navbar -->
        <nav class="navbar navbar-expand navbar-dark bg-dark">
            <div class="container">
                <a href="#" class="navbar-brand">Hello, <?php if(isset($namaLengkap)){ echo $namaLengkap;}?>!</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                    <li class="nav-item <?php if($_GET['menu'] == 'Dashboard' || $_GET['menu'] == 'Compare') echo 'active';?>">
                        <a class="nav-link" href="user.php?menu=Dashboard">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item <?php if($_GET['menu'] == 'Summary') echo 'active';?>">
                        <a class="nav-link" href="user.php?menu=Summary">Summary</a>
                    </li>
                    </ul>
                </div>
                <div class="col-7"></div>
                <a class="btn btn-danger my-2" href="logout.php" name="btnLogout">Logout</a>
            </div>
        </nav>

        <!-- Main Content -->
        <main>

            <!-- Dashboard -->
            <!-- Search Form -->
            <div class="container mt-5 jumbotron bglight  <?php if($menu == 'Dashboard') echo "d-block"; else echo "d-none";?>">
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
                        <br><br>
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
                if(!isset($_POST['btnSearch']) || $menu != 'Dashboard'){
                    echo 'd-none';
                }
            ?>">
                <div class="container pt-5 <?php
                if(!isset($_POST['btnSearch']) || $menu != 'Dashboard'){
                    echo 'd-none';
                }
            ?>">
                    <h2 class="text-light pb-2">Your Search Result</h2>
                    <?php
                    if(isset($_POST['btnSearch']) && $menu == 'Dashboard'){
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
                                echo '<input type="hidden" name="sMaker" value=' . (isset($sMaker) ? json_encode($sMaker) : "") . '>
                                <input type="hidden" name="sCategory" value=' . (isset($sCategory) ? json_encode($sCategory) : "").'>';
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
            
            <!-- Summary -->
            <div class="container my-4 <?php if($menu == 'Summary') echo "d-block"; else echo "d-none";?>">
                <h1>Your Summary</h1>
                <br><br>
                
                <!-- Car -->
                <h2>Your Cars</h2>
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Name</th>
                        <th scope="col">Maker</th>
                        <th scope="col">Category</th>
                        <th scope="col">Price ($)</th>
                        <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(isset($user[$login]['cars']) && count($user[$login]['cars']) > 0){
                            foreach($user[$login]['cars'] as $key => $value){
                                echo "<tr>";
                                echo "<td class='align-middle'>" . $value['id'] . "</td>";
                                echo "<td class='align-middle'>" . $value['name'] . "</td>";
                                echo "<td class='align-middle'>" . $value['maker'] . "</td>";
                                echo "<td class='align-middle'>" . $value['category'] . "</td>";
                                echo "<td class='align-middle'>" . $value['price'] . "</td>";
                                echo '<td><form method="post"><button type="submit" class="btn btn-success mt-3" name="btnSell" value="' . $key .'">Sell</button></form></td>';
                                echo "</tr>";
                            }
                        }
                        else{
                            echo "<tr>";
                            echo "<th colspan='7' class='text-center'>NO DATA FOUND</th>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <h2>Your Orders</h2>
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                        <th scope="col">Id_Car</th>
                        <th scope="col">Count</th>
                        <th scope="col">Total Price</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $jmlorder = 0;
                        if(isset($order)){
                            foreach($order as $key => $value){
                                if($value['id_user'] == $login && $value['status'] != 'Accept'){
                                    echo "<tr>";
                                    echo "<td class='align-middle'>" . $value['id_car'] . "</td>";
                                    echo "<td class='align-middle'>" . $value['count'] . "</td>";
                                    echo "<td class='align-middle'>" . $value['totalprice'] . "</td>";
                                    if($value['status'] == 'Pending'){
                                        echo "<td class='align-middle bg-warning'>" . $value['status'] . "</td>";
                                        echo '<td><form method="post"><button type="submit" class="btn btn-danger mt-3" name="btnCancel" value="' . $key .'">Cancel</button></form></td>';
                                    }
                                    else if($value['status'] == 'Rejected'){
                                        echo "<td class='align-middle bg-danger'>" . $value['status'] . "</td>";
                                        echo "<td></td>
                                        ;";
                                    }
                                    else if($value['status'] == 'Cancelled'){
                                        echo "<td class='align-middle'>" . $value['status'] . "</td>";
                                        echo "<td></td>
                                        ;";
                                    }
                                    echo "</tr>";
                                    $jmlorder++;
                                }
                            }
                        }
                        if($jmlorder == 0){
                            echo "<tr>";
                            echo "<th colspan='4' class='text-center'>NO DATA FOUND</th>";
                            echo "</tr>";
                        }
                        
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Compare -->
            <div class="container my-4 <?php if($menu == 'Compare') echo "d-block"; else echo "d-none";?>">
                <h1>Compare</h1>
                <br>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <td scope="col" style="width: 10%;"></td>
                            <td scope="col" style="width: 45%;"><div class="container-fluid border border-dark" style="height: 200px;"></div></td>
                            <td scope="col" style="width: 45%;"><div class="container-fluid border border-dark" style="height: 200px;"></div></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row" style="width: 10%;">Name</th>
                            <td style="width: 45%;"><?php echo $compare[0]['name'];?></td>
                            <td style="width: 45%;"><?php echo $compare[1]['name'];?></td>
                        </tr>
                        <tr>
                            <th scope="row" style="width: 10%;">Category</th>
                            <td style="width: 45%;"><?php echo $compare[0]['category'];?></td>
                            <td style="width: 45%;"><?php echo $compare[1]['category'];?></td>
                        </tr>
                        <tr>
                            <th scope="row" style="width: 10%;">Maker</th>
                            <td style="width: 45%;"><?php echo $compare[0]['maker'];?></td>
                            <td style="width: 45%;"><?php echo $compare[1]['maker'];?></td>
                        </tr>
                        <tr>
                            <th scope="row" style="width: 10%;">Price</th>
                            <td><?php echo $compare[0]['price'];?></td>
                            <td><?php echo $compare[1]['price'];?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>    
    </body>
</html>