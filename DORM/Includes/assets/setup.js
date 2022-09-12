document.getElementById("defaultOpen").click();

const responseHTML = document.querySelector('#response');
const requestJob = document.querySelector('#requestJob');
const apiurl = document.querySelector('#apiurl');
const apiprotocol = document.querySelector('#apiprotocol');
const toast = document.querySelector('#toast');

apiurl.value = window.location.hostname;
console.log(requestJob.value);

function request(){

  var resp = axios.post( apiprotocol.value + apiurl.value,
          validateJSON( requestJob.value )
      )
      .then(
          (response) => {
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


function openTab(evt, tabName) {
  var i, tabcontent, tablinks;

  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  document.getElementById(tabName).style.display = "flex";
  evt.currentTarget.className += " active";
}

function showToast( type, content){
  // 0 = success | 1 = error

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