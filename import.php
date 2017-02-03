<?php
$servername ="localhost";
$username ="root";
$password ="";
$dbname ="blogster";
$data = array(
"M. Night",
"Robert Altman",
"Kathryn Bigelow(The",
"Ben Afleck",
"Warren Beatty",
"Lee Unkrich",
"Marc Forster",
"Terrence Mallick",
"Robert Rodriguez",
"Brad Bird",
"George Roy",
"Lewis Milestone",
"Federico Fellini",
"Frank Darabont",
"D.W. Griffith",
"Richard Attenborough",
"David O.",
"Todd Phillips",
"Harold Ramis",
"John Sturges",
"Joel Schumacher",
"Robert Wise",
"Sam Mendes",
"Michael Bay",
"Sergio Leone",
"Cecil B.DeMille",
"Michael Curtiz",
"George Cukor",
"Bryan Singer",
"Charlie Chaplin",
"David Cronenberg",
"John McTierman",
"James Mangold",
"Wes Anderson",
"Kevin Smith",
"Andrew Stanton",
"Mel Brooks",
"Elia Kazan",
"Wes Craven",
"John Landis",
"Paul Thomas",
"Satyajit Ray",
"Mike Nichols",
"Howard Hawks",
"Sidney Lumet",
"Fred Zinnemann",
"Richard Linklater",
"Paul Green",
"Spike Lee",
"Tony Scott",
"Hal Ashby",
"Peter Weir",
"Milos Forman",
"Gore Verbinski(Pirates",
"Terry Gilliam",
"Victor Flaming",
"Cameron Crowe",
"Gus Van",
"George A.",
"Rob Reiner",
"William Wyler",
"John Huston",
"Ingmar Bergman",
"Oliver Stone",
"Steven Soderbergh",
"Sam Raimi",
"David Lynch",
"Tim Burton",
"Michael Mann",
"Ron Howard",
"Frank Capra",
"Brian De",
"George Lucas",
"Fritz Lang",
"Orson Welles",
"Darren Aronofsky",
"Robert Zemeckis",
"John Carpenter",
"John Hughes",
"Billy Wilder",
"John Ford",
"Danny Boyle",
"Peter Jackson",
"David Lean",
"Roman Polanski",
"Ridley Scott",
"Francis Ford",
"Clint Eastwood",
"Akira Kurosawa",
"Ang Lee",
"Coen Brothers",
"David Fincher",
"Woody Allen",
"James Cameroon",
"Quentin Tarantino",
"Christopher Nolan",
"Martin Scorsese",
"Steven Spielberg",
"Alfred Hitchcock",
"Stanley Kubrick"


);
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed:". $conn->connect_error);
} 
foreach ($data as $line){
	$line = explode(" ", $line);
	
$sql = "INSERT INTO user (id_user, title, first_Name,last_Name,email,password)
VALUES (Null,'mr','$line[0]', '$line[1]','@example.com','pw')";
if ($conn->query($sql) === TRUE) {
    echo"New record created successfully";
} else {
    echo"Error:". $sql ."<br>". $conn->error;
}


}

$conn->close();
?>