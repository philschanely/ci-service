// Global variables
// ----------------------------------

var api = "/api/index.php/";

var tplPageLogin,         tplPageSignup,   tplPageMain,
    tplModalEditCategory, tplCategoryList, tplCategory,
    tplModalEditTask,     tplTaskList,     tplTask;

// var userLoggedIn = false;
// var currentUser = null;
var currentUser = {
  id: 1,
  name: "Phil",
  email: 'phil@example.com'
};
var userLoggedIn = true;

var categories = [];
var taskTypes = [];


// Function definitions
// ----------------------------------

function addCategory(e) {
  e.preventDefault();
  $('#edit-category').remove();
  var newCategory = {
    id: 0,
    name: '',
    owner: currentUser.id
  };
  var editCategoryHTML = tplModalEditCategory(newCategory);
  $('body').append(editCategoryHTML);
  $('#edit-category').modal('show');
}

function createCategory(data) {
  $.ajax(api + "category/", {
    type: "POST",
    data: {
      name: data.name,
      owner: data.owner
    },
    success: function(data) {
      data = $.parseJSON(data);
      getCategories();
      $("#edit-category").modal('hide');
    }
  });
}

function deleteCategory(e) {
  e.preventDefault();
  var categoryId = $(e.target).closest('.category').attr('data-id');
  $.ajax(api + "category/" + categoryId, {
    type: "DELETE",
    success: function(data) {
      getCategories();
    }
  });
}

function getTaskTypes() {
  $.get(api + "task_type/", function(data) {
    taskTypes = $.parseJSON(data);
    console.log(taskTypes);
  });
}

function preloadTemplates() {
  var tplCount = 0;
  $.get('templates/page-login.tpl.html', function(tpl){
    tplPageLogin = Handlebars.compile(tpl);
    tplCount++;
    loadInitialPage(tplCount);
  });
  $.get('templates/page-signup.tpl.html', function(tpl){
    tplPageSignup = Handlebars.compile(tpl);
    tplCount++;
    loadInitialPage(tplCount);
  });
  $.get('templates/page-main.tpl.html', function(tpl){
    tplPageMain = Handlebars.compile(tpl);
    tplCount++;
    loadInitialPage(tplCount);
  });
  $.get('templates/modal-edit-category.tpl.html', function(tpl){
    tplModalEditCategory = Handlebars.compile(tpl);
    tplCount++;
    loadInitialPage(tplCount);
  });
  $.get('templates/category-list.tpl.html', function(tpl){
    tplCategoryList = Handlebars.compile(tpl);
    tplCount++;
    loadInitialPage(tplCount);
  });
  $.get('templates/category.tpl.html', function(tpl){
    tplCategory = Handlebars.compile(tpl);
    Handlebars.registerPartial('category', tplCategory);
    tplCount++;
    loadInitialPage(tplCount);
  });
  $.get('templates/modal-edit-task.tpl.html', function(tpl){
    tplModalEditTask = Handlebars.compile(tpl);
    tplCount++;
    loadInitialPage(tplCount);
  });
  $.get('templates/task-list.tpl.html', function(tpl){
    tplTaskList = Handlebars.compile(tpl);
    tplCount++;
    loadInitialPage(tplCount);
  });
  $.get('templates/task.tpl.html', function(tpl){
    tplTask = Handlebars.compile(tpl);
    Handlebars.registerPartial('task', tplTask);
    tplCount++;
    loadInitialPage(tplCount);
  });
}

function filterTasksByCategory(e) {
  e.preventDefault();
  var categoryId = $(e.target).closest('.category').attr('data-id');
  getTasks(categoryId);
}

function getAllTasks(e) {
  e.preventDefault();
  getTasks();
}

function getCategories() {
  $.ajax(api + "category/", {
    type: "GET",
    data: {
      filter: {
        owner: currentUser.id
      }
    },
    success: function(data) {
      data = $.parseJSON(data);
      categories = data;
      var categoryListHTML = tplCategoryList(data);
      $('#categories').html(categoryListHTML);
    }
  });
}

function getTasks(categoryId) {
  var taskFilter = {
    owner: currentUser.id
  };
  if (categoryId) {
    taskFilter.category = categoryId
  }
  $.ajax(api + "task/", {
    type: "GET",
    data: {
      filter: taskFilter
    },
    success: function(data) {
      data = $.parseJSON(data);
      var taskListHTML = tplTaskList(data);
      $('#tasks').html(taskListHTML);
    }
  });
}

function loadInitialPage(tplCount) {
  var tplMasterCount = 9;

  if (tplCount !== tplMasterCount) {
    return false;
  }

  if (userLoggedIn) {
    showMainPage();
  } else {
    showLoginPage();
  }
}

function initializeEventHandlers() {
  $('body')
    .on('click', '.link-signup', showSignupPage)
    .on('click', '.link-login',  showLoginPage)
    .on('click', '.link-logout', processLogout)
    .on('click', '.link-category', filterTasksByCategory)
    .on('click', '.link-all-tasks', getAllTasks)
    .on('click', '.edit-category', editCategory)
    .on('click', '.delete-category', deleteCategory)
    .on('click', '.btn-add-category', addCategory)
    .on('submit', '.form-login', processLogin)
    .on('submit', '.form-signup', processSignup)
    .on('submit', '.form-edit-category', saveCategory);
}

function editCategory(e) {
  e.preventDefault();
  $("#edit-category").remove();
  var categoryId = $(e.target).closest('.category').attr('data-id');
  $.get(api + "category/" + categoryId, function(data) {
    data = $.parseJSON(data);
    var editCategoryHTML = tplModalEditCategory(data);
    $('body').append(editCategoryHTML);
    $("#edit-category").modal('show');
  });
}

function processLogin(e) {
  e.preventDefault();
  var formData = serializeData($(e.target));
  formData.password = md5(formData.password);
  $.ajax(api + "auth_user/", {
    type: 'GET',
    data: {
      filter: formData
    },
    success: function(data) {
      data = $.parseJSON(data);

      if (data.authenticated) {
        userLoggedIn = true;
        currentUser = data.user;
      } else {
        userLoggedIn = false;
        currentUser = null;
      }

      if (userLoggedIn) {
        showMainPage();
      } else {
        $('.login-error').removeClass('d-none');
      }
    },
    error: function() {
      console.log("login error");
    }
  });
}

function processLogout(e) {
  userLoggedIn = false
  currentUser = false;
  showLoginPage();
}

function processSignup(e) {
  e.preventDefault();
  $('.feedback').addClass('d-none');
  var $form = $(e.target);
  var formData = serializeData($form);
  if (formData.name.length < 1) {
    $('.feedback--name').removeClass('d-none');
    return false;
  }
  if (!validateEmail(formData.email)) {
    $('.feedback--email').removeClass('d-none');
    return false;
  }
  if (formData.password !== formData.password2) {
    $('.feedback--password').removeClass('d-none');
    return false;
  }
  $.ajax(api + "user/", {
    type: "POST",
    data: {
      "name": formData.name,
      "email": formData.email,
      "password": md5(formData.password)
    },
    success: function(data) {
      data = $.parseJSON(data);
      if (data && data.id) {
        currentUser = data;
        userLoggedIn = true;
        showMainPage();
      } else {
        $('.feedback--submit-error').removeClass('d-none');
      }
    },
    error: function() {
      console.log("signup error");
    }
  });
}

function saveCategory(e) {
  e.preventDefault();
  var formData = serializeData($(e.target));
  var categoryID = formData.id;
  if (categoryID > 0) {
    updateCategory(formData);
  } else {
    createCategory(formData);
  }
}

function serializeData($form) {
  if ($form) {
    var formData = $form.serializeArray();
    var data = {};
    for (var i = 0; i < formData.length; i++) {
      var item = formData[i];
      data[item.name] = item.value;
    }
    return data;
  } else {
    return false;
  }
}

function showLoginPage() {
  $('.page').remove();
  var loginPageHTML = tplPageLogin();
  $('body').prepend(loginPageHTML);
}

function showMainPage() {
  $('.page').remove();
  var mainPageHTML = tplPageMain();
  $('body').prepend(mainPageHTML);

  getCategories();
  getTasks();
}

function showSignupPage() {
  $('.page').remove();
  var signupPageHTML = tplPageSignup();
  $('body').prepend(signupPageHTML);
}

function updateCategory(data) {
  $.ajax(api + "category/" + data.id, {
    type: "PUT",
    data: data,
    success: function(data) {
      data = $.parseJSON(data);
      getCategories();
      $("#edit-category").modal('hide');
    }
  });
}

function validateEmail(email) {
  var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}


// Responders
// ----------------------------------

$(function(){

  preloadTemplates();
  getTaskTypes();
  initializeEventHandlers();

});
