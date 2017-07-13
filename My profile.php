
<!DOCTYPE HTML>
<html>
<head>
    <title>My Profile Page</title>
    <link rel="stylesheet" href="css/My%20profile.css"/>
    <style>
        div.alert{display:none ;}
    </style>
</head>
<body>

<header>
    <div class="head_top">
        <div class="logo_name"><img class="siyanelogo" src="images/siyane_logo.jpg">

            <h1>LIBRARY</h1>
            <h3>Siyane National College of Education<br />Veyangoda</h3>

        </div>
    </div>
    <div class="bgimage">
        <nav>
            <ul>
                <li><a href="Member%20Page.php">HOME</a></li>
                <li class="logout"><a href="../index.php">LOGOUT</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="tab">

    <button class="tablinks" onclick="ClickOption(event, 'tableMyProf')" >My Profile</button>
    <div class="ChangeSettings">

        <h3>Click on the buttons to change your settings</h3>
        <button class="tablinks" onclick="ClickOption(event, 'Password')">Password</button>
        <button class="tablinks" onclick="ClickOption(event, 'E-mail')">E-mail</button>
        <button class="tablinks" onclick="ClickOption(event, 'Address')">Address</button>
        <button class="tablinks" onclick="ClickOption(event, 'TP')">Telephone No</button>
    </div>
</div>

<?php
session_start();
include("../database.php");
include("../table.php");
include("../member.php");
include ("../login.php");
$dbObj=database::getInstance();
$dbObj->connect('localhost','root','','lms_db');

$user_id=$_SESSION['id'];
$message='';
$login = new login();
$login->load($dbObj,$user_id);
$m=new member();
$m->load($dbObj,$user_id);

?>

<div id="Password" class="tabcontent">
    <div class="Password">
        <form  method="POST"  autocomplete="off">
            <div class="container">
                <h1>Change the Password</h1><hr />
                <label><b>Current Password</b></label>
                <input type="password" name="curPsw" Placeholder="Enter your current password" required autofocus/>
                <label><b>New Password</b></label>
                <input type="password" name="newPsw" Placeholder="Enter your new password" required/>
                <label><b>Confirm new password</b></label>
                <input type="password" name="conNewPsw" Placeholder="Re enter your new password" required/>
                <button name="savePsw" class="Submitbtn" type="submit">Save Changes</button>
            </div>
        </form>
    </div>
    <?php

    $psw="";
    if (isset($_POST["savePsw"])) {
        $CurPsw=$_POST["curPsw"];
        $curEncriped=md5("$CurPsw");
        $NewPsw=$_POST["newPsw"];
        $ConNewPsw=$_POST["conNewPsw"];
        $sql = "Select password from logins where id = '$user_id' ";
        $result = $login->featuredLoad($dbObj,$sql);
        $psw=mysqli_fetch_row($result)[0];

        if($curEncriped!=$psw){

            $message= "Your current password is incorrect..!!";
            ?> <style>div.alert{display:inline-block;}</style><?php
        } elseif (strlen($NewPsw)>64 or strlen($NewPsw)<8) {
            ?>
            <style>div.alert { display: inline-block;}</style><?php
            $message = "Your password must contain 8-64 characters..!!";

        }elseif($NewPsw!=$ConNewPsw){
            $message= "Your new Password and confirmed password are not matched..!!";
            ?> <style>div.alert{display:inline-block;}</style><?php

        }elseif($curEncriped==$psw){
            $encriptedPsw=md5($NewPsw);
            $login->password="$encriptedPsw";
            $login->update($dbObj);
            $message=  "Your password changed successfully";
            ?> <style>div.alert{display:inline-block;}</style><?php
        }
    }


    ?>

</div>






<div id="E-mail" class="tabcontent">
    <?php
    $emal="";
    $sql= "Select member_email from members where id = '$user_id' ";
    $emailresult= $m->featuredLoad($dbObj,$sql);
    $email = mysqli_fetch_row($emailresult)[0];
    ?>

    <div class="Password">
        <form  method="POST" action="" autocomplete="off">
            <div class="container">
                <h1>Change the E-mail</h1><hr />
                <label><b>Current E-mail address</b></label>
                <input type="text" name="curEmail" id="textEmail" value="<?php echo $email; ?>" readonly />
                <label><b>New E-mail address</b></label>
                <input type="text" name="newEmail" Placeholder="Enter your new E-mail" required autofocus/>
                <button name="saveEmail" class="Submitbtn" type="submit">Save Changes</button>
            </div>
        </form>
    </div>
    <?php if(isset($_POST['saveEmail'])){
        $NewEmail=$_POST['newEmail'];
        $m->member_email="$NewEmail";
        $m->update($dbObj);
        $message=  "Your Email address changed successfully";
        $text='"'.$m->member_email.'"';
        ?><script type="text/javascript"> document.getElementById("textEmail").value= <?php echo $text;?> </script>
        <style>div.alert{display:inline-block;}</style><?php
    }
    ?>
</div>



<div id="Address" class="tabcontent">

    <?php
    $address="";
    $sql= "Select permanent_address from members where id = '$user_id' ";
    $add= $m->featuredLoad($dbObj,$sql);
    $address = mysqli_fetch_row($add)[0];
    ?>

    <div class="Password">
        <form  method="POST" action="" autocomplete="off">
            <div class="container">
                <h1>Change the Address</h1><hr />
                <label><b>Current Address</b></label>
                <textarea name="curAdd" id="textaddress" cols="40" rows="6" readonly ><?php echo $address;?></textarea>
                <label><b>New Address</b></label>
                <textarea name="newAdd"  cols="40" rows="6" required autofocus></textarea>
                <button name="saveAddress" class="Submitbtn" type="submit">Save Changes</button>
            </div>
        </form>
    </div>

    <?php if(isset($_POST['saveAddress'])){
        $NewAdd=$_POST['newAdd'];
        $m->permanent_address="$NewAdd";
        $m->update($dbObj);
        $message=  "Your Address changed successfully";
        ?> <style>div.alert{display:inline-block;}</style><?php
        $text='"'.$m->permanent_address.'"';
        ?><script type="text/javascript"> document.getElementById("textaddress").value= <?php echo $text;?> </script><?php

    }
    ?>
</div>

<div id="TP" class="tabcontent">

    <?php
    $tel="";
    $sql= "Select contact_no from members where id = '$user_id' ";
    $tp= $m->featuredLoad($dbObj,$sql);
    $tel = mysqli_fetch_row($tp)[0];
    ?>

    <div class="telephoneNo">
        <form  method="POST" action="" autocomplete="off">
            <div class="container">
                <h1>Change the Telephone No.</h1><hr />
                <label><b>Current Telephone No.</b></label>
                <input type="text" name="curTP" id="contactNo" value="<?php echo $tel ;?>" readonly/>
                <label><b>New Telephone No.</b></label>
                <input type="text" name="newTP"  Placeholder="Enter your Telephone No." required autofocus/>
                <button name="saveTP" class="Submitbtn" type="submit">Save Changes</button>
            </div>
        </form>
    </div>
    <?php if(isset($_POST['saveTP'])){
        $NewTP=$_POST['newTP'];
        if(!(is_numeric($NewTP))|| !(strlen($NewTP)==10)){
            $message="Invalid Contact Number !!";
            ?> <style>div.alert{display:inline-block;}</style><?php
        }
        else{
            $m->contact_no="$NewTP";
            $m->update($dbObj);
            $message=  "Your Telephone Number changed successfully";
            ?> <style>div.alert{display:inline-block;}</style><?php
            $text='"'.$m->contact_no.'"';
            ?><script type="text/javascript"> document.getElementById("contactNo").value= <?php echo $text;?> </script><?php
        }
    }
    ?>

</div>

<div id="tableMyProf" class="tabcontent">
    <table class =MyprofTable">
        <th align="center" class ="tableCaption" colspan="2"><h1>My Profile Details</h1> </th>
        <tr>
            <th>Member ID</th>
            <td><?php echo $m->id ?></td>
        </tr>
        <tr>
            <th>Name</th>
            <td><?php echo $m->member_name ?></td>
        </tr>
        <tr>
            <th>Full Name</th>
            <td><?php echo $m->member_fullname ?></td>
        </tr>
        <tr>
            <th>Member Type</th>
            <td><?php echo $m->member_type ?></td>
        </tr>
        <tr>
            <th>Joined Date</th>
            <td><?php echo $m->join_date ?></td>
        </tr>
        <tr>
            <th>Addmission Date</th>
            <td><?php echo $m->addmission_date ?></td>
        </tr>
        <tr>
            <th>Permanent Address</th>
            <td><?php echo $m->permanent_address ?></td>
        </tr>
        <tr>
            <th>Current Address</th>
            <td><?php echo $m->current_address ?></td>
        </tr>
        <tr>
            <th>E-mail</th>
            <td><?php echo $m->member_email ?></td>
        </tr>
        <tr>
            <th>Contact No.</th>
            <td><?php echo $m->contact_no ?></td>
        </tr>
    </table>
</div>

<div class="alert">
    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
    <?php echo $message;?>
</div>


<script>
    function ClickOption(evt, optionName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(optionName).style.display = "inline-block";
        evt.currentTarget.className += " active";
    }
</script>

</body>
</html>

<?php
$dbObj->closeConnection();
?>




