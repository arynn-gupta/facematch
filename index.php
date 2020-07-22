<?php
session_start(); 
$link = mysqli_connect("localhost", "username", "password", "databasename");
if(mysqli_connect_error())
    die("Unable to connect to the server !");
else
{
    $user1 = $_POST['user1'] ;
    $like1 = $_POST['like1'] ;
    $user2 = $_POST['user2'] ;
    $like2 = $_POST['like2'] ;
    $newquery = "";
    $query1 = "SELECT * FROM users WHERE rollno = '".$user1."'";
    $query2 = "SELECT * FROM users WHERE rollno = '".$user2."'";
    $result1 = mysqli_query($link, $query1);
    $result2 = mysqli_query($link, $query2);
    if($user1!=0 AND $user2 !=0)
    {
        if (mysqli_num_rows($result1)==1)
        {
            $row=mysqli_fetch_array($result1);
            $newquery = "UPDATE users SET likes = '".($row['likes']+1)."' , views = '".($row['views']+1)."' WHERE users.rollno = ".$user1."";
            mysqli_query($link, $newquery);
        }
        if (mysqli_num_rows($result2)==1)
        {
            $row=mysqli_fetch_array($result2);
            $newquery = "UPDATE users SET likes = '".($row['likes']+1)."' , views = '".($row['views']+1)."' WHERE users.rollno = ".$user2."";
            mysqli_query($link, $newquery);
        }
        if (mysqli_num_rows($result1)==0)
        {
            $newquery = "INSERT INTO users (rollno, likes, views) VALUES('".$user1."', '".$like1."', '1')";
            mysqli_query($link, $newquery);
        }
        if (mysqli_num_rows($result1)==0)
        {
            $newquery = "INSERT INTO users (rollno, likes, views) VALUES('".$user2."', '".$like2."', '1')";
            mysqli_query($link, $newquery);
        }
    }
}
?>
<!doctype html>
<html lang="en">
    <head>
        <title>facematch</title>
        <link rel="shortcut icon" href="img/icon.png" type="image/x-icon">
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1 , shrink-to-fit=no" />
        <meta name="author" content="CYBORG">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <style type="text/css">
        html{
            background: url(img/bg.jpg) no-repeat center center fixed;
            -webkit-background-size: cover; /* For WebKit*/
            -moz-background-size: cover;    /* Mozilla*/
            -o-background-size: cover;      /* Opera*/
            background-size: cover; 
            height:100vh;
        }
        ::-webkit-scrollbar{
            width:8px;
        }

        ::-webkit-scrollbar-track {
            background-color: black;
        }

        ::-webkit-scrollbar-thumb{
            border-radius: 6px;
            background: linear-gradient(transparent,#545454);
        }

        body{
            text-align:center;
            background:none;
        }
        h1{
            color:#282C35;
            font-style:italic;
        }
        .container{
            text-align:center;
            padding:20px;
            margin-bottom:20px ;
        }
        .card{
            padding:0px;
        }
        .card-img-top{
            width:100% ;
            height:30vw ;
        }
        .vs{
            position:relative;
            top:40%;
            bottom:60%;
            font-size:4vw;
        }
        .like_a{
        opacity: 0;
        position: absolute;
        top: 15vw;
        left: 50%;
        width:10%;
        }
        .like_b{
        opacity: 0;
        position: absolute;
        top: 15vw;
        left: 50%;
        width:10%;
        }
        .like-unhide{
        transform: translate(-50%, -50%);
        width:20%;
        transition: width 1s ease;
        opacity: 1 !important;
        }
        .card-img-top:hover {
        box-shadow: 0 0 20px rgba(33,33,33,.2); 
        }
        </style>
    </head>
    <body>
        <form method="post" name="form"> 
            <input id="user1" type="hidden" name="user1" value="">
            <input id="like1" type="hidden" name="like1" value="">
            <input id="user2" type="hidden" name="user2" value="">
            <input id="like2" type="hidden" name="like2" value="">
        <h1 class="display-3">facematch (PSIT)</h1>
        <p class="text-muted" style="margin:30px;">a battle between the ulgiest candid's &#128540;</p>
        <div class="container-fluid">
            <div class="row" style="padding:0px 20px;">
                <div class="card col-1" style="background:none; border:none; padding:15px;"></div>
                <div class="card col-4 shadow p-1 mb-5 bg-white rounded">
                    <img class="card-img-top" id="face1" src="img/loading.gif"/>
                    <img class="card-img like_a" src="img/like.png">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12" id="rollno1" style="white-space: nowrap;"></div>
                        </div>
                    </div>
                </div>
                <div class="card col-2" style="background:none; border:none; padding:15px;">
                    <span class="card-title text-muted vs" >VS</span>
                </div>
                <div class="card col-4 shadow p-1 mb-5 bg-white rounded">
                    <img class="card-img-top" id="face2" src="img/loading.gif" >
                    <img class="card-img like_b" src="img/like.png">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12" id="rollno2" style="white-space: nowrap;"></div>
                        </div>
                    </div>
                </div>
                <div class="card col-1" style="background:none; border:none; padding:15px;"></div>
            </div>
            <h1>#Leaderboard</h1>
            </br>
            <table class="table table-hover table-dark mx-auto">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Roll No.</th>
                    <th scope="col">Likes</th>
                    <th scope="col">Views</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $leadquery = "SELECT * FROM users ORDER BY likes DESC";
                $leads=mysqli_query($link, $leadquery);
                for ($x = 1; $x <= 10; $x++) {
                $leadsarray=mysqli_fetch_array($leads);
                if ($x==1){
                    echo '<tr class="bg-secondary">
                        <th scope="row">'.$x.'</th>
                        <td><a style="text-decoration:none;color:white;" href="https://erp.psit.in/assets/img/Simages/'.$leadsarray[rollno].'.jpg" target="_blank">'.$leadsarray[rollno].'</a></td>
                        <td>'.$leadsarray[likes].'</td>
                        <td>'.$leadsarray[views].'</td>
                        </tr>';
                }
                else{
                    echo '<tr>
                        <th scope="row">'.$x.'</th>
                        <td><a style="text-decoration:none;color:white;" href="https://erp.psit.in/assets/img/Simages/'.$leadsarray[rollno].'.jpg" target="_blank">'.$leadsarray[rollno].'</a></td>
                        <td>'.$leadsarray[likes].'</td>
                        <td>'.$leadsarray[views].'</td>
                        </tr>';
                }
                }
                ?>
                </tbody>
            </table>
            </br>
            <?php
            $viewlink = mysqli_connect("sql313.epizy.com", "epiz_25847926", "agruypatnaweb", "epiz_25847926_viewcount");
            if(mysqli_connect_error())
            die("Unable to connect to the server !");
            else{
                $viewquery="SELECT * FROM viewcount";
                $viewresult = mysqli_query($viewlink, $viewquery);
                $viewarray= mysqli_fetch_array($viewresult);
                echo "<h3 style='color:white;'> Page Views : ".$viewarray['count']."</h3>";
                if(!isset($_SESSION['views']))
                {   
                    $newviewquery="UPDATE viewcount SET count ='".($viewarray['count']+1)."' WHERE viewcount.id=1";
                    mysqli_query($viewlink, $newviewquery);
                    $_SESSION['views'] = 1; 
                }
            }
            ?>
            </br>
            <h1><a  style="color:#3A3A40; text-decoration:none;" href = "mailto: iamkirashinigami@gmail.com">Contact Us</a><h1>
            </br>
            <span style="color:white; font-size:2%;" >Project-MAHAKAL<span>
            </br>
        </div>
        </form>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script type="text/javascript">
            $(document).ready(function () {
            $("body").on("contextmenu", function (e) {
            return false;
            });
            });
            var max=27500;
            var min=20000;
            var user1=0;
            var user2=0;
            function face1() 
            {
                var cal=(Math.floor(Math.random() * (max - min + 1) ) + min);
                var random="https://erp.psit.in/assets/img/Simages/"+cal+".jpg";
                var img = new Image();
                img.onload = function(){ $("#face1").attr("src",random); $("#rollno1").html("#"+cal);}; 
                img.onerror = function(){ face1(); };
                img.src = random;
                user1=cal;
            }
            function face2() 
            {
                var cal=(Math.floor(Math.random() * (max - min + 1) ) + min);
                var random="https://erp.psit.in/assets/img/Simages/"+cal+".jpg";
                var img = new Image();
                img.onload = function(){ $("#face2").attr("src",random); $("#rollno2").html("#"+cal);}; 
                img.onerror = function(){ face2(); };
                img.src = random;
                user2=cal;
            }
            face1();
            face2();
            $("#face1").one('click', function(){
                $("#user1").val(user1);
                $("#like1").val(1);
                $("#user2").val(user2);
                $("#like2").val(0);
                $(".like_a").addClass("like-unhide");
                setTimeout(function() {
                    $(".like_a").removeClass("like-unhide");
                }, 500);
                setTimeout(function() {
                    form.submit();
                }, 1000);});
            $("#face2").one('click', function(){
                $("#user1").val(user1);
                $("#like1").val(0);
                $("#user2").val(user2);
                $("#like2").val(1);
                $(".like_b").addClass("like-unhide");
                setTimeout(function() {
                    $(".like_b").removeClass("like-unhide");
                }, 500);
                setTimeout(function() {
                    form.submit();
                }, 1000);});
        </script>
    </body>
</html>
