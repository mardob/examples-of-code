<!DOCTYPE html>
<html>
<head>
	<meta name="author" content="Historický odraz" />
	
	<meta charset="windows-1250">
	<meta content="sk">
	
	<title>Preklad</title>

	<meta name="Keywords" content="Historický odraz" />
	<meta name="Description" content="Historický odraz Alpha" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="home" href="http://www.historicky.odraz.sk/" />
	<link rel="stylesheet" href="css/header.css" type="text/css"  />
	<link rel="stylesheet" href="css/preklad.css" type="text/css"  />

	<script src="javascript/preklad/vocabulary.js" type="text/javascript" charset="utf-8"></script>
	<script src="javascript/preklad/score.js" type="text/javascript" charset="windows-1250"></script>	
	<script src="javascript/preklad/normalizer.js" type="text/javascript" charset="utf-8"></script>
	
</head>

<body onload = "setInitialQuestion(0)">

	<header id="header">
	    <nav>
    		<a href="index.html"><h1>Vyskúšaj sa</h1></a>
			<a href="alpha.html"><div id="beta">Beta</div></a>
        </nav>
    </header>
	
	<div id="content">
			<div id="table">
				<div id="row">
					<?php
					require 'library.php';
					$con = conn();
					session_start();
					foreach($_POST as $name => $content){
						if($name != "answer"){
							$_SESSION["name"]=$name;
						}
					}
					$name = $_SESSION["name"];	
					$keyWord = $_SESSION["keyWord"];

					if($_POST[answer]===NULL){
						$firstRun = TRUE;
					}else{$firstRun = FALSE; }


					if($firstRun == TRUE){
						$_SESSION["$numOfGiven"] = 1;
						$sql = "SELECT `lastFilled` FROM `$keyWord` WHERE `Name`='$name'";
						$result = $con->query($sql);
						$data = $result->fetch_assoc();
						$_SESSION["numOfWords"] = $data["lastFilled"] - 1;}
						$questionNum = rand(1, $_SESSION["numOfWords"]);


					$questionPosition = $_SESSION["$numOfGiven"]; 
					$numOfQuestions = 20;
					
					$answersList = answerArray($firstRun, $numOfQuestions, $questionPosition);

					printDots($firstRun, $numOfQuestions, $answersList, $_SESSION["$numOfGiven"]);

					if($firstRun == FALSE){ 
						$_SESSION["$numOfGiven"]=$_SESSION["$numOfGiven"] + 1; 
						$questionPosition = $_SESSION["$numOfGiven"];
					}

					$sql = "SELECT * FROM `$keyWord` WHERE `Name`='$name'";
					$result = $con->query($sql);
					if ($result->num_rows > 0) {
						$data = $result->fetch_assoc();

						$questionName = $questionNum . "question";
						$value = $data[$questionName];
						$val = getSpliter($value);
						$firstLanguage = substr($value, 0, $val);
						$secondLanguage = substr($value, $val+1 );
						$_SESSION["answer"] = $secondLanguage;
						$_SESSION["questionNum"]++;
					}

					if($questionPosition >= $numOfQuestions+1){
						echo('<div id="translate">'.$_SESSION["rightAnswers "]."/". $numOfQuestions."   ");
						echo((($_SESSION["rightAnswers "]/$numOfQuestions)*100)."% </div>");
						$_SESSION["rightAnswers "]=0;
					}else{echo'<div id="translate">Prelož text</div>';}	

				echo' <div class="ques"  id="question2"> '.$firstLanguage.' </div>				
					<form action="rimpra-preklad.php" method="post" >
					<textarea id="textarea" name="answer" rows="1" placeholder="Preklad" maxlength="60"></textarea>';
					
				if($questionPosition >= $numOfQuestions+1){
					echo'</form><form action="rimskepravo.php"><a href="rimskepravo.php"><input class="test" type="submit" value="Go back"></a></form>';
				}else{echo '<a href="rimpra-preklad.php"><input class="test" type="submit" value="Skontrolovať"></a></form>';}
				echo '<button id="score" onclick="main(0)" type="button">Repeat</button>';

				

				function checkAnswer() {
					if(!($_POST[answer]===NULL)){
						$rightAnswer = $_SESSION["answer"];
						$userAnswer = $_POST[answer];
						if($rightAnswer == $userAnswer){return TRUE;}
						else{return FALSE;}
					}	
				} 

				function printDots($firstRun, $numOfQuestions, $answersList, $questionNum){
					if($firstRun == FALSE){
						for($i=0;$i<$numOfQuestions;$i++){
							if($answersList[$i]==TRUE){
								echo'<div class="dott"><div class="green" id="empty"></div></div>'; }
							else{  if(($answersList[$i]==FALSE) && ($i<$questionNum)){
										echo'<div class="dott"><div class="red" id="empty"></div></div>'; }
									else{  echo'<div class="dott"><div class="empty" id="empty"></div></div>'; }
								}										
						}
					} else{
						for($i=0;$i<$numOfQuestions;$i++){
							echo'<div class="dott"><div class="empty" id="empty"></div></div>'; 
						}
					}
					echo'</div>';
				}

				function answerArray($firstRun, $numOfQuestions, $questionPosition){
					if($firstRun == TRUE){
						$answersList = array();						
						for($i=0;$i<$numOfQuestions;$i++){	
							(array) $answersList[$i] = FALSE;
						}
					} else { $answersList = $_SESSION["answersList"]; 
								if (checkAnswer() == TRUE){
									$_SESSION["rightAnswers "]++;
									$answersList[$questionPosition-1] = TRUE;
								} else{ 
									$answersList[$questionPosition-1] = FALSE;
								} 
							}
					$_SESSION["answersList"] = $answersList;
					return $answersList;
				}

					$_SESSION["runedAlready"] = TRUE;
					$con->close();

				?>

           	</div>	
		</div>
	</div>
</body>
</html>