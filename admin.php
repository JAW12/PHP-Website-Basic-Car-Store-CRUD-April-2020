<?php
    session_start();
    include "cek.php";
    // include "cetak.php";

    if(isset($_SESSION['login'])){
        if($user[$login]['role'] == 'member'){
            header("location: user.php?menu=Dashboard");
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

    // Maker PHP
    if(isset($_POST['btnRemoveMaker'])){
        $idx = -1;
        foreach($maker as $key => $value){
            if($value['id'] == $_POST['btnRemoveMaker']){
                $idx = $key;
            break;
            }
        }
        if($idx != -1){
            unset($maker[$idx]);
        }
        $_SESSION['maker'] = $maker;
    }

    if(isset($_POST['btnEditMaker'])){
        header("location:admin.php?menu=Edit Maker&edit=" . $_POST['btnEditMaker']);
    }

    if(isset($_POST['btnCancelMaker'])){
        header("location:admin.php?menu=Makes");
    }

    if(isset($_POST['btnAddMaker'])){
        $id = $_POST['idmaker'];
        $name= $_POST['namamaker'];
        $dataBaru = array(
            "id" => $id,
            "name" => $name
        );

        $idxAda = -1;
        foreach($maker as $key => $value){
            if($value['id'] == $id){
                $idxAda = $key;
            break;
            }
        }
        if($name != ''){
            if($idxAda == -1){
                $maker[] = $dataBaru;
            }
            else{
                $maker[$idxAda] = $dataBaru;
            }
            $_SESSION['maker'] = $maker;
            header("location:admin.php?menu=Makes");
        }
        else{
            echo "<script type='text/javascript'>alert('Data belum terisi lengkap!');</script>";
        }
    }

    // Category PHP
    if(isset($_POST['btnRemoveCategory'])){
        $idx = -1;
        foreach($category as $key => $value){
            if($value['id'] == $_POST['btnRemoveCategory']){
                $idx = $key;
            break;
            }
        }
        if($idx != -1){
            unset($category[$idx]);
        }
        $_SESSION['category'] = $category;
        header("location:admin.php?menu=Category");
    }

    if(isset($_POST['btnEditCategory'])){
        header("location:admin.php?menu=Edit Category&edit=" . $_POST['btnEditCategory']);
    }

    if(isset($_POST['btnCancelCategory'])){
        header("location:admin.php?menu=Category");
    }

    if(isset($_POST['btnAddCategory'])){
        $id = $_POST['idcategory'];
        $name= $_POST['namacategory'];
        $dataBaru = array(
            "id" => $id,
            "name" => $name
        );

        $idxAda = -1;
        foreach($category as $key => $value){
            if($value['id'] == $id){
                $idxAda = $key;
            break;
            }
        }
        if($name != ''){
            if($idxAda == -1){
                $category[] = $dataBaru;
            }
            else{
                $category[$idxAda] = $dataBaru;
            }
            $_SESSION['category'] = $category;
            header("location:admin.php?menu=Category");
        }
        else{
            echo "<script type='text/javascript'>alert('Data belum terisi lengkap!');</script>";
        }
    }

    // Car PHP
    if(isset($_POST['btnRemoveCar'])){
        $idx = -1;
        foreach($cars as $key => $value){
            if($value['id'] == $_POST['btnRemoveCar']){
                $idx = $key;
            break;
            }
        }
        if($idx != -1){
            unset($cars[$idx]);
        }
        $_SESSION['cars'] = $cars;
        header("location:admin.php?menu=Cars");
    }

    if(isset($_POST['btnEditCar'])){
        header("location:admin.php?menu=Edit Car&edit=" . $_POST['btnEditCar']);
    }

    if(isset($_POST['btnCancelCar'])){
        header("location:admin.php?menu=Cars");
    }

    if(isset($_POST['btnAddCar'])){
        $id = $_POST['idcar'];
        $name= $_POST['namacar'];
        $mak= $_POST['makercar'];
        $cat= $_POST['categorycar'];
        $price= $_POST['pricecar'];
        $stock= $_POST['stockcar'];
        $dataBaru = array(
            "id" => $id,
            "name" => $name,
            "maker" => $mak,
            "category" => $cat,
            "price" => $price,
            "stock" => $stock
        );

        $idxAda = -1;
        foreach($cars as $key => $value){
            if($value['id'] == $id){
                $idxAda = $key;
            break;
            }
        }
        if($name != '' && $mak != '' && $cat != '' && $price != '' && $stock != ''){
            if($idxAda == -1){
                $cars[] = $dataBaru;
            }
            else{
                $cars[$idxAda] = $dataBaru;
            }
            $_SESSION['cars'] = $cars;
            header("location:admin.php?menu=Cars");
        }
        else{
            echo "<script type='text/javascript'>alert('Data belum terisi lengkap!');</script>";
        }
    }

    if(isset($_POST['btnAcceptOrder'])){
        $id_order = $_POST['btnAcceptOrder'];
        $id_car = $order[$id_order]['id_car'];
        $count_order = $order[$id_order]['count'];

        $idxcars = -1;
        foreach($cars as $key => $value){
            if($value['id'] == $id_car){
                $idxcars = $key;
            break;
            }
        }

        if($idxcars != -1){
            if($cars[$idxcars]['stock'] >= $count_order){
                $cars[$idxcars]['stock'] -= $count_order;
                $order[$id_order]['status'] = "Accept";
                $id_user = $order[$id_order]['id_user'];
                $idxuser = -1;
                foreach($user as $key => $value){
                    if($value['username'] == $id_user){
                        $idxuser = $key;
                    break;
                    }
                }

                if($idxuser != -1){
                    $dataBaru = array(
                        "id" => $id_car,
                        "name" => $cars[$idxcars]['name'],
                        "maker" => $cars[$idxcars]['maker'],
                        "category" => $cars[$idxcars]['category'],
                        "price" => $cars[$idxcars]['price']
                    );

                    for($i = 0; $i < $count_order; $i++){
                        array_push($user[$idxuser]['cars'], $dataBaru);
                    }
                    $_SESSION['cars'] = $cars;
                    $_SESSION['order'] = $order;
                    $_SESSION['user'] = $user;

                    header("location:admin.php?menu=Cars");
                }
            }
            else{
                echo "<script type='text/javascript'>alert('Stock tidak cukup!');</script>";
            }
        }
    }

    if(isset($_POST['btnRejectOrder'])){
        $id_order = $_POST['btnRejectOrder'];
        $order[$id_order]['status'] = "Rejected";
        $_SESSION['order'] = $order;
        header("location:admin.php?menu=Cars");
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
        <link href="css/all.css" rel="stylesheet">

        <title>Admin</title>
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
                    <li class="nav-item <?php if($_GET['menu'] == 'Dashboard') echo 'active';?>">
                        <a class="nav-link" href="admin.php?menu=Dashboard">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item <?php if($_GET['menu'] == 'Makes' || $_GET['menu'] == 'New Maker' || $_GET['menu' == 'Edit Maker']) echo 'active';?>">
                        <a class="nav-link" href="admin.php?menu=Makes">Makes</a>
                    </li>
                    <li class="nav-item <?php if($_GET['menu'] == 'Category' || $_GET['menu'] == 'New Category') echo 'active';?>">
                        <a class="nav-link" href="admin.php?menu=Category">Category</a>
                    </li>
                    <li class="nav-item <?php if($_GET['menu'] == 'Cars' || $_GET['menu'] == 'New Car') echo 'active';?>">
                        <a class="nav-link" href="admin.php?menu=Cars">Cars</a>
                    </li>
                    </ul>
                </div>
                <div class="col-6"></div>
                <a class="btn btn-danger my-2" href="logout.php" name="btnLogout">Logout</a>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            <div class="container mt-4">
                <h1><?php echo $menu;?></h1>
            </div>

            <!-- Maker Section -->
            <div class="container mt-5 <?php if($menu == 'Makes'){ echo 'd-block'; }else{ echo 'd-none';}?>">
                <a class="btn btn-success" href="admin.php?menu=New Maker" role="button">+ Add New Maker</a>

                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Name</th>
                        <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <form method="post">
                        <?php

                        if(isset($maker)){
                            $jmlmaker = 0;
                            foreach($maker as $key => $value){
                                echo "<tr>";
                                echo "<td class='align-middle'>" . $value['id'] . "</td>";
                                echo "<td class='align-middle'>" . $value['name'] . "</td>";
                                echo "<td class='text-right'>
                                <button type='submit' name='btnEditMaker' value='$value[id]' class='btn btn-warning'><i class='fas fa-edit'></i></button>
                                <button type='submit' name='btnRemoveMaker' value='$value[id]' class='btn btn-danger'><i class='fas fa-trash-alt'></i></button>
                                </td>";
                                echo "</tr>";
                                $jmlmaker++;
                            }
                            if($jmlmaker == 0){
                                echo "<tr>";
                                echo "<th colspan='3' class='text-center'>NO DATA FOUND</th>";
                                echo "</tr>"; 
                            }
                        }
                        else{
                            echo "<tr>";
                            echo "<th colspan='3' class='text-center'>NO DATA FOUND</th>";
                            echo "</tr>";
                        }
                        ?>
                        </form>
                    </tbody>
                </table>
            </div>

            <div class="container mt-5 <?php if($menu == 'New Maker' || $menu == 'Edit Maker'){echo 'd-block';} else {echo 'd-none';} ?> ">
                <form method="post">
                    <div class="form-group">
                        <label for="inputId">ID</label>
                        <input type="text" name="idmaker" id="inputId" class="form-control" value="<?php
                        if($menu == 'New Maker'){
                            if(count($maker) > 0){
                                $max = intval(substr(end($maker)['id'], 3)) + 1;
                            }
                            else{
                                $max = 0;
                            }
                            echo "MK_" . str_pad($max, 3, '0', STR_PAD_LEFT);
                        }
                        else if($menu == 'Edit Maker'){
                            echo $_GET['edit'];
                        }
                        ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="inputNama">Nama</label>
                        <input type="text" name="namamaker" id="inputNama" class="form-control" value="<?php
                        if($menu == 'Edit Maker'){
                            foreach($maker as $key => $value){
                                if($value['id'] == $_GET['edit']){
                                    echo $value['name'];
                                break;
                                }
                            }
                        }
                        ?>">
                    </div>

                    <button type="submit" class="btn btn-success" name="btnAddMaker">
                        <?php
                        if($menu == 'New Maker'){
                            echo "Add";
                        }
                        else if($menu == 'Edit Maker'){
                            echo "Edit";
                        }
                        ?>
                    </button>
                    <button type="submit" class="btn btn-danger" name="btnCancelMaker">
                        Cancel
                    </button>
                </form>
            </div>

            <!-- Category Section -->
            <div class="container mt-5 <?php if($menu == 'Category'){ echo 'd-block'; }else{ echo 'd-none';}?>">
                <a class="btn btn-success" href="admin.php?menu=New Category" role="button">+ Add New Category</a>

                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Name</th>
                        <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <form method="post">
                        <?php

                        if(isset($category)){
                            $jmlcategory = 0;
                            foreach($category as $key => $value){
                                echo "<tr>";
                                echo "<td class='align-middle'>" . $value['id'] . "</td>";
                                echo "<td class='align-middle'>" . $value['name'] . "</td>";
                                echo "<td class='text-right'>
                                <button type='submit' name='btnEditCategory' value='$value[id]' class='btn btn-warning'><i class='fas fa-edit'></i></button>
                                <button type='submit' name='btnRemoveCategory' value='$value[id]' class='btn btn-danger'><i class='fas fa-trash-alt'></i></button>
                                </td>";
                                echo "</tr>";
                                $jmlcategory++;
                            }
                            if($jmlcategory == 0){
                                echo "<tr>";
                                echo "<th colspan='3' class='text-center'>NO DATA FOUND</th>";
                                echo "</tr>"; 
                            }
                        }
                        else{
                            echo "<tr>";
                            echo "<th colspan='3' class='text-center'>NO DATA FOUND</th>";
                            echo "</tr>";
                        }
                        ?>
                        </form>
                    </tbody>
                </table>
            </div>

            <div class="container mt-5 <?php if($menu == 'New Category' || $menu == 'Edit Category'){echo 'd-block';} else {echo 'd-none';} ?> ">
                <form method="post">
                    <div class="form-group">
                        <label for="inputId">ID</label>
                        <input type="text" name="idcategory" id="inputId" class="form-control" value="<?php
                        if($menu == 'New Category'){
                            if(count($category) > 0){
                                $max = intval(substr(end($category)['id'], 3)) + 1;
                            }
                            else{
                                $max = 0;
                            }
                            echo "CA_" . str_pad($max, 3, '0', STR_PAD_LEFT);
                        }
                        else if($menu == 'Edit Category'){
                            echo $_GET['edit'];
                        }
                        ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="inputNama">Nama</label>
                        <input type="text" name="namacategory" id="inputNama" class="form-control" value="<?php
                        if($menu == 'Edit Category'){
                            foreach($category as $key => $value){
                                if($value['id'] == $_GET['edit']){
                                    echo $value['name'];
                                break;
                                }
                            }
                        }
                        ?>">
                    </div>

                    <button type="submit" class="btn btn-success" name="btnAddCategory">
                        <?php
                        if($menu == 'New Category'){
                            echo "Add";
                        }
                        else if($menu == 'Edit Category'){
                            echo "Edit";
                        }
                        ?>
                    </button>
                    <button type="submit" class="btn btn-danger" name="btnCancelCategory">
                        Cancel
                    </button>
                </form>
            </div>

            <!-- Cars Section -->
            <div class="container mt-5 <?php if($menu == 'Cars'){ echo 'd-block'; }else{ echo 'd-none';}?>">
                <a class="btn btn-success" href="admin.php?menu=New Car" role="button">+ Add New Car</a>

                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Name</th>
                        <th scope="col">Maker</th>
                        <th scope="col">Category</th>
                        <th scope="col">Price ($)</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <form method="post">
                        <?php
                        if(isset($cars)){
                            $jmlcar = 0;
                            foreach($cars as $key => $value){
                                echo "<tr>";
                                echo "<td class='align-middle'>" . $value['id'] . "</td>";
                                echo "<td class='align-middle'>" . $value['name'] . "</td>";
                                echo "<td class='align-middle'>" . $value['maker'] . "</td>";
                                echo "<td class='align-middle'>" . $value['category'] . "</td>";
                                echo "<td class='align-middle'>" . $value['price'] . "</td>";
                                echo "<td class='align-middle'>" . $value['stock'] . "</td>";
                                echo "<td class='text-right'>
                                <button type='submit' name='btnEditCar' value='$value[id]' class='btn btn-warning'><i class='fas fa-edit'></i></button>
                                <button type='submit' name='btnRemoveCar' value='$value[id]' class='btn btn-danger'><i class='fas fa-trash-alt'></i></button>
                                </td>";
                                echo "</tr>";
                                $jmlcar++;
                            }
                            if($jmlcar == 0){
                                echo "<tr>";
                                echo "<th colspan='7' class='text-center'>NO DATA FOUND</th>";
                                echo "</tr>";
                            }
                        }
                        else{
                            echo "<tr>";
                            echo "<th colspan='7' class='text-center'>NO DATA FOUND</th>";
                            echo "</tr>";
                        }
                        ?>
                        </form>
                    </tbody>
                </table>
                <br><br>
                <h2>Orders</h2>
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                        <th scope="col">Id_User</th>
                        <th scope="col">Id_Car</th>
                        <th scope="col">Count</th>
                        <th scope="col">Total Price ($)</th>
                        <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <form method="post">
                        <?php
                        if(isset($order)){
                            $jmlorder = 0;
                            foreach($order as $key => $value){
                                if($value['status'] == 'Pending'){
                                    echo "<tr>";
                                    echo "<td class='align-middle'>" . $value['id_user'] . "</td>";
                                    echo "<td class='align-middle'>" . $value['id_car'] . "</td>";
                                    echo "<td class='align-middle'>" . $value['count'] . "</td>";
                                    echo "<td class='align-middle'>" . $value['totalprice'] . "</td>";
                                    echo "<td class='text-right'>
                                    <button type='submit' name='btnAcceptOrder' value='$key' class='btn btn-success'><i class='fas fa-check'></i></button>
                                    <button type='submit' name='btnRejectOrder' value='$key' class='btn btn-danger'><i class='fas fa-times'></i></button>
                                    </td>";
                                    echo "</tr>";
                                    $jmlorder++;
                                }
                            }
                            if($jmlorder == 0){
                                echo "<tr>";
                                echo "<th colspan='7' class='text-center'>NO DATA FOUND</th>";
                                echo "</tr>";
                            }
                        }
                        else{
                            echo "<tr>";
                            echo "<th colspan='7' class='text-center'>NO DATA FOUND</th>";
                            echo "</tr>";
                        }
                        ?>
                        </form>
                    </tbody>
                </table>
            </div>

            <div class="container mt-5 <?php if($menu == 'New Car' || $menu == 'Edit Car'){echo 'd-block';} else {echo 'd-none';} ?> ">
                <form method="post">
                    <div class="form-group">
                        <label for="inputId">ID</label>
                        <input type="text" name="idcar" id="inputId" class="form-control" value="<?php
                        if($menu == 'New Car'){
                            if(count($cars) > 0){
                                $max = intval(substr(end($cars)['id'], 3)) + 1;
                            }
                            else{
                                $max = 0;
                            }
                            echo "CR_" . str_pad($max, 3, '0', STR_PAD_LEFT);                       
                         }
                        else if($menu == 'Edit Car'){
                            echo $_GET['edit'];
                        }
                        ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="inputNama">Nama</label>
                        <input type="text" name="namacar" id="inputNama" class="form-control" value="<?php
                        if($menu == 'Edit Car'){
                            foreach($cars as $key => $value){
                                if($value['id'] == $_GET['edit']){
                                    echo $value['name'];
                                break;
                                }
                            }
                        }
                        ?>">
                    </div>

                    <div class="form-group">
                        <label for="inputCategory">Category</label>
                        <select class="form-control" name="categorycar">
                            <?php 
                                $cat = '';
                                foreach($cars as $key => $value){
                                    if($value['id'] == $_GET['edit']){
                                         $cat = $value['category'];   
                                    break;
                                    }
                                }

                                foreach($category as $key => $value){
                                    echo "<option value='$value[name]'";
                                    if($value['name'] == $cat){
                                        echo "selected";
                                    }
                                    echo ">$value[name]</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="inputMaker">Maker</label>
                        <select class="form-control" name="makercar">
                            <?php 
                                $mak = '';
                                foreach($cars as $key => $value){
                                    if($value['id'] == $_GET['edit']){
                                         $mak = $value['maker'];   
                                    break;
                                    }
                                }

                                foreach($maker as $key => $value){
                                    echo "<option value='$value[name]'";
                                    if($value['name'] == $mak){
                                        echo "selected";
                                    }
                                    echo ">$value[name]</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="inputPrice">Price</label>
                        <input type="text" name="pricecar" id="inputPrice" class="form-control" value="<?php
                        if($menu == 'Edit Car'){
                            foreach($cars as $key => $value){
                                if($value['id'] == $_GET['edit']){
                                    echo $value['price'];
                                break;
                                }
                            }
                        }
                        ?>">
                    </div>

                    <div class="form-group">
                        <label for="inputStock">Stock</label>
                        <input type="text" name="stockcar" id="inputStock" class="form-control" value="<?php
                        if($menu == 'Edit Car'){
                            foreach($cars as $key => $value){
                                if($value['id'] == $_GET['edit']){
                                    echo $value['stock'];
                                break;
                                }
                            }
                        }
                        ?>">
                    </div>

                    <button type="submit" class="btn btn-success" name="btnAddCar">
                        <?php
                        if($menu == 'New Car'){
                            echo "Add";
                        }
                        else if($menu == 'Edit Car'){
                            echo "Edit";
                        }
                        ?>
                    </button>
                    <button type="submit" class="btn btn-danger" name="btnCancelCar">
                        Cancel
                    </button>
                </form>
            </div>
        </main>
    </body>
</html>