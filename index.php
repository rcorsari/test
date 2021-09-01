<?php 
  define('ROOTPATH', __DIR__);
  require ROOTPATH."/caricachiam.php";
?>
<!doctype html>
<html lang="en">

  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="./favicon.ico" />
    <link rel="icon" type="image/png" href="./favicon-32x32.png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="./zebra_pagination.css" rel="stylesheet">

    <style>

      /**https://stackoverflow.com/a/1956141/3446280 */
      #provDiv {
        position: absolute; /** e 'relative' sul parent, la cella, dove si posiziona il DIV */
        top: 0; 
        right: 0; 
        width: auto;
        max-width: 200px;
        background: greenyellow;  
        text-align: center;
        padding-top: 5px;
        /*ADDED*/
        -webkit-box-shadow: 5px 5px 15px #444;
        -moz-box-shadow: 5px 5px 15px #444;
        box-shadow: 5px 5px 15px #444;
      }

      #provDiv > ul > li {
        list-style-type: none;
        padding: 0 5px;
        border-bottom: 1px solid black;
        /** fondamentale per avere tutto su una riga e DIV che si allarga e stringe */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }

      #provDiv > ul > li + li {
        margin-top: 10px;
      }
    </style>

    <title>Registro Chiamate</title>
  </head>

  <body>

    <div class="container-fluid">
    
      <div class="d-flex flex-wrap">
        <div class="col-xl-6 col-sm-12">
          <div class="d-flex justify-content-start">
              <h1 class="p-1">Registro Chiamate - </h1>
              <p id="provincie"></p>
          </div>
        </div>
        <div class="col-xl-6 col-sm-12">
          <div class="d-flex flex-wrap justify-content-end">
              <div>
                <button class="btn btn-primary m-3" id="AggiungiRiga" type="submit" value="Submit" >+ Riga</button>
              </div>
              <div>
                <button class="btn btn-danger m-3" id="EliminaRiga" type="submit" value="Submit" >- Riga</button>
              </div>
              <div>
                <button class="btn btn-success m-3" id="SalvaRiga" type="submit" value="Submit" >Salva</button>
              </div>
          </div>
        </div>
      </div>

      <div class="table-responsive" style="z-index: 9999;">
        <table style="min-width:1000px;" class="table table-striped table-hover table-bordered text-center" id="tabella">

          <thead>
            <tr>
              <th data-dbrow="riga" scope="col" style="width: 5%">#</th>
              <th data-dbrow="id" scope="col" style="width: 5%">ID</th>
              <th data-dbrow="nome" scope="col" style="width: 30%">Nome</th>
              <th data-dbrow="tel" scope="col" style="width: 11%">Telefono</th>
              <th data-dbrow="mail" scope="col" style="width: 29%">Email</th>
              <th data-dbrow="device" scope="col" style="position:relative; width: 10%">
                  <select id="select1" class="form-select form-select-sm">
                      <option value="$" selected disabled>- Selez Disp -</option>
                      <option value="val1">HDD USB</option>
                      <option value="val1">HDD</option>
                      <option value="val2">Chiavetta</option>
                      <option value="val3">SD microSD</option>
                      <option value="val4">SSD</option>
                      <option value="val5">Smartphone</option>
                      <option value="val6">NAS</option>
                      <option value="val7">RAID</option>
                  </select>
                  <div id="provDiv"></div>
              </th>
              <th data-dbrow="iscli" scope="col" style="width: 5%">&Egrave; Cli</th>
              <th data-dbrow="prov" scope="col" class="cellaProv" style="width: 5%">Prov</th>

            </tr>
          </thead>

          <tbody id="table1bd">

            <?php 
              $num = 1 + $stmt->rowCount();
              foreach ($rows as $row): ?>

              <tr>
                <th><?= --$num; ?></th>
                <td><?= $row['id'] ?></td>
                <td contenteditable="true"><?= $row['nome'] ?></td>
                <td contenteditable="true"><?= $row['tel'] ?></td>
                <td contenteditable="true"><?= $row['mail'] ?></td>
                <td><?= $row['device'] ?></td>
                <td contenteditable="true"><?= ($row['iscli'] ? "Si" : "No") ?></td>            
                <td contenteditable="true"><?= $row['prov'] ?></td>            
              </tr>

            <?php endforeach ?>

          </tbody>

        </table>
      </div>

      <?php $pagination->render(); ?>

    </div>

    

    <pre></pre>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

    <script>
        // DICHIARAZIONE COSTANTI
        const date = new Date();

        const table1 = document.getElementById("tabella");
        const t1cells = table1.rows[0].cells;
        const tBody = document.getElementsByTagName("tbody")[0];
        const provDiv = document.getElementById("provDiv");

        const cellDispo = tBody.rows[0].cells[5];
        const cellCli = table1.rows[1].cells[6];
        const cellProv = table1.rows[1].cells[7];

        const select1 = document.getElementById("select1");

        const btn1 = document.getElementById("AggiungiRiga");
        const btn2 = document.getElementById("EliminaRiga");
        const btn3 = document.getElementById("SalvaRiga");

        
        // UTILITA' GENERA LA STRINGA DELLA DATA
        function prettyDate(date) {
          var months =  ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu',
                        'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'];

          return date.getUTCDate() + ' ' + months[date.getUTCMonth()] + ' ' + date.getUTCFullYear();
        }


        
        // IN APERTURA AGGIUNGE DATA all' H1 (ma non serve a un tubo) 
        data = document.createTextNode(prettyDate(date));
        document.querySelector("h1").appendChild(data);



        // SELECT TENDINA
        select1.addEventListener("change", function() {
            //cellDispo.innerHTML = select1.options[select1.selectedIndex].text;
          if (table1.rows[1].cells[0].innerText == "NUOVA") {
            table1.rows[1].cells[5].innerText = select1.options[select1.selectedIndex].text; // mette dispositivo
            table1.rows[1].cells[6].innerText = "No"; // mette "No" in è cliente
            select1.selectedIndex = "0"; // azzera la tendina del select
            table1.rows[1].cells[6].focus(); // mette il focus sulla cella è cliente
          }
        });



        // Pulsante AGGIUNGE RIGA a tbody
        // SOLO DOPO che si è aggiunta la row , si possono attivare gli addEventListener su sue celle
        btn1.addEventListener("click", function() {
          var curURL = window.location.href;
          if (curURL.includes("=")){
            alert("Per aggiungere una riga, bisogna essere a pagina 1\nOra la app cambierà pagina da se");
            window.location.href = curURL.split(/[?#]/)[0];};
          if (table1.rows[1].cells[0].innerText !== "NUOVA") { // controlla che NON è NUOVA riga
            insRowInTBodyAtTop(table1, 0);  // inserisce riga
            table1.rows[1].cells[0].innerText = "NUOVA"; // inserisce stringa "NUOVA"
            select1.selectedIndex = "0"; // azzera la tendina del select
            table1.rows[1].cells[2].focus(); // mette il focus su Nome
            table1.rows[1].cells[7].addEventListener('input', cercaProvincia);
            table1.rows[1].cells[7].addEventListener('blur', hideProvDiv);
          }
        });



        // Pulsante RIMUOVE RIGA da tbody
        btn2.addEventListener("click", function() {
          if (table1.rows[1].cells[0].innerText == "NUOVA") { // controlla che SIA NUOVA riga
            removeRowInTBodyAtTop (0); // rimuove riga
            //fadeOutEffect("provDiv"); // esperimento che funziona ma tolto
            displayElemByID("provDiv", "none"); // se c'era il DIV province lo toglie
          }
        });



        // Pulsante SALVA RIGA
        // fa validazione e chiama inserisciRiga(tabella)
        btn3.addEventListener("click", function() {
          if (controlloCampi()) {return}; // check dei campi inseriti
          if (table1.rows[1].cells[0].innerText == "NUOVA") { // stringa strategica
            inserisciRiga(table1);
          }
        });
        // document.querySelector("pre").innerHTML = JSON.stringify(parseTable(table1));


        // AGGIUNGE RIGA IN CIMA AL TBODY DI UNA TABLE
        function insRowInTBodyAtTop(table, tBodyId) {

          let rows = table.rows; // le rows della tabella
          let clone = rows[1].cloneNode(true); // clona la riga[1] cioè la seconda
          let body = document.getElementsByTagName("tbody")[tBodyId]; // il tBody[0] è il primo della pagina
          // body.appendChild(clone); appnedchild attaccherebbe al fondo come ultima

          // Get the parent element
          // let parentElement = document.getElementsByTagName("tbody")[0];
          let parentElement = body;
          // Get the parent's first child
          let theFirstChild = parentElement.firstChild;

          // vuota celle prima
          for (var j = 0, col; col = clone.cells[j]; j++) {
            clone.cells[j].innerText = "";
          }  

          // Insert the new element before the first child insertBefore(what, where)
          parentElement.insertBefore(clone, theFirstChild);

          // inserisco span per fare una casella di testo per la ricerca province
          // https://stackoverflow.com/questions/5802663/create-a-span-element-inside-another-element-using-javascript
          // https://stackoverflow.com/questions/13845003/tooltips-for-cells-in-html-table-no-javascript
/*        var provSpan = document.createElement('span')
          provSpan.setAttribute("id", "provSpan");
          provSpan.setAttribute("class", "textSearch");
          document.getElementsByTagName("thead")[tBodyId].rows[0].cells[7].appendChild(provSpan);  */         
        }

        // RIMUOVE RIGA IN CIMA AL TBODY DI UNA TABELLA
        function removeRowInTBodyAtTop (tBodyId) {
            let body = document.getElementsByTagName("tbody")[tBodyId];
            let rows = body.rows;
            if (body.rows.length > 1) {
                body.deleteRow (0);
            }
        }


        // mapRow(headings) OCCORRE PER FAR LAVORARE parseTable(table)
        // https://gist.github.com/WickyNilliams/9252235 parseTable + mapRow
        function mapRow(headings) {
          return function mapRowToObject({ cells }) {
            return [...cells].reduce(function(result, cell, i) {
              const input = cell.querySelector("input,select");
              var value;

              if (input) {
                value = input.type === "checkbox" ? input.checked : input.value;
              } else {
                value = cell.innerText.replace(/(\r\n|\n|\r)/gm, ""); // replace occorre per bug '\n' 
              }

              return Object.assign(result, { [headings[i]]: value });
            }, {});
          };
        }

        // PRENDE TUTTE LE CELLE DI UNA TABELLA E LE TRASFORMA IN ARRAY
        // richiede la funzione mapRow(headings)
        function parseTable(table) {
          // con gli indici [0] e [1] , limitiamo sempre tutto ad una sola riga della TABLE

          var headings = [...table.tHead.rows[0].cells].map(
            //heading => heading.innerText   // non vogliamo il contenuto del TH
            heading => heading.dataset.dbrow // invece del testo TH, modifica per leggere 'data-dbrow' 
          );

          obj = [table1.rows[1]].map(mapRow(headings)); // genera un array di oggetti diciamo bi dimensionale

          // modifica personale per tgliere due colonne in array dalla TABLE
          delete obj[0].id;   // con obj[0].id puntiamo 0 il primo oggetto
          delete obj[0].riga; // con i delete, eliminiamo i campi 'id' e 'riga' dall'array

          // throw new Error("Esecuzione javascript bloccata per debug!"); // debug

          return obj;

        }

        // VALIDAZIONE
        function controlloCampi() {
          let tbody = document.getElementsByTagName("tbody")[0];
          var bodyRows = tbody.rows;
          var nome = bodyRows[0].cells[2].innerText;
          var tele = bodyRows[0].cells[3].innerText;
          var mail = bodyRows[0].cells[4].innerText;
          var disp = bodyRows[0].cells[5].innerText;
          var clie = bodyRows[0].cells[6].innerText;
          var prov = bodyRows[0].cells[7].innerText;
          if ( nome.length <= 2){
            alert("Inserire Nome");
            return true;
          }
          if ( tele.length <= 8){
            alert("Controlla Telefono");
            return true;
          }
          if ( mail.length <= 4 && mail !== "nulla"){
            alert("Inserire Mail");
            return true;
          }
          if ( disp.length <= 2){
            alert("Inserire Dispositivo");
            return true;
          }
          if ( clie.length <= 1){
            alert("E\' Cliente?");
            return true;
          }
          if ( prov.length <= 1 && prov.length > 2){
            alert("Controlla Provincia");
            return true;
          }
          return false;
        }


        // AJAX 1 - INSERT della riga nel DB
        // il pulsante salva è premuto
        function inserisciRiga(table) {
          var url = window.location.href + 'salvariga.php';
          fetch(url, {
            method: 'POST', // or 'PUT'
            headers: {
              'Content-Type': 'application/json',
            },                                      // invio POST
            body: JSON.stringify(parseTable(table)),// body: è quanto viene inviato via POST 
          })                                        // parseTable() toglie colonne 'id' e 'riga'
          .then(response => response.json())
          .then(data => {
            //console.log('Success:', data);
            aggiornaRiga(data);                     // RISPOSTA con 'data' che è nativo API fetch()
          })                                        // chiamiamo aggironaRiga()
          .catch((error) => {
            console.error('Error:', error);
          });
          
        }

        // dopo AJAX 1 TRASFORMA LA RIGA TABELLA da input a normale
        // a riga aggiornata, il ciclo del salvataggio riga finisce.
        function aggiornaRiga(obj) {
          // console.log(obj); // debug
          // throw new Error("Esecuzione javascript bloccata per debug!"); // debug
          var obj = JSON.parse(obj);
          table1.rows[1].cells[0].innerText = obj.riga;
          table1.rows[1].cells[1].innerText = obj.id;
          //document.getElementById("provSpan").style.display="none"; // elimina box ricerca provincia
          displayElemByID("provDiv", "none");

        }


        // AJAX 2 AUTO COMPLETAMENTO PROVINCE
        // AJAX 2 viene attivato dalle routine abbinate al click del btn aggiungi riga
        // precisamente dall'evento 'input' .addEventListener('input', cercaProvincia);
        function cercaProvincia(){
          var url = window.location.href + 'cercaprov.php';
          var text = table1.rows[1].cells[7].innerText;
          text = text.replace(/(\r\n|\n|\r)/gm, ""); // strano bug inserisc '\n' nelle celle
          //console.log(text);
          fetch(url, {
            method: 'POST', // or 'PUT'
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({search: text}), // invia POST di un array [search: "text"]
          })
          .then(response => response.json())
          .then(data => {
            // console.log('Success:', data);
            scriveProv(data);                     // riceve JSON e va a scriveProv()
          })
          .catch((error) => {
            console.error('Error:', error);
          });
          
        }


        // Routine che mostra il risultato AJAX 2
        function scriveProv(obj){
          // fase 1 controlla se c'è testo nella casella, se no 'display: none'
          var text = table1.rows[1].cells[7].innerText;
          text = text.replace(/(\r\n|\n|\r)/gm, ""); // strano bug inserisce '\n' nelle celle
          var blonon = (text.length > 0) ? "block" : "none";
          displayElemByID("provDiv", blonon);

          // fase 2 cancella lista tasto precedene e ricra con risult nuova query
          provDiv.innerHTML = ""; // azzera contenuto
          provDiv.appendChild(makeUL(obj, 5, "provUl")); // mostra la nuova UL

          // fase 3 mette in ascolto la UL per i click e se mai trasferisce il testo cliccato
          // https://stackoverflow.com/a/5116987/3446280
          var ul = document.getElementById('provUl');
          ul.addEventListener('click', function(e) {
              if (e.target.tagName === 'LI'){
                //alert(e.target.innerHTML);
                aggiornaProv(e.target.innerHTML);
              }
          });
        }


        // UTILITA' setta l'attributo display di un elemento tramite il suo ID
        function displayElemByID(id, attribute) {
          element = document.getElementById(id);
          element.style.display = attribute;
        }


        // CREA UN INTERO ELEMENTO UL DA UN ARRAY
        function makeUL(array, nItems, id) {
          // https://stackoverflow.com/a/11128791/3446280
            // Create the list element:
            // console.log(array);
            var list = document.createElement('ul');
            list.setAttribute("id", id);
            list.style.cssText = 'padding: 0px; margin: 0px;';

            for (var i = 0; i < Object.keys(array).length; i++) {
              if (i < nItems){
                // Create the list item:
                var item = document.createElement('li');

                // Set its contents:
                item.appendChild(document.createTextNode(array[i].sigla + " " + array[i].provincia));

                // Add it to the list:
                list.appendChild(item);
              }
            }

            // Finally, return the constructed list:
            return list;
        }


        function hideProvDiv() {
          setTimeout(function() { displayElemByID("provDiv", "none"); }, 800);
          // displayElemByID("provDiv", "none");
        }


        function aggiornaProv(stringa) {
          var prov = stringa.substring(0, 2);
          table1.rows[1].cells[7].innerText = prov;
          table1.rows[1].cells[7].blur();
        }

        function fadeOutEffect(id) {
          var fadeTarget = document.getElementById(id);
          var fadeEffect = setInterval(function () {
              if (!fadeTarget.style.opacity) {
                  fadeTarget.style.opacity = 1;
              }
              if (fadeTarget.style.opacity > 0) {
                  fadeTarget.style.opacity -= 0.1;
              } else {
                  clearInterval(fadeEffect);
              }
          }, 200);
        }
        


/*         function findPos(obj) {
	        var curleft = curtop = 0;
          if (obj.offsetParent) {
            do {
			        curleft += obj.offsetLeft;
			        curtop += obj.offsetTop;
            } while (obj = obj.offsetParent);
          return {curleft, curtop};
          }
        } */


        /*         select2.addEventListener("change", function() {
            cellCli.innerHTML = select2.options[select2.selectedIndex].text;
        }); */


        // alert(table1.rows[0].cells.length);

/*         function tableToJSON(table) {
          var obj = {};
          var row, rows = table.rows;
          for (var i=0, iLen=rows.length; i<iLen; i++) {
            row = rows[i];
            obj[row.cells[0].textContent] = row.cells[1].textContent
          }
          return JSON.stringify(obj);
        } */

        //console.log(tableToJSON(table1)); // {"Name:":"Carlos","Age:":"22"}"

        /*         var map = new Map();
        map.set('name', 'John');
        map.set('id', 11);

        // Get the full content of the Map
        console.log(JSON.stringify([...map])); // Map { 'name' => 'John', 'id' => 11 } */

        /*         btn1.addEventListener("click", function() {
          console.log(console.log(parseTable(table1)));

          var rows = table1.rows;
          var clone = rows[1].cloneNode(true);
          var body = document.getElementsByTagName("tbody")[0];
          // body.appendChild(clone);

          // Get the parent element
          // let parentElement = document.getElementsByTagName("tbody")[0];
          var parentElement = body;
          // Get the parent's first child
          var theFirstChild = parentElement.firstChild;

          // Create a new element
          // let newElement = document.createElement("div")

          for (var j = 0, col; col = clone.cells[j]; j++) {
            clone.cells[j].innerText = "";
          }  

          // Insert the new element before the first child
          parentElement.insertBefore(clone, theFirstChild);

          table1.rows[1].cells[1].innerHTML = prettyDate(date);

        }); */

        /* btn1.addEventListener("click", function() {
          if (controlloCampi()) {return}; // check degli inserimenti
          //console.log(console.log(parseTable(table1)));
          document.querySelector("pre").innerHTML = JSON.stringify(parseTable(table1));
          var n = parseInt(table1.rows[1].cells[0].innerText); // converte integer
          insRowInTBodyAtTop(table1, 0);
          table1.rows[1].cells[0].innerText = n + 1;
          table1.rows[1].cells[1].innerText = prettyDate(date);
        }); */
        

    </script>


  </body>
</html>
