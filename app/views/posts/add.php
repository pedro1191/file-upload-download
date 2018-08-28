<?php require APPROOT . '/views/inc/header.php'; ?>
  <?php flash('post_message'); ?>
  <a href="<?php echo URLROOT; ?>/posts" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
  <div class="card card-body bg-light mt-5">
    <h2 class="mb-4">File Upload</h2>
    <form action="<?php echo URLROOT; ?>/posts/add" method="post" enctype="multipart/form-data">
      <div class="form-group">
	       <div class="custom-file mb-3" lang="en">
            <input id="file" type="file" name="file" class="form-control form-control-lg custom-file-input <?php echo (!empty($data['file_err'])) ? 'is-invalid' : ''; ?>" required>
	          <span class="custom-file-control upload-button"></span>
            <span id="invalid-feedback" class="invalid-feedback"><?php echo empty($data['file_err']) ? '' : $data['file_err']; ?></span>
	       </div>
      </div>
      <input type="submit" class="btn btn-success" value="Send">
    </form>
  </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>