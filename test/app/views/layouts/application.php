<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Main App</title>
  </head>
  <body>
    <?php var_dump(get_defined_vars()) ?>
    <header>
      <h2>This is the header</h2>
      <hr>
      <a href="#">Home</a>
      <a href="#">About</a>
      <a href="#">Contact Us</a>
    </header>

    <hr>
    <?php $this->render_template(); ?>
    <hr>
    <footer>
      THIS IS THE FOOTER
    </footer>
  </body>
</html>
