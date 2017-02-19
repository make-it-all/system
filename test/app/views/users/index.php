<!DOCTYPE html>
<html>
  <body>
      <h1>Users</h1>
      <?php exit(print_r(get_defined_vars(), true)); ?>

      <ul>
        <?php foreach($users as $user): ?>
          <?php echo $user->validate(); ?>
          <li><?php var_dump($user->errors()->full_messages()); ?></li>
        <?php endforeach; ?>
      </ul>
  </body>
</html>
