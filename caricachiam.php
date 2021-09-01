<?php 

require ROOTPATH."/class.Db.php";
require ROOTPATH."/pdo.instantiate.php";
require ROOTPATH."/class.ZebraPagination.php";

// PREPARAZIONE PAGINAZIONE --- inizio --- 

// how many rows should be displayed on a page?
$records_per_page = 10;

// instantiate the pagination object
$pagination = new Zebra_Pagination();

// calcolo numero totale rows della tabella
$queryPag = '
    SELECT COUNT(*) FROM righe
';
$rowsPag = $pdo->query($queryPag)->fetchColumn();

// pass the total number of rows to the pagination class
$pagination->records($rowsPag);

// rows per page
$pagination->records_per_page($records_per_page);

// PREPARAZIONE PAGINAZIONE --- fine --- 

// ATTIVAZIONE PAGINAZIONE
// inserire nella query 
// 'LIMIT ' . (($pagination->get_page() - 1) * $records_per_page) . ', ' . $records_per_page . '
//
// e poi nell'HTML
// <?php $pagination->render();


// https://stackoverflow.com/questions/9511882/sorting-by-date-time-in-descending-order

$query = '
    SELECT * , UNIX_TIMESTAMP(data) AS DATE 
    FROM righe 
    ORDER BY DATE DESC 
    LIMIT 
    ' . (($pagination->get_page() -1) * $records_per_page) . ', ' . $records_per_page . ' 
';

$stmt = $pdo->query($query);

$rows = $stmt->fetchAll();



/* while ($row = $stmt->fetch()) {
    echo $row['nome']."<br />\n";
} */

/* foreach ($rows as $row) {
    echo $row["nome"] ."<br/>";
} */


