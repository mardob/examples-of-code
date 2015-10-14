<!DOCTYPE html>
<html>
<head>
	<meta name="author" content="Historický odraz" />
	
	<meta charset="windows-1250">
	<meta content="sk">
	
	<title>Rímske právo</title>

	<meta name="Keywords" content="Historický odraz" />
	<meta name="Description" content="Historický odraz Alpha" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="home" href="http://www.historicky.odraz.sk/" />
	<link rel="stylesheet" href="css/header.css" type="text/css"  />
	<link rel="stylesheet" href="css/body.css" type="text/css"  />
	<link rel="stylesheet" href="css/vocabulary.css" type="text/css"  />
	
</head>

<body>
	<header id="header">
	    <nav>
    		<a href="index.html"><h1>Vyskúšaj sa</h1></a>	
        </nav>
    </header>
	
	<div id="content">
			<article>
				<?php
				require 'library.php';
				$con = conn();
				session_start();
				$_SESSION["questionNum"] = 1;
				foreach($_POST as $name => $content){
					if(($name != "test")&&($name != "languageone")&&($name != "languagetwo")){
						if($content!="X"){ $_SESSION["name"]=$name; }
						else{ $deleteName=$name; }
					}
				}	

				if(($_POST[test]!=null)) {
					$name = $_POST[test];
					$_SESSION["name"]=$name;
					$keyWord = $_SESSION["keyWord"];
					printHeader($name);
    				$sql = "INSERT INTO `$keyWord` (`Name`,`numAccesed`,`lastFilled`) VALUES ('$name','0','1')";
					if ($con->query($sql) === TRUE){
					} else {
						echo "Error creating note: " . mysqli_error($con);
					}
				}else {
					$keyWord = $_SESSION["keyWord"];
					$name = $_SESSION["name"];
					printHeader($name);
					if($_POST[languageone] != "" && $_POST[languagetwo] != ""){
						$sql = "SELECT `Id` FROM `$keyWord` WHERE `Name`='$name'";
						$Id = $con->query($sql);
						$sql = "SELECT `lastFilled` FROM `$keyWord` WHERE `Name`='$name'";
						$lastFilled = $con->query($sql);
						$value = $lastFilled->fetch_assoc();
						$lastFilled = $value["lastFilled"];

						if($lastFilled < 41){
							$languageone = $_POST[languageone];
							$languagetwo = $_POST[languagetwo];
							$question = "{$languageone}&{$languagetwo}";
							$collumName = "{$lastFilled}question";
	 						$sql = "UPDATE `$keyWord` SET `$collumName`='$question' WHERE `Name`='$name'";
							if( $con->query($sql) ){} 
							else {echo " didn't add". mysqli_error($con);}
							$lastFilled = $lastFilled+1;
							$sql = "UPDATE `$keyWord` SET `lastFilled` = '$lastFilled' WHERE `Name`='$name' ";
							if( $con->query($sql) ){
							} else {echo " didn't increment". mysqli_error($con);}

						}
					}
				}

				if($deleteName){
					
					deleteWord($name, $keyWord, $deleteName,  $con);
				}

				printServerData($con);

				$con->close();

		function printHeader($name){
			echo '<div class="title">'.$name.'</div>';
			echo '<section> <form method="post" action="rimpra.php">
				<input type="text" name="languageone" maxlength="20">
				<input type="text" name="languagetwo" maxlength="20">
				<input id="add" type="submit" value="Pridať"> </form>';
		}

		function printServerData($con){
			$keyWord = $_SESSION["keyWord"];
			$name = $_SESSION["name"];
			$sql = "SELECT * FROM `$keyWord` WHERE `Name`='$name'";
			$result = $con->query($sql);
			echo "<table>";
			if ($result->num_rows > 0) {
				$data = $result->fetch_assoc();
				for($i=1;$i<$data["lastFilled"];$i++){
					$questionName = "{$i}question";
					$value = $data[$questionName];
					$val = getSpliter($value);
					$firstLanguage = substr($value, 0, $val);
					$secondLanguage = substr($value, $val+1 );
						echo "<tr> <td> $firstLanguage </td>
							  <td> {$secondLanguage}";    
						echo' <form method="post" action="rimpra.php"><input id="add" name="'.$questionName.'" type="submit" value="X"></form> 
							  </td> </tr>';
				}
				echo "</table>";
			}
		}

				function deleteWord($name, $keyWord, $word, $con){
				if(strlen ($word)==9) {$startnum = (integer) substr($word, 0, 1);} else {$startnum = (integer) substr($word, 0, 2);}
				$sql = "UPDATE `$keyWord` SET `$word`= NULL WHERE `Name`='$name'";
					if( $con->query($sql) ){
						for($i = $startnum; $i < 40; $i ++){
							$question = (string) $i . "question";
							$question2 = (string) ($i + 1)."question";
							$sql = "UPDATE `$keyWord` SET ". $question ."=".$question2." WHERE `Name`='$name'";
							if( $con->query($sql) ){}
						}
						
						$sql =  "UPDATE `$keyWord` SET `lastFilled` = lastFilled - 1 WHERE `Name`='$name'";
						if( $con->query($sql) ){}
					} 
					else {echo " didn't delete". mysqli_error($con);}
				}

	$con->close();
?>

					<a href="rimpra-preklad.php"><button id="test">Vyskúšaj sa</button></a>
		
			</section>
		</article>		
	</div>

</body>
</html>