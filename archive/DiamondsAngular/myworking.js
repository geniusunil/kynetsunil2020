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
var filterStock, txtValueStock , filterCert, txtValueCert, filterMinSize, txtValueMinSize;

input = document.getElementById("stockSearch");
filterStock = input.value.toUpperCase();
input = document.getElementById("certificateSearch");
filterCert = input.value.toUpperCase();
input = document.getElementById("sizeFrom");
filterMinSize = input.value.toUpperCase();

table = document.getElementById("table-container-table");
tr = table.getElementsByTagName("tr");
console.log(tr);
for (i = 0; i < tr.length; i++) {
  console.log(tr[i]);
  console.log(tr[i].getElementsByTagName("td"));
tdStock = tr[i].getElementsByTagName("td")[43];
tdCert = tr[i].getElementsByTagName("td")[5];
tdMinSize = parseInt(tr[i].getElementsByTagName("td")[40],10);
console.log("before the if");
if (tdStock && tdCert && tdMinSize) {

  txtValueStock = tdStock.textContent || tdStock.innerText;
  txtValueCert = tdCert.textContent || tdCert.innerText;
  txtValueMinSize = parseInt(tdMinSize.textContent || tdMinSize.innerText,10);
    console.log(txtValueMinSize+" "+filterMinSize);
    console.log(txtValueMinSize > filterMinSize);

  if (txtValueStock.toUpperCase().indexOf(filterStock) > -1 && txtValueCert.toUpperCase().indexOf(filterCert) > -1 && txtValueMinSize > filterMinSize) {
    
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