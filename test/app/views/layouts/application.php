<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Main App</title>
    <?php $this->include_stylesheet('application'); ?>
    <?php $this->include_javascript('application'); ?>
  </head>
  <body>
    <header>
      <h2>This is the header</h2>
      <hr>
      <a href="#">Home</a>
      <a href="#">About</a>
      <a href="#">Contact Us</a>
      <?php $this->link_to('Contact Us', '#'); ?>
    </header>
    <?php $this->render('header', ['title'=>123]); ?>
    <?php $this->render('partials/test'); ?>

    <hr>
    <?php $this->yield(); ?>
    <hr>
    <footer>
      THIS IS THE FOOTER
    </footer>
  </body>
</html>
