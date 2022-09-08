document.getElementById("defaultOpen").click();

const responseHTML = document.querySelector('#response');
const requestJob = document.querySelector('#requestJob');
const apiurl = document.querySelector('#apiurl');
const apiprotocol = document.querySelector('#apiprotocol');
const toast = document.querySelector('#toast');

apiurl.value = window.location.hostname;
console.log(requestJob.value);

// ToDo: Format Request JSON 
// requestJob.onkeyup = function( event ) {
//   if ( event.keyCode == 13 ){
//      requestJob.value = JSON.stringify( requestJob.value, undefined, 4 );
//   }
// }

function request(){

    var resp = axios.post( apiprotocol.value + apiurl.value + '/api.php',
            validateJSON( requestJob.value )
        )
        .then(
            (response, d) => {
            responseHTML.innerHTML = JSON.stringify( response.data, undefined, 4 );
            console.log( response );
            showToast( 0,  "FIN" );
        })
        .catch(function (error) {
            console.log(error);
            showToast( 1, error  + "( maybe https:// or http::// selection ) " );
        });
}

function validateJSON( json ){
  try {
    json = JSON.parse( json )
    return json;
  } catch (error) {
   showToast( 1, error );
  }
}


function openCity(evt, cityName) {
  // Declare all variables
  var i, tabcontent, tablinks;

  // Get all elements with class="tabcontent" and hide them
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  // Get all elements with class="tablinks" and remove the class "active"
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  // Show the current tab, and add an "active" class to the button that opened the tab
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

function showToast( type, content){
  // 0 = success
  // 1 = error
  toast.classList.add("show"); 

  toast.innerHTML = content;
  if ( type == 0 ) { 
    toast.classList.add("success"); 
    toast.classList.remove("error"); 
  }
  if ( type == 1 ) { 
    toast.classList.remove("success"); 
    toast.classList.add("error"); 
  }
}