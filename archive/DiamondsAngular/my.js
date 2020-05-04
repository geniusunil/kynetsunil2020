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

var input, table, tr, td, i;
var filterStock, txtValueStock , filterCert, txtValueCert, filterMinSize, txtValueMinSize, filterMaxSize, filterMinPrice, txtValuePrice, filterMaxPrice, txtValueColor, filterColor, txtValueShape, filterShape, txtValueCut, filterCut, txtValuePolish, filterPolish, txtValueSymmetry, filterSymmetry, txtValueClarity, filterClarity, txtValueLab, filterLab, txtValueIntensity, filterIntensity;

input = document.getElementById("stockSearch");
filterStock = input.value.toUpperCase();
input = document.getElementById("certificateSearch");
filterCert = input.value.toUpperCase();
input = document.getElementById("sizeFrom");
filterMinSize = parseFloat(input.value.toUpperCase());
// check for NaN
if(isNaN(filterMinSize)){
  filterMinSize=0;
}
input = document.getElementById("sizeTo");
filterMaxSize = parseFloat(input.value.toUpperCase());
if(isNaN(filterMaxSize)){
  filterMaxSize=1000000;
}
input = document.getElementById("priceFrom");
filterMinPrice = parseFloat(input.value.toUpperCase());
if(isNaN(filterMinPrice)){
  filterMinPrice=0;
}
input = document.getElementById("priceTo");
filterMaxPrice = parseFloat(input.value.toUpperCase());
if(isNaN(filterMaxPrice)){
  filterMaxPrice=1000000;
}
input = document.getElementById("selectColor");
filterColor =  input.options[input.selectedIndex].value.toUpperCase();
input = document.getElementById("selectClarity");
filterClarity =  input.options[input.selectedIndex].value.toUpperCase();
input = document.getElementById("selectShape");
filterShape =  input.options[input.selectedIndex].value.toUpperCase();
input = document.getElementById("selectLab");
filterLab =  input.options[input.selectedIndex].value.toUpperCase();

input = document.getElementById("selectFluorescence");
filterIntensity =  input.options[input.selectedIndex].value.toUpperCase();

table = document.getElementById("table-container-table");
tr = table.getElementsByTagName("tr");

for (i = 0; i < tr.length; i++) {

tdStock = tr[i].getElementsByTagName("td")[0];
tdCert = tr[i].getElementsByTagName("td")[10];
tdMinSize = tr[i].getElementsByTagName("td")[2];
tdPrice = tr[i].getElementsByTagName("td")[8];
tdColor = tr[i].getElementsByTagName("td")[3];
tdShape = tr[i].getElementsByTagName("td")[1];
tdClarity= tr[i].getElementsByTagName("td")[4];
tdLab= tr[i].getElementsByTagName("td")[32];
tdCut= tr[i].getElementsByTagName("td")[5];
tdPolish= tr[i].getElementsByTagName("td")[6];
tdSymmetry= tr[i].getElementsByTagName("td")[7];
tdIntensity= tr[i].getElementsByTagName("td")[27];
// console.log("before the if");
if (tdStock && tdCert && tdMinSize && tdPrice && tdColor && tdShape && tdClarity && tdLab && tdCut && tdPolish && tdSymmetry && tdIntensity) {

  txtValueStock = tdStock.textContent || tdStock.innerText;
  txtValueCert = tdCert.textContent || tdCert.innerText;
  txtValueMinSize = parseFloat(tdMinSize.textContent || tdMinSize.innerText);
  if(isNaN(txtValueMinSize)){
    txtValueMinSize=0;
  }
  txtValuePrice = parseFloat(tdPrice.textContent || tdPrice.innerText);
  if(isNaN(txtValuePrice)){
    txtValuePrice=0;
  }
  txtValueColor = "NONEX"
  txtValueShape = "NONEX";
  txtValueClarity = "NONEX";
  
  txtValueLab = "NONEX";
  txtValueIntensity = "NONEX";


if(filterColor!="NONEX")
  txtValueColor = tdColor.textContent || tdColor.innerText;
if(filterShape!="NONEX")
  txtValueShape = tdShape.textContent || tdShape.innerText;
if(filterClarity!="NONEX")
  txtValueClarity = tdClarity.textContent || tdClarity.innerText;
// if(filterCut!="NONEX")
//  txtValueCut = tdCut.textContent || tdCut.innerText;
if(filterLab!="NONEX")
  txtValueLab = tdLab.textContent || tdLab.innerText;
if(filterIntensity!="NONEX")
  txtValueIntensity = tdIntensity.textContent || tdIntensity.innerText;
// if(filterSymmetry!="NONEX")
//   txtValueSymmetry = tdSymmetry.textContent || tdSymmetry.innerText;
// if(filterPolish!="NONEX")
//   txtValuePolish = tdPolish.textContent || tdPolish.innerText;

//  console.log(txtValuePolish);
    if (txtValueStock.toUpperCase().indexOf(filterStock) > -1 && txtValueCert.toUpperCase().indexOf(filterCert) > -1 && txtValueMinSize >= filterMinSize && txtValueMinSize <= filterMaxSize && txtValuePrice >= filterMinPrice && txtValuePrice <= filterMaxPrice && txtValueColor == filterColor && txtValueShape == filterShape && txtValueClarity == filterClarity && txtValueLab == filterLab && txtValueIntensity == filterIntensity) {
    
    tr[i].style.display = "";
  } else {
    tr[i].style.display = "none";
  }
}       
}

}



function clearxyz(){
  input = document.getElementById("sizeTo");
  // console.log(input);
  input.value = "";
  document.getElementById("stockSearch").value = "";
  document.getElementById("certificateSearch").value = "";
  document.getElementById("sizeFrom").value = "";
  document.getElementById("priceFrom").value="";
  document.getElementById("priceTo").value="";

 input = $('.filterSelect option');
 console.log(input);
    $('.filterselect option').prop('selected', function() {
        return this.defaultSelected;
    });
applyAllFilters();
}
function toggleAll(){
  console.log("toggle all working");
  if (document.getElementById('checkAll').checked) 
  {
    console.log('checkAll');
    $.each($("tr:not(:hidden) input[name='sport']"), function(){    
      console.log($(this));
              $(this).prop("checked", true);
          });
  } else {
    console.log('checkNone');
    $.each($("input[name='sport']"), function(){    
      console.log($(this));
              $(this).attr("checked", false);
          });
  }
}

function sendPopup(){
  var favorite = [];
      $.each($("input[name='sport']:checked"), function(){    
  
          favorite.push($(this).val());
      });
      
  popupWin = window.open('https://www.ajami-diamonds.com/contact-us-enquiry/','popup','width=600,height=600'); 
  // popupWin.document.writeln('<html><head><title>test</title></head><body><form><input type="text" id="popupTextBox"/></form></body></html>');
  // popupWin.document.close();
/*   popupText = popupWin.document.getElementById("wpcf7-f487-p80-o1");
  popupText.html("<div id='manual'></div>"); */
  parentText = "successssss";
  transferText();

  jQuery(popupWin).load(function () {

    // alert('page is loaded');
    popupText = popupWin.document.getElementById("fav").value = favorite.join(", ");
    console.log(popupText);
    myDiv = '<div id="manual"></div>';
    console.log(favorite.join(", "));
    var node = document.createElement("span");                 // Create a <li> node
var textnode = document.createTextNode(favorite.join(", "));         // Create a text node
node.appendChild(textnode); 
    // popupText.value = "hello";//favorite.join(", ");
    /* setTimeout(function () {
        alert('page is loaded and 1 minute has passed');   
    }, 60000); */

});
}
function transferText()
{
  // popupText.value = parentText.value;
}