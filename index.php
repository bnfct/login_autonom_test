<?php
include_once("TableModel.php");

//melyik táblát akarjuk kezelni
$model = new TableModel('employees');

//A classban inicializált táblázat összes oszlopát és összes sorát visszaadja
//$results = $model->getAllRecords();

//Sor frissítése az adatbázisban
//milyen adat szerint
//milyen oszlopban (alapértelmezetten: id)
//majd végül melyik oszlopban milyen adatot szeretnénk frissíteni

/*$model->updateRecordByPk(10001, 'emp_no', [
    'last_name' => 'Jani'
]);*/

//használat: megadjuk milyen adat alapján (melyik oszlopban) szeretnénk adatot törölni
//$model->deleteRecordByPk(1,"emp_no");

//Ezzel a függvénnyel kérjük le a dolgozók kombinált adatait melyet HTML-ben kirenderelünk
$results = $model->getEmployeeData();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login Autonom (teszt feladat)</title>
    </head>
    <body>
        <table>
            <thead>
                <th>Employee ID</th>
                <th>Birth date</th>
                <th>First name</th>
                <th>Last name</th>
                <th>Gender</th>
                <th>Title</th>
                <th>Salary</th>
                <th>Department name</th>
            </thead>
            <tbody>
                <?php
                    for ($i = 0; $i < count($results); $i++) {
                        echo "<tr>";
                            echo "<td>".$results[$i]["emp_no"]."</td>";
                            echo "<td>".$results[$i]["birth_date"]."</td>";
                            echo "<td>".$results[$i]["first_name"]."</td>";
                            echo "<td>".$results[$i]["last_name"]."</td>";
                            echo "<td>".$results[$i]["gender"]."</td>";
                            echo "<td>".$results[$i]["title"]."</td>";
                            echo "<td>".$results[$i]["salary"]."</td>";
                            echo "<td>".$results[$i]["dept_name"]."</td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
    </body>
</html>