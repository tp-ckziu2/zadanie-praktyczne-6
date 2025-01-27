<?php
    $conn = mysqli_connect("localhost", "root", "", "rzeki");
    $query1 = 'SELECT nazwa, rzeka, stanOstrzegawczy, stanAlarmowy, stanWody FROM wodowskazy JOIN pomiary ON pomiary.wodowskazy_id = wodowskazy.id WHERE dataPomiaru = "2022-05-05"';
    $query2 = 'SELECT nazwa, rzeka, stanOstrzegawczy, stanAlarmowy, stanWody FROM wodowskazy JOIN pomiary ON pomiary.wodowskazy_id = wodowskazy.id WHERE dataPomiaru = "2022-05-05" AND stanWody > stanOstrzegawczy';
    $query3 = 'SELECT nazwa, rzeka, stanOstrzegawczy, stanAlarmowy, stanWody FROM wodowskazy JOIN pomiary ON pomiary.wodowskazy_id = wodowskazy.id WHERE dataPomiaru = "2022-05-05" AND stanWody > stanAlarmowy';
    $query4 = 'SELECT dataPomiaru, AVG(stanWody) AS stanWody FROM pomiary GROUP BY dataPomiaru';

    $waterStatesResult = [];
    if(isset($_POST['pola'])) {
        if($_POST["pola"] == 'wszystkie')
            $waterStatesResult = mysqli_query($conn, $query1);
        else if($_POST["pola"] == 'ostrzegawcze')
            $waterStatesResult = mysqli_query($conn, $query2);
        else if($_POST["pola"] =='alarmowe')
            $waterStatesResult = mysqli_query($conn, $query3);
    }
    else
        $waterStatesResult = mysqli_query($conn, $query1);

    $avgWaterStates = mysqli_query($conn, $query4);

    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Poziomy rzek</title>
    <link rel="stylesheet" href="styl.css">
</head>
<body>
    <header>
        <div class="left">
        	<img src="obraz1.png" alt="Mapa Polski">
        </div>
        <div class="right">
        	<h1>Rzeki w województwie dolnośląskim</h1>
        </div>
    </header>
    <nav>
        <form method="post" action="poziomRzek.php">
            <label for="pola1" class="label">Wszystkie <input type="radio" name="pola" id="pola1" class="radio" value="wszystkie"></label>
            <label for="pola2" class="label">Ponad stan ostrzegawczy <input type="radio" name="pola" id="pola2" class="radio" value="ostrzegawcze"></label>
            <label for="pola3" class="label">Ponad stan alarmowy <input type="radio" name="pola" id="pola3" class="radio" value="alarmowe"></label>
            <button>Pokaż</button>
        </form>
    </nav>
    <main>
        <div class="main_left">
            <h3>Stany na dzień 2022-05-05</h3>
            <table>
                <tr>
                    <th>Wodomierz</th>
                    <th>Rzeka</th>
                    <th>Ostrzegawczy</th>
                    <th>Alarmowy</th>
                    <th>Aktualny</th>
                </tr>
				<!-- Skrypt php nr 1 -->
                <?php 
                
                    while($row = mysqli_fetch_array($waterStatesResult)) {
                        echo "<tr>";
                            echo "<td> $row[0] </td>";
                            echo "<td> $row[1] </td>";
                            echo "<td> $row[2] </td>";
                            echo "<td> $row[3] </td>";
                            echo "<td> $row[4] </td>";
                        echo "</tr>";
                    }
                
                ?>
            </table>
        </div>
        <div class="main_right">
            <h3>Informacje</h3>
            <ul>
                <li>Brak ostrzeżeń o burzach z gradem</li>
                
                <li>Smog w mieście Wrocław</li>
                <li>Silny wiatr w Karkonoszach</li>
            </ul>
            <h3>Średnie stany wód</h3>
            <?php 
            
            while($row = mysqli_fetch_assoc($avgWaterStates)) {
                echo "<p>";
                echo $row["dataPomiaru"] . ": ";
                echo $row["stanWody"];
                echo "</p>";
            }
            
            ?>
            <a href="https://komunikaty.pl">Dowiedz się więcej</a>
            <img src="obraz2.jpg" alt="rzeka">
        </div>
    </main>
    <footer>
        <p>Stronę wykonał: 02981498194</p>
    </footer>
</body>
</html>
