<?php
$action = $_REQUEST['action'];
if (!empty($action)) {
    require_once 'incudes/players.php';
    $obj = new player();
}

if ($action == 'adduser' && !empty($_POST)) {
    $pname = $_POST['username'];
    $pemail = $_POST['email'];
    $pphone = $_POST['phone'];
    $pphoto = $_FILES['photo'];
    $pid = (!empty($_POST['userid'])) ? $_POST['userid'] : '';
    
    // File upload
    $image = "";
    if (!empty($pphoto['name'])) {
        $imagename = $obj->uploadPhoto($pphoto);
        $playerData = [
            'player_name' => $pname,
            'player_email' => $pemail,
            'player_phone' => $pphone,
            'player_image' => $imagename,
        ];
    } else {
        // Set a default image if no photo provided
        $defaultImage = 'default_image.jpg'; // adjust this to your default image path
        $playerData = [
            'player_name' => $pname,
            'player_email' => $pemail,
            'player_phone' => $pphone,
            'player_image' => $defaultImage,
        ];
    }
    
    // Add player
    $playerId = $obj->add($playerData);
    
    if (!empty($playerId)) {
        $player = $obj->getRow('id', $playerId);
        echo json_encode($player);
        exit();
    }
}


//get  page values and users
if($action=="getusers"){
$page=(!empty($_GET['page']))?$_GET['page'] :1;
$limit=4;
$start=($page-1)*$limit;

$players=$obj->getRows($start,$limit);

if(!empty($players)){
    $playerlist=$players;
}
else{
    $playerlist=[];
}
$total=$obj->getCount();
$playerArr=['count'=>$total,'players'=>$playerlist];
echo json_encode($playerArr);
exit();



}
?>
