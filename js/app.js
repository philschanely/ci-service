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

    $.ajax(api + 'task', {
      type: "GET",
      data: {
        filter: "owner=" + current_user
      },
      success: function(data){
        var result = $.parseJSON(data);
        console.log(result);
        $.get("templates/task.tpl.html", function(tpl){
          var taskTpl = Handlebars.compile(tpl);
          $(result.tasks).each(function(i,o){
            var taskHTML = taskTpl(o);
            $("#tasks").append(taskHTML);
          });

        });

      }
    });

});
