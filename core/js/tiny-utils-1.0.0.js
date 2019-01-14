// requires drive-picker-1.0.0.php (js) to be called first

function filePickerCallback(callback, value, meta) {
  var input = document.createElement('input');
  input.setAttribute("type", "file");
  input.setAttribute("accept", "image/*");

  input.onchange = function () {
    var formData = new FormData();
    formData.append('image', input.files[0]);

    $.ajax({
      data: formData,
      type: 'POST',
      processData: false, // important
      contentType: false, // important
      url: '/core/abre_tiny_upload.php'
    }).done(function (response) {
      callback(response.location, { alt: response.name });
    });
  }
  input.click();
}

function onClick(editor) {
  showPickerPromise({ multiselect: true }).then(docs => {
    for (var i = 0; i < docs.length; i++) {
      var doc = docs[i];
      var url = doc[google.picker.Document.URL];
      var title = doc[google.picker.Document.NAME];

      if (i != 0) {
        editor.insertContent(' '); // insert a space between elements if more than one
      }
      editor.insertContent(`<a href="${url}">${title}</a>`);
    }
  });
}

function loadGoogleDrivePickerForTiny(editor) {
  editor.addButton('drive', {
    onclick: () => onClick(editor),
    image: './core/images/abre/google-drive-dark.png',
    tooltip: 'Insert a Google Drive file'
  });
};