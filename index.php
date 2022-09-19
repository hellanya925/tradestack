<?php
require "./api/config.php";
require "./api/functions.php";
session_start();
if(!(isset($_SESSION['logged'])) || $_SESSION['logged'] !== true){
    header('location: log.html');
}
$email = $_SESSION['email'];
$sql = "SELECT * FROM categories WHERE owner='$email'";
$res = mysqli_query($conn,$sql);
$categories = '';
while($row = mysqli_fetch_assoc($res)){
    $categories .= '<option value="'.$row['pname'].'">'.$row['pname'].'</option>';
}
$sql = "select * FROM sold WHERE owner='$email'";
$res = mysqli_query($conn,$sql);
$me = $de = $we = $ye = $ms = $ds = $ws = $ys = 0;
$timestamp = date_timestamp_get(new DateTime());
$thisDay = date('d',$timestamp);
$thisWeek = date('W',$timestamp);
$thisMonth = date('m',$timestamp);
$thisYear = date('o',$timestamp);
while($row = mysqli_fetch_assoc($res)){
    $sdate = strtotime($row['date_sold']);
    $sday = date('d',$sdate);
    $sWeek = date('W',$sdate);
    $sMonth = date('m',$sdate);
    $sYear = date('o',$sdate);
    if($sday === $thisDay){
        $de += floatval($row['price'])*intval($row['quantity']);
        $ds += intval($row['quantity']);
    }
    if($sWeek === $thisWeek && $sYear === $thisYear){
        $we += intval($row['price'])*intval($row['quantity']);
    }
}
$totalEarnings = $me + $de + $we + $ye;
$sql = "select * from products WHERE owner='$email'";
$res = mysqli_query($conn,$sql);
$products = mysqli_fetch_all($res);
$totalIncome = $totalExpenses = 0;
$sql = "Select * from ie_stats WHERE owner='$email'";
$res = mysqli_query($conn,$sql);
while($row = mysqli_fetch_assoc($res)){
    if($row['type'] === 'i'){
        $totalIncome += floatval($row['value']);
    }else{
        $totalExpenses += floatval($row['value']);
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">
        <link rel="stylesheet" href="./style/index.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="./script/index.js"></script>
    </head>
    <body>
        <div class="header">
            <div class="sitename">
            <button onclick="shownav()" id="navicon">
                <i class="fa fa-navicon"></i>
            </button>
            <img src="./img/logo/default-monochrome.svg">
            </div>
            <div class="navb">
                <div class="closenavc">
                <button onclick="shownav()" class="closenav"><i class="fa fa-times"></i></button>
                </div>
                <div class="navlinks">
                    <a href="index.php" class="visited">My Stock</a></li>
                    <a href="market.html">Market</a>
                    <a href="news.html">News</a>
                    <a href="api/logout.php">Logout</a>
                </div>
            </div>
        </div>
        <hr style="height: .1vw;text-decoration:ridge;margin:2vw;">
        <div class="container mb-5">
            <div class="row d-flex p-0 m-0"  id="khod_latest_rates">
            </div>
            <script>load(document.getElementById('khod_latest_rates'))</script>
            <div class="row">
                <p class="rowtitle">My Stats</p>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 t1">
                        <p>Total Income: <span class="income"><?php echo $totalIncome;?> USD</span></p>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 t1">
                        <p>Total Expenses: <span class="expense"><?php echo $totalExpenses;?> USD</span></p>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 t1">
                        <p>Net Income: <span class="expense net"><?php $net = floatval($totalIncome) - floatval($totalExpenses);
                        if($net < 0){
                            echo '<span style="color: #ff5151 !important;">'.$net.' USD</span>';
                        }else{
                            echo '<span style="color: #02d026 !important;">'.$net.' USD</span>';
                        }
                        ?></p>
                    </div>
                </div>
                    <div class="col-lg-6 col-sm-12 col-xs-12" id="earnings">
                        <span>Earnings</span>
                        <div class="row d-flex mb-0">
                            <div class="col col-lg-5 col-xs-12 col-sm-12">
                                <input type="date" onchange="setEarningFilter(this.value,this.parentNode.parentNode.children[2].children[0].value)" value="<?php echo date('Y-m-j');?>" class="form-control" max="<?php echo (intval(date('Y)'))).'-12-'.date('t');?>" min="<?php echo (intval(date('Y')) - 20).'-01-01';?>"> 
                            </div>
                            <div class="col col-lg-2 col-xs-12 col-sm-12 arrow"> --> </div>
                            <div class="col col-lg-5 col-xs-12 col-sm-12">
                                <input type="date" class="form-control" onchange="setEarningFilter(this.parentNode.parentNode.children[0].children[0].value,this.value)" value="<?php echo date('Y-m-j');?>" max="<?php echo (intval(date('Y)'))).'-12-'.date('t');?>" min="<?php echo (intval(date('Y')) - 20).'-01-01';?>">     
                            </div>
                        </div>
                        <div class="col-12 d-flex">
                            <input type="text" class="col-8 earningsInp w-50 form-control" value="<?php echo $de;?>" disabled>
                            <select type="text" class="form-select w-50" onmousedown="localStorage.setItem('earningsoldcur',this.value)" onchange="setPriceFilter(this,this.parentNode.children[0],localStorage.getItem('earningsoldcur'))">
                                <option value="USD" selected>USD</option>
                                <option value="sayrafa">LBP (Sayrafa)</option>
                                <option value="bm">LBP (Black Market)</option>
                                <option value="omt">LBP (OMT)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-xs-12">
                        <span>Sales</span>
                        <div class="row d-flex">
                            <div class="col  col-lg-5 col-xs-12 col-sm-12">
                                <input type="date" onchange="setSalesFilter(this.parentNode.parentNode.children[2].children[0].value,this.value)" value="<?php echo date('Y-m-j');?>" class="form-control" max="<?php echo (intval(date('Y)'))).'-12-'.date('t');?>" min="<?php echo (intval(date('Y')) - 20).'-01-01';?>"> 
                            </div>
                            <div class="col col-lg-2 col-xs-12 col-sm-12 arrow"> --> </div>
                            <div class="col col-lg-5 col-xs-12 col-sm-12">
                                <input type="date" onchange="setSalesFilter(this.parentNode.parentNode.children[0].children[0].value,this.value)" class="form-control" value="<?php echo date('Y-m-j');?>" max="<?php echo (intval(date('Y)'))).'-12-'.date('t');?>" min="<?php echo (intval(date('Y')) - 20).'-01-01';?>">     
                            </div>
                            <div class="col col-12">
                                <input type="number" class="salesInp w-100 form-control" min="0" value="<?php echo $ds;?>" disabled>
                            </div>    
                        </div>
                    </div>
            </div>
            <div class="row">
                <form id="catform" onsubmit="return false;">
                    <p class="rowtitle">New Category</p>
                    <input type="text" id="cform" class="form-control" placeholder="Product Name" required>
                    <input type="file" class="form-control" id="catIcon">
                    <button type="submit" class="btn btn-success" onclick="addNewCategory()">Submit</button>
                </form>
            </div>
            <div class="row">
                <form id="pform" class="col" method="post" onsubmit="return false;">
                    <p class="rowtitle">Insert Products</p>
                    <select class="form-select categories">
                        <option value="" selected>Select Product</option>
                        <?php echo $categories?>
                    </select>
                    <div class="col">
                        <input class="form-control" type="number" min="1" required placeholder="Quantity">
                    </div>
                    <div class="col d-flex">
                        <input type="number" class="col-8 form-control w-50" min="0" placeholder="Price" onmousedown="localStorage.setItem('showcur',this.parentNode.children[1].value)">
                        <select type="text" class="form-select w-50" onmousedown="localStorage.setItem('addoldcur',this.value)" onchange="setPriceFilter(this,this.parentNode.children[0],localStorage.getItem('addoldcur'));localStorage.setItem('showcur',this.value);">
                            <option value="USD" selected>USD</option>
                            <option value="sayrafa">LBP (Sayrafa)</option>
                            <option value="bm">LBP (Black Market)</option>
                            <option value="omt">LBP (OMT)</option>
                        </select>
                    </div>
                    <button class="btn btn-success" onclick="addP()">Add</button>
                </form>
            </div>
            <div id="plist" class="row d-flex">
                <p class="rowtitle">Sell Products</p>
                    <?php 
                        if(sizeof($products) == 0){
                            echo '<p>No more products</p>';
                        }
                        for($i = 0;$i < sizeof($products);$i++){
                            $sql = 'SELECT * FROM categories WHERE pname="'.$products[$i][1].'"';
                            $res = mysqli_query($conn,$sql);
                            $r1 = mysqli_fetch_assoc($res);
                            echo '<div class="col-lg-4 col-sm-12 col-xs-12 p-2 plistItem" onclick="showSellTool(this)">
                            <div class="col">
                                <img src="'.$r1['img'].'" class="productIcon">
                            </div>
                                <div class="col">
                                    <p>Type</p>
                                    <input type="text" class="form-control" value="'.$products[$i][1].'" disabled >
                                </div>
                                <div class="col">
                                    <p>Quantity</p>
                                    <input type="text" class="form-control" disabled value='.$products[$i][3].'>
                                </div>
                                <div class="row d-flex m-0 p-0">
                                    <div class="col-lg-12">
                                        <p>Price</p>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 p-0 m-0">
                                        <input type="text" class="form-control" style="float:left" disabled value='.$products[$i][4].'>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 p-0 m-0">
                                    <select type="text" class="form-select" onmousedown=localStorage.setItem("showoldcur",this.value) onchange="setPriceFilter(this,this.parentNode.parentNode.children[1].children[0],localStorage.getItem(\'showoldcur\'))">
                                        <option value="USD" selected>USD</option>
                                        <option value="sayrafa">LBP (Sayrafa)</option>
                                        <option value="bm">LBP (Black Market)</option>
                                        <option value="omt">LBP (OMT)</option>
                                    </select>
                                    </div>
                                </div>
                                <input type="text" disabled hidden value="'.$products[$i][0].'">
                            </div>';
                        }
                    ?>
            </div>
        </div>
        <div id="notif" class="notif"></div>
        <footer class="row mb-0 d-flex">
            <div class="col-lg-6 col-sm-12">
                <p>More To See</p>
                <ul>
                    <li><a href="./faqs.html">FAQs</a></li>
                    <li><a href="https://forum.tradestack.test">Forum</a></li>
                    <li><a href="./about.html">About Us</a></li>
                </ul>
            </div>
            <div class="col-lg-6 col-sm-12">
                <p>Get In Touch</p>
                <ul>
                    <li><i class="fa fa-instagram contactLinks"></i></li>
                    <li><i class="fa fa-facebook contactLinks"></i></li>
                    <li><i class="fa fa-twitter contactLinks"></i></li>
                </ul>
            </div>
            <div class="col-12">
                <p id="footerp"><i class="sitenameFooter">Tradestack</i> Team &copy; </p>
            </div>
        </footer>
        <script>
            let footerp = document.getElementById('footerp').innerText += ' '+year;
        </script>
    </body>
</html>