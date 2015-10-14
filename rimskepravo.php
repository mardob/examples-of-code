<!DOCTYPE html>
<html>
<head>
	<meta name="author" content="Historický odraz" />
	
	<meta charset="windows-1250">
	<meta content="sk">
	
	<title>Historický odraz Alpha</title>

	<meta name="Keywords" content="Historický odraz, História, Dejiny, Test, Vyskúšaj sa, Otestuj sa, Preskúšaj sa" />
	<meta name="Description" content="Historický odraz Alpha" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="home" href="http://www.historicky.odraz.sk/" />
	<link rel="stylesheet" href="css/header.css" type="text/css"  />
	<link rel="stylesheet" href="css/body.css" type="text/css"  />
	<link rel="stylesheet" href="css/menu.css" type="text/css"  />
	<link rel="stylesheet" href="css/add.css" type="text/css"  />
</head>
<body>
	<header id="header">
	    <nav>
    		<a href="index.html"><h1>Vyskúšaj sa</h1></a>
			<a href="beta.html"><div id="beta">Beta</div></a>
        </nav>
    </header>
	<div id="content">		
		<section>
						<?php 	
							session_start();
							require 'library.php';
							if($_GET[test]){
								$keyWord = $_GET[test]; 
							} else {$keyWord = $_SESSION["keyWord"]; }

							$serverKW = normalize($keyWord);
								if($keyWord != ""){
									$con = conn();
									if(!$con){die("Conection failed!");}
									
									$_SESSION["keyWord"] = $keyWord;
									$_SESSION["questionNum"] = 1;
									
									echo '<div class="title"> '.$keyWord.'</div>';
									$sql = "SELECT `Name` FROM $serverKW";
									if ( $data = $con->query($sql) ){
										while($cell = $data->fetch_assoc()){
											echo'<form name="form1"  action="rimpra.php" method="post"><a href="rimpra.php"><input type="submit" value="'.$cell['Name'].'" name="'.$cell['Name'].'"></a></form>';
											echo'<form name="form2"  action="rimpra-preklad.php" method="post"><a href="rimpra-preklad.php"><input type="submit" value="Vyskúšaj sa" name="'.$cell['Name'].'"></a></form>';
											echo '<a href="rimpra.php"><div class="topic">';
											echo $cell['Name'];
											echo '</div></a><a href="rimpra-preklad.php"><button class="test"' . $cell['Name'] . ' type="button">Vyskúšaj sa</button></a>';
										}
									}else {
										$sql = getTable($serverKW);
										
										if (mysqli_query($con, $sql)) {
											echo "Table created successfully";
										} else {
    										echo "Error at table creation";
    									}
									}
									mysql_close($con);
								}else {echo "didn't work";}

								function getTable($serverKW){
									$sql = "CREATE TABLE `$serverKW`(	`Id` INT NOT NULL AUTO_INCREMENT,
																		`Name` TEXT,
																		`numAccesed` INT,
																		`lastFilled` INT,
																		`1question` TEXT,
																		`2question` TEXT,
																		`3question` TEXT,
																		`4question` TEXT,
																		`5question` TEXT,
																		`6question` TEXT,
																		`7question` TEXT,
																		`8question` TEXT,
																		`9question` TEXT,
																		`10question` TEXT,
																		`11question` TEXT,
																		`12question` TEXT,
																		`13question` TEXT,
																		`14question` TEXT,
																		`15question` TEXT,
																		`16question` TEXT,
																		`17question` TEXT,
																		`18question` TEXT,
																		`19question` TEXT,
																		`20question` TEXT,
																		`21question` TEXT,
																		`22question` TEXT,
																		`23question` TEXT,
																		`24question` TEXT,
																		`25question` TEXT,
																		`26question` TEXT,
																		`27question` TEXT,
																		`28question` TEXT,
																		`29question` TEXT,
																		`30question` TEXT,
																		`31question` TEXT,
																		`32question` TEXT,
																		`33question` TEXT,
																		`34question` TEXT,
																		`35question` TEXT,
																		`36question` TEXT,
																		`37question` TEXT,
																		`38question` TEXT,
																		`39question` TEXT,
																		`40question` TEXT,
																		PRIMARY KEY (`Id`))";
								return $sql;
							}
							
				$con->close();
				?>
				<form  action="rimpra.php" method="post">
					<input type="text" placeholder="Nový test" onfocus="placeholder=''" onblur="placeholder='Nový test'" name="test" maxlength="20">
					<a href="rimpra.php"><input class="test" type="submit" value="Submit"></a>
				</form>	
		</section>
	</div>
</body>
</html>