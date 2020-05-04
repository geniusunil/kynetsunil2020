function format_link(link){
    if (link)
     return '<a href="' + link + '" target="_blank" rel="noopener noreferrer">' + link + '</a>';
    else
      return "";
  }


function applyAllFilters(){
//searchStock();
//searchCert();
// searchMinSize();
console.log("inside applyallfilters");
var input, table, tr, td, i;
var filterStock, txtValueStock , filterCert, txtValueCert;

input = document.getElementById("stockSearch");
filterStock = input.value.toUpperCase();
input = document.getElementById("certificateSearch");
filterCert = input.value.toUpperCase();


table = document.getElementById("table-container-table");
tr = table.getElementsByTagName("tr");
for (i = 0; i < tr.length; i++) {
tdStock = tr[i].getElementsByTagName("td")[43];
tdCert = tr[i].getElementsByTagName("td")[5];


if (tdStock && tdCert) {

  txtValueStock = tdStock.textContent || tdStock.innerText;
  txtValueCert = tdCert.textContent || tdCert.innerText;


  if (txtValueStock.toUpperCase().indexOf(filterStock) > -1 && txtValueCert.toUpperCase().indexOf(filterCert) > -1) {
    
    tr[i].style.display = "";
  } else {
    tr[i].style.display = "none";
  }
}       
}

}
function searchStock() {
var input, filter, table, tr, td, i, txtValue;
input = document.getElementById("stockSearch");
filter = input.value.toUpperCase();
table = document.getElementById("table-container-table");
tr = table.getElementsByTagName("tr");
for (i = 0; i < tr.length; i++) {
td = tr[i].getElementsByTagName("td")[43];
if (td) {
  txtValue = td.textContent || td.innerText;
  if (txtValue.toUpperCase().indexOf(filter) > -1) {
    tr[i].style.display = "";
  } else {
    tr[i].style.display = "none";
  }
}       
}
}
function searchCert() {
var input, filter, table, tr, td, i, txtValue;
input = document.getElementById("certificateSearch");
filter = input.value.toUpperCase();
table = document.getElementById("table-container-table");
tr = table.getElementsByTagName("tr");
for (i = 0; i < tr.length; i++) {
td = tr[i].getElementsByTagName("td")[5];
if (td) {
  txtValue = td.textContent || td.innerText;
  if (txtValue.toUpperCase().indexOf(filter) > -1) {
    tr[i].style.display = "";
  } else {
    tr[i].style.display = "none";
  }
}       
}
}

function searchMinSize() {
var input, filter, table, tr, td, i, txtValue;
input = document.getElementById("sizeFrom");
filter = input.value.toUpperCase();
table = document.getElementById("table-container-table");
tr = table.getElementsByTagName("tr");
for (i = 0; i < tr.length; i++) {
td = tr[i].getElementsByTagName("td")[40];
if (td) {
  txtValue = td.textContent || td.innerText;
  if (txtValue.toUpperCase().indexOf(filter) > -1) {
    tr[i].style.display = "";
  } else {
    tr[i].style.display = "none";
  }
}       
}
}