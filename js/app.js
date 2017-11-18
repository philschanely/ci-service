// Global variables
// ----------------------------------

var api = "/api/index.php/";
var current_user = 1;
var default_task_type = 1;


// Function definitions
// ----------------------------------




// Responders
// ----------------------------------

$(function(){

    // $.ajax(api + 'category', {
    //   type: "POST",
    //   data: {
    //     name: "Freelance 2",
    //     owner: current_user
    //   },
    //   success: function(data){
    //     var result = $.parseJSON(data);
    //     console.table(result.categories);
    //     $(".well").text(data);
    //   }
    // });

    // $.ajax(api + 'category/6', {
    //   type: "DELETE",
    //   success: function(data){
    //     var result = $.parseJSON(data);
    //     console.table(result.categories);
    //     $(".well").text(data);
    //   }
    // });

    $.ajax(api + 'book', {
      type: "GET",
      data: {},
      success: function(data){
        var result = $.parseJSON(data);
        _.each(result.books, function(obj){
          obj.checkedOut = obj.checkedOut == "1" ? true : false;
        });
        $.get("templates/book-list.tpl.html", function(tpl){
          var booksTpl = Handlebars.compile(tpl);
          var booksHTML = booksTpl(result);
          $("#books").append(booksHTML);
        });
      }
    });

});
