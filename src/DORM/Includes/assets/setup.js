console.log("blubb");
document.getElementById("defaultOpen").click();

const responseHTML = document.querySelector('#response');
const requestJob = document.querySelector('#requestJob');
const apiurl = document.querySelector('#apiurl');

apiurl.value = window.location.hostname;
console.log(apiurl);


console.log(requestJob.value);

function request(){
    var resp = axios.post( "https://" + apiurl.value + '/api.php',
            JSON.parse( requestJob.value )
        )
        .then(
            (response, d) => {
            responseHTML.innerHTML = JSON.stringify( response.data, undefined, 4 );
            console.log( response );

        })
        .catch(function (error) {
            console.log(error);
        });
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