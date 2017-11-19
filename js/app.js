(function() {

// ----------------------------------
// Global variables
// ----------------------------------

var api = "/api/index.php/";
var defaultBook = {
  id: 0,
  title: '',
  author: '',
  checkedOut: false
};
var modalEditBookTpl, modalDeleteBookTpl, bookRowTpl, bookListTpl;


// ----------------------------------
// Function definitions
// ----------------------------------

function preloadTemplates() {
  $.get('templates/book-list.tpl.html', function(tpl){
    bookListTpl = Handlebars.compile(tpl);
  });

  $.get('templates/book-row.tpl.html', function(tpl){
    bookRowTpl = Handlebars.compile(tpl);
    Handlebars.registerPartial('bookRow', bookRowTpl);
  });

  $.get('templates/modal-edit-book.tpl.html', function(tpl){
    modalEditBookTpl = Handlebars.compile(tpl);
  });

  $.get('templates/modal-delete-book.tpl.html', function(tpl){
    modalDeleteBookTpl = Handlebars.compile(tpl);
  });
}

function serializeData($form) {
  var formData = $form.serializeArray();
  var data = {};
  for (var i = 0; i < formData.length; i++) {
    var obj = formData[i];
    data[obj.name] = obj.value;
  }
  return data;
}

function getBookIdFromRow($start) {
  return $start.closest('.book').attr('data-bookId');
}

function addBook(data) {
  $.ajax(api + 'book/', {
    type: "POST",
    data: data,
    success: function(data) {
      getAllBooks();
    }
  });
}

function getAllBooks() {
  $.ajax(api + 'book/', {
    type: "GET",
    success: function(data){
      var result = $.parseJSON(data);
      _.each(result.books, function(obj){
        obj.checkedOut = obj.checkedOut == "1" ? true : false;
      });
      var booksHTML = bookListTpl(result);
      $("#books").html(booksHTML);
    }
  });
}

function getBook(bookId) {
  console.log(api + 'book/' + bookId);
  $.get(api + 'book/' + bookId, function(data){
    var result = $.parseJSON(data);
    result.checkedOut = result.checkedOut == "1" ? true : false;
    var bookEditorHTML = modalEditBookTpl(result);
    $("body").append(bookEditorHTML);
    $('#modal-edit-book').modal('show');
  });
}

function deleteBook(bookId) {
  $.ajax(api + 'book/' + bookId, {
    type: "DELETE",
    success: function(data) {
      var bookIdStr = '#book-' + bookId;
      $(bookIdStr).remove();
      $('#modal-delete-book').modal('hide');
    }
  });
}

function updateBook(bookId, data) {
  $.ajax(api + 'book/' + bookId, {
    type: "PUT",
    data: data,
    success: function(data) {
      console.log(data);
      var result = $.parseJSON(data);
      result.checkedOut = result.checkedOut == "1" ? true : false;
      var bookIdStr = '#book-' + bookId;
      var bookHTML = bookRowTpl(result);
      $(bookIdStr).replaceWith(bookHTML);
    }
  });
}

function handleAddBookClick(e){
  e.preventDefault();

  $("#modal-edit-book").remove();

  var bookDetails = defaultBook;
  var bookModalHTML = modalEditBookTpl(bookDetails);
  $('body').append(bookModalHTML);

  $('#modal-edit-book').modal('show');
}

function handleEditBookClick(e){
  e.preventDefault();

  $("#modal-edit-book").remove();

  var bookId = getBookIdFromRow($(e.target));
  getBook(bookId);
}

function handleSaveBookClick(e){
  e.preventDefault();

  var $form = $(e.target).closest('form');
  var formData = serializeData($form);

  $('#modal-edit-book').modal('hide');

  if (formData.id > 0) {
    updateBook(formData.id, formData);
  } else {
    addBook(formData);
  }
}

function handleCheckinBookClick(e){
  e.preventDefault();
  var bookId = getBookIdFromRow($(e.target));
  updateBook(bookId, { checkedOut: 0 });
}

function handleCheckoutBookClick(e){
  e.preventDefault();
  var bookId = getBookIdFromRow($(e.target));
  updateBook(bookId, { checkedOut: 1 });
}

function handleDeleteBookClick(e){
  e.preventDefault();

  $('#modal-delete-book').remove();

  var book = {
    id: getBookIdFromRow($(e.target))
  };
  var deleteBookModalHTML = modalDeleteBookTpl(book);
  $('body').append(deleteBookModalHTML);
  $('#modal-delete-book').modal('show');
}

function handleConfirmDeleteBookClick(e){
  e.preventDefault();

  var $form = $(e.target).closest('form');
  var formData = serializeData($form);
  deleteBook(formData.id);
}


// ----------------------------------
// DRE and Responders
// ----------------------------------

$(function(){

    preloadTemplates();
    getAllBooks();

    $(document).on("click", ".btn-add-book", handleAddBookClick);
    $(document).on("click", ".link-edit-book", handleEditBookClick);
    $(document).on("click", ".btn-save-book", handleSaveBookClick);
    $(document).on("click", ".btn-check-in", handleCheckinBookClick);
    $(document).on("click", ".btn-check-out", handleCheckoutBookClick);
    $(document).on("click", ".btn-delete-book", handleDeleteBookClick);
    $(document).on("click", ".btn-confirm-delete-book", handleConfirmDeleteBookClick);

});

})();
