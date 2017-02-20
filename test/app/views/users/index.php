<!DOCTYPE html>
<html>
  <body>
      <h1>Users</h1>

      <?php if (\Application::flash()->any()): ?>
        <ul>
          <?php foreach (\Application::flash() as $key=>$msg): ?>
            <li class="flash flash-<?php echo $key ?>"><?php echo $msg ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <ul>
        <?php foreach($users as $user): ?>
          <?php echo $user->validate(); ?>
          <li><?php var_dump($user->errors()->full_messages()); ?></li>
        <?php endforeach; ?>
      </ul>
  </body>
</html>
