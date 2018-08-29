<?php require APPROOT . '/views/inc/header.php'; ?>
  <?php flash('post_message'); ?>
  <div class="row mb-3">
    <div class="col-md-10">
      <h2>My Files</h2>
    </div>
    <div class="col-md-2">
      <a href="<?php echo URLROOT; ?>/posts/add" class="btn btn-primary pull-right">
        <i class="fa fa-upload"></i> Upload
      </a>
    </div>
  </div>
  <div class="row my-3">
    <div class="col">
      <form class="" action="<?php echo URLROOT; ?>/posts" method="post">
        <div class="form-group">
          <input class="form-control mr-sm-2" type="search" name="q" placeholder="Search for..." aria-label="Search" value="<?php echo empty($data['query']) ? '' : $data['query']; ?>">
          <button class="btn btn-success mt-2" type="submit">Go</button>
        </div>
      <?php if(!empty($data['posts']) && !empty($data['total']) && $data['total'] > RESULTSPERPAGE) :?>
        <div class="form-group">
          <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo ($data['prev_page'] !== null) ? '' : 'disabled'; ?>">
                  <button type="submit" class="page-link" name="offset" aria-label="Previous" value="<?php echo $data['prev_page']; ?>">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Prev</span>
                  </button>
                </li>
              <?php for ($i=$data['start']; $i < $data['end']; $i++) : ?>
              <li class="page-item <?php echo ($data['current_page'] == $i) ? 'active' : ''; ?>"><button type="submit" class="page-link" name="offset" value="<?php echo $i; ?>"><?php echo $i + 1; ?></button></li>
              <?php endfor; ?>
                <li class="page-item  <?php echo ($data['next_page'] !== null) ? '' : 'disabled'; ?>">
                  <button type="submit" class="page-link" name="offset" aria-label="Next" value="<?php echo $data['next_page']; ?>">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                  </button>
                </li>
            </ul>
          </nav>
        </div>
      <?php endif; ?>
      </form>
    </div>
  </div>
  <hr>
  <?php if(!empty($data['posts'])) :
    foreach($data['posts'] as $post) : ?>
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
  <?php endforeach;
  else : ?>
  <div class="row">
    <div class="col">
      <div class="text-center">
        <h2>No result found :(</h2>
      </div>
    </div>
  </div>
  <?php endif; ?>
  <?php if(!empty($data['posts']) && !empty($data['total']) && $data['total'] > RESULTSPERPAGE) :?>
  <form class="" action="<?php echo URLROOT; ?>/posts" method="post">
    <div class="form-group">
      <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php echo ($data['prev_page'] !== null) ? '' : 'disabled'; ?>">
              <button type="submit" class="page-link" name="offset" aria-label="Previous" value="<?php echo $data['prev_page']; ?>">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Prev</span>
              </button>
            </li>
          <?php for ($i=$data['start']; $i < $data['end']; $i++) : ?>
          <li class="page-item <?php echo ($data['current_page'] == $i) ? 'active' : ''; ?>"><button type="submit" class="page-link" name="offset" value="<?php echo $i; ?>"><?php echo $i + 1; ?></button></li>
          <?php endfor; ?>
            <li class="page-item  <?php echo ($data['next_page'] !== null) ? '' : 'disabled'; ?>">
              <button type="submit" class="page-link" name="offset" aria-label="Next" value="<?php echo $data['next_page']; ?>">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Next</span>
              </button>
            </li>
        </ul>
      </nav>
    </div>
    <input type="hidden" value="<?php echo empty($data['query']) ? '' : $data['query']; ?>" name="q">
  </form>
  <?php endif; ?>
<?php require APPROOT . '/views/inc/footer.php'; ?>