<?php require APPROOT . '/views/inc/header.php'; ?>
  <?php flash('post_message'); ?>
  <div class="row mb-3">
    <div class="col-md-6">
      <h1>My Files</h1>
    </div>
    <div class="col-md-6">
      <a href="<?php echo URLROOT; ?>/posts/add" class="btn btn-success pull-right">
        <i class="fa fa-upload"></i> Upload
      </a>
    </div>
  </div>
  <?php foreach($data['posts'] as $post) : ?>
    <div class="card card-body mb-3">
      <div class="row">
	      <div class="col-md">
          <h4 class="mt-0 mb-1"><?php echo $post->getFilename(); ?></h4>
          <p class="mb-2">Modified for the last time on <?php echo date('m/d/Y H:i:s', $post->getMTime()); ?></p>
        </div>
	      <div class="col-md">
          <form action="<?php echo URLROOT; ?>/posts/download" method="post">
            <button type="submit" value="<?php echo $post->getFilename(); ?>" name="id" class="btn btn-light pull-right"><i class="fa fa-download"></i> Download</button>
          </form>
	        <form action="<?php echo URLROOT; ?>/posts/show" method="post">
            <button type="submit" value="<?php echo $post->getFilename(); ?>" name="id" class="btn btn-danger pull-right mr-2">Delete</button>
          </form>
	      </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php require APPROOT . '/views/inc/footer.php'; ?>