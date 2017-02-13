<!DOCTYPE html>
<html>
  <body>
      <h1>Users</h1>
      <ul>
        <?php foreach($users as $user): ?>
          <li><?php echo $user; ?></li>
        <?php endforeach; ?>
      </ul>
  </body>
</html>
