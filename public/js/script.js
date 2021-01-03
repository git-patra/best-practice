// Add Table
$.ajaxSetup({
    headers: {
        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on('submit', '#login', function (e) {
  
  e.preventDefault();
  
  $.ajax({
    url: "api/login",
    type: "POST",
    data: $('#login').serialize(),
    success: function (response) {
        window.location.replace("/book");
        console.log(response);
    },
    error: function (error) {
      console.log(error);
    }
  })
});

$(document).on('submit', '#logout', function (e) {
  
  e.preventDefault();
  console.log('ok');
  $.ajax({
    url: "api/logout",
    type: "POST",
    data: $('#logout').serialize(),
    success: function (response) {
        window.location.replace("/login");
        console.log(response);
    },
    error: function (error) {
      console.log(error);
    }
  })
});


const bearer_token = Cookies.get('token');
console.log(bearer_token);
const bearer = `Bearer ${bearer_token}`;

fetch("http://localhost:8000/api/user", {
  method: "GET",
  headers: {
    'Authorization': bearer,
  }
})
 .then(response => {
   return response.json();
 })
 .then(responseJson => {
     return console.log(responseJson);
 })
 .catch(error => {
   console.log(error);
 })
