<?php

$catobj = get_terms( 'list_categories' );
// print_r($catobj);
?>



<div class="container">
    <div id="topsfbadges">
        <div class="design">
            <div class="title">
                <span>Top Software Badges</span>
            </div>
            <div>
                <div class="col-md-4">
                    <div class="dropdown">
                    <button onclick="myFunction()" class="dropbtn">Select Software Category</button>
                    <div id="myDropdown" class="dropdown-content">
                        <input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">
                        <?php foreach($catobj as $co){ 
                       echo  "<span style='display: block;' term-id=".$co->term_id." >".$co->name."</span>";
                         }?>
                    </div>
                    </div>
                </div>
                <div id="softwareName" class="col-md-4">
                    <div class="dropdown">
                    <button onclick="myFunction()" class="dropbtn">Select Software</button>
                    <div id="myDropdown2" class="dropdown-content">
                        <input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">
                      
                    </div>
                    </div>
                </div>
                <div class="col-md-4">
                </div>
            </div>
        </div>
        <div class="preview">
        </div>
    </div>
</div>

<style>
/* Dropdown Button */
.dropbtn {
  background-color: #4CAF50;
  color: white;
  padding: 16px;
  font-size: 16px;
  border: none;
  cursor: pointer;
}

/* Dropdown button on hover & focus */
.dropbtn:hover, .dropbtn:focus {
  background-color: #3e8e41;
}

/* The search field */
#myInput {
  box-sizing: border-box;
  background-image: url('searchicon.png');
  background-position: 14px 12px;
  background-repeat: no-repeat;
  font-size: 16px;
  padding: 14px 20px 12px 45px;
  border: none;
  border-bottom: 1px solid #ddd;
}

/* The search field when it gets focus/clicked on */
#myInput:focus {outline: 3px solid #ddd;}

/* The container <div> - needed to position the dropdown content */
.dropdown {
  position: relative;
  display: inline-block;
}

/* Dropdown Content (Hidden by Default) */
.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f6f6f6;
  min-width: 230px;
  border: 1px solid #ddd;
  z-index: 1;
}

/* Links inside the dropdown */
.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

/* Change color of dropdown links on hover */
.dropdown-content a:hover {background-color: #f1f1f1}

/* Show the dropdown menu (use JS to add this class to the .dropdown-content container when the user clicks on the dropdown button) */
.show {display:block;}
</style>

<script>
/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
function myFunction() {
    // console.log(event.target.nextElementSibling);
    event.target.nextElementSibling.classList.toggle("show");
//   document.getElementById("myDropdown").classList.toggle("show");
}
/* function myFunction2() {
  document.getElementById("myDropdown2").classList.toggle("show");
} */
/* function send_query(){
    alert("hi");
} */
const buttons = document.querySelectorAll("#myDropdown span")
for (const button of buttons) {
  button.addEventListener('click', function(event) {
    // event.preventDefault()
//   alert("hi");
    console.log(event.target.getAttribute("term-id"));
  document.getElementById("myDropdown").classList.toggle("show");

})
}
function filterFunction() {
  var input, filter, ul, li, a, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  div = event.target.parentNode;
  a = div.getElementsByTagName("span");
  for (i = 0; i < a.length; i++) {
    txtValue = a[i].textContent || a[i].innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      a[i].style.display = "block";
    } else {
      a[i].style.display = "none";
    }
  }
}
</script>