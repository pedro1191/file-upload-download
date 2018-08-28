<?php require APPROOT . '/views/inc/header.php'; ?>
  <?php flash('post_message'); ?>
  <a href="<?php echo URLROOT; ?>/posts" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
  <div class="card card-body bg-light mt-5">
  <?php if ($data['post'] != null) : ?>
    <h2 class="mb-3">File Deletion</h2>
    <div class="card card-body mb-3">
	     <div class="media-body">
              <h4 class="mt-0 mb-1"><?php echo $data['post']->getFilename(); ?></h4>
               Modified for the last time on <?php echo date('m/d/Y H:i:s', $data['post']->getMTime()); ?>
              </div>
    </div>
    <p>Are you sure you want to delete this file?</p>
    <form class="pull-right" action="<?php echo URLROOT; ?>/posts/delete" method="post">
      <button type="submit" name="id" value="<?php echo $data['post']->getFilename(); ?>" class="btn btn-danger">Delete</button>
    </form>
  <?php else : ?>
    <h2>File not found :(</h2>
  <?php endif; ?>
  </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>