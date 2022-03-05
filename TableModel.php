<?php
class TableModel {
    //létrehozunk egy változót amibe majd a táblázatból kinyert adatokat tároljuk
    public $data;
    public $table;

    //Ezzel inicializáljuk az egész classunkat, hogy milyen adatokat kérünk be tőle
    //jelen esetben csak a kezelni kívánt tábla nevét kérjük itt
    public function __construct($tbl="") {
        if($tbl){
            $this->table = $tbl;
        }
    }

    //definiáljuk az adatbázis csatlakozásunkat
    public function get_connection() {
        $dsn = "mysql:host=localhost;dbname=employees";
        $user = "root";
        $passwd = "";
        $conn = new PDO($dsn, $user, $passwd);
        return $conn;
    }

    //ez a függvény adja vissza a kombinált adatokat a dolgozókról
    public function getEmployeeData($sel=array()) {
        $conn = $this->get_connection();
        $query = $conn->prepare("SELECT employees.emp_no, employees.birth_date, employees.first_name, employees.last_name, employees.gender, titles.title, salaries.salary, dept_emp.dept_name FROM employees INNER JOIN titles ON employees.emp_no = titles.emp_no JOIN (SELECT from_date, salary, emp_no FROM salaries A WHERE from_date = (SELECT Max(from_date) FROM salaries B WHERE A.emp_no = B.emp_no)) salaries ON employees.emp_no = salaries.emp_no JOIN (SELECT departments.dept_name, dept_emp.emp_no FROM dept_emp INNER JOIN departments ON dept_emp.dept_no = departments.dept_no) dept_emp ON employees.emp_no = dept_emp.emp_no GROUP BY employees.emp_no LIMIT 1000");
        $query->execute([]);
        return $query->fetchAll();
    }

    //Ezzel a funkcióval az adott táblának az összes oszlopát és adatát kitudjuk nyerni
    public function getAllRecords($sel=array()) {
        $conn = $this->get_connection();
        $query = $conn->prepare("SELECT * from ". $this->table);
        $query->execute([]);
        return $query->fetchAll();
    }

    //Ezzel a funkcióval tömbösítjük a meghíváskor kapott adatokat melyet majd módosításnál fogunk használni
    private function getColsAndValues($data) {
        $cols = [];
        $values = [];
        foreach ($data as $key=> $value) {
            $cols[] =  $key;
            $values[] =  $value;
        }
        return [
            'cols' => $cols,
            'values' => $values
        ];
    }

    //updateljük a sort az adatbázisban ID alapján
    public function updateRecordByPk($id, $pk='id', $data=[]) {
        //elindítjuk a csatlakozást
        $conn = $this->get_connection();
        //egy külön funkcióba tömbösítjük a kapott adatokat
        $cvs = $this->getColsAndValues($data);
        //egy saját funkciónkba a kapott adatokból kigeneráljuk az update sql kódot
        $sql = $this->getUpdateSQL($cvs['cols'], $pk);
        //átadjuk az sql értéket
        $query = $conn->prepare($sql);
        //a két arrayt össze mergeljük
        $res = array_merge($cvs['values'],[$id]);
        //végül lefuttatjuk az update querrynket
        $query->execute($res);
    }

    //ez a funkció csinál sql kódot abból amit átadtál neki
    //hogy az efelett lévő funkcióba megtudd hívni és futtatni
    private function getUpdateSQL($data, $pk) {
        $sql = "UPDATE ". $this->table . " SET ";
        $sql .= "";
        foreach ($data as $col) {
            $sql .= "`$col`=?,";
        }
        $sql = rtrim($sql,',');
        $sql .= " WHERE `$pk`=?";
        return $sql;
    }

    //megadott ID alapján törli az adatbázisból a sort
    public function deleteRecordByPk($id, $pk) {
        //csatlakozunk
        $conn = $this->get_connection();
        //sql kód létrehozás
        $query = $conn->prepare("DELETE FROM ".$this->table." WHERE $pk=?");
        //itt pedig átadjuk az adatot (vagy adatokat) melyeket kérdőjeleztünk az sql kódban
        $query->execute([$id]);
        return true;
    }
}
?>