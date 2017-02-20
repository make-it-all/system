<h1>Create User!</h1>
<?php print_r($this->i('welcome')); ?>

<?php if ($user->errors()->any()): ?>
  <?php var_dump($user->errors()->full_messages()); ?>
<?php endif; ?>

<?php $this->form_for($user, '/users'); ?>

  <?php $this->text_field($user, 'name'); ?>
  <?php $this->email_field($user, 'email'); ?>
  <?php $this->text_field($user, 'admin'); ?>
  <?php $this->checkbox_field($user, 'terms'); ?>
  <?php $this->submit_button('Create User') ?>

</form>
