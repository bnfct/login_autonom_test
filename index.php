<?php
include_once("TableModel.php");

//melyik táblát akarjuk kezelni
$model = new TableModel('employees');

//A classban inicializált táblázat összes oszlopát és összes sorát visszaadja
$results = $model->getAllRecords();
print_r($results);

//Sor frissítése az adatbázisban
//milyen adat szerint
//milyen oszlopban (alapértelmezetten: id)
//majd végül melyik oszlopban milyen adatot szeretnénk frissíteni

$model->updateRecordByPk(10001, 'emp_no', [
    'last_name' => 'Jani'
]);

//használat: megadjuk milyen adat alapján (melyik oszlopban) szeretnénk adatot törölni

$model->deleteRecordByPk(1,"emp_no");
?>