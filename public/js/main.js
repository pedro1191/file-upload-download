$('.custom-file-input').on('change', function() {
    let fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-control').addClass("selected").html(fileName);
});

$('#file').on('change', function() {
    var maxSize = $('#maxfilesize').val();
    if(this.files[0].size > maxSize) {
       $('#invalid-feedback').html('Maximum file size must be ' + maxSize/1000000 + 'MB');
       $(this).addClass('is-invalid');
       $(this).val('');
    } else {
      $('#invalid-feedback').html('');
      $(this).removeClass('is-invalid');
    }
  });