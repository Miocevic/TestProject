<html>
<head>
    <link rel="stylesheet" href="style.css" type="text/css">
    <title>SOCIAL NETWORK</title>
</head>
<body>
<form action="index.php" method="post">
    <div id = 'h1' style="text-align: center">
        <h1>SOCIAL NETWORK</h1>
    </div>

    <div id='h3' style="text-align: center;">
        <h3>Select People from Menu</h3>
    </div>
    <?php

    $json_data=file_get_contents("data.json");
    $json=json_decode($json_data,true);
    echo "<div id='select'><select name='peoples'    >";
            echo "<option value='selected'>- - - -Select Person- - - -</option>";
        foreach ($json as $people){
            echo "<option value='".$people['id']."'>" . $people['firstName']." ". $people['surname'] . "</option>";
    }
    echo "</select></div><br /><br />";
    ?>
   <div id="input"><input type="submit" value="SELECT"></div>

</form>
</body>
</html>

<?php

if(isset($_POST['peoples']) AND is_numeric($_POST['peoples']))
{
    $id = $_POST['peoples'];
    $json_data=file_get_contents("data.json");
    $json=json_decode($json_data,true);

    foreach($json as $people)
        if($people['id']==$id)
        {
            $name = strtoupper($people['firstName']) . " " . strtoupper($people['surname']);
            $age = $people['age'];
            $gender = ucfirst($people['gender']);
        }

        if(is_numeric($age))
            echo " <div id='person'><b>SELECTED PERSON:<br/><br/>".$name."<br />".$age." years<br />" .$gender."<br /><br /></b></div>";
        else
            echo " <div id='person'><b>SELECTED PERSON:<br/><br/>".$name."<br />".$gender."<br /><br /></b></div>";

    $DirectFriendsList = "<b>DIRECT FRIENDS </b> <br />";
    $DirectFriendsList.="----------------------------------"."<br />";
    $directFriendsArray=array();
    foreach($json as $people)
    {
        if (in_array($id,$people['friends']))
        {
            array_push($directFriendsArray,$people['id']);

            $DirectFriendsList .= "||   <b>" . $people['firstName'] ." ". $people['surname'] . "</b><br />";
            if(is_numeric($people['age']) AND $people['age']>0)
                $DirectFriendsList .= "||   <b>" . $people['age'] . " years "."</b><br />";
            $DirectFriendsList .= "||   <b>" . ucfirst($people['gender']) . "</b><br />";
            $DirectFriendsList .= "----------------------------------" . "<br />";
        }
    }
    echo '<tr>';

        echo "<div id=direct>".$DirectFriendsList."</div><br /><br />";

    $friendFriendsArray=array();

    foreach($json as $people) {
        foreach ($people['friends'] as $friend)
        {
            if (in_array($friend,$directFriendsArray))
                array_push($friendFriendsArray,$people['id']);
        }
    }

    $friendFriendsArray=array_unique($friendFriendsArray);
    $friendFriendsArray = array_diff($friendFriendsArray, $directFriendsArray);
    $friendFriendsArray = array_diff($friendFriendsArray, array($id));

    $FriendsList="<b>FRIENDS OF FRIENDS </b> <br />";
    $FriendsList.="----------------------------------"."<br />";
    foreach($json as $people)
    {
        if(in_array($people['id'],$friendFriendsArray))
        {
            $FriendsList .= "||   <b>" . $people['firstName'] ." ". $people['surname'] . "</b><br />";
            if(is_numeric($people['age']) AND $people['age']>0)
                $FriendsList .= "||   <b>" . $people['age'] . " years "."</b><br />";
            $FriendsList .= "||   <b>" . ucfirst($people['gender']) . "</b><br />";
            $FriendsList .= "----------------------------------" . "<br />";
        }
    }
    echo "<div id=friends>".$FriendsList."</div> <br /><br />";

    $SuggestedList="<b>SUGGESTED FRIENDS </b> <br />";
    $SuggestedList.="----------------------------------"."<br />";

    $i=0;
    foreach($json as $people)
    {
        foreach($people['friends'] as $friend)
        {
            if (in_array($friend,$directFriendsArray) AND $people['id']!=$id AND !(in_array($people['id'],$directFriendsArray)))
            {
                $i++;

            }
        }
        if($i>=2)
        {
            $SuggestedList .= "||   <b>" . $people['firstName'] ." ". $people['surname'] . "</b><br />";
            if(is_numeric($people['age']) AND $people['age']>0)
                $SuggestedList .= "||   <b>" . $people['age'] . " years "."</b><br />";
            $SuggestedList .= "||   <b>" . ucfirst($people['gender']) . "</b><br />";
            $SuggestedList .= "----------------------------------" . "<br />";
        }
        $i=0;
    }
    echo "<div id='suggested'>".$SuggestedList."</div> <br /><br />";


}

?>
