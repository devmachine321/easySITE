<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (isset($_POST['ok'])) {
    $password = md5($_POST['password']);
    $key = $_POST['key'];
    file_put_contents('data/admin_url', $key);
    file_put_contents('data/name', $_POST['name']);
    file_put_contents('data/language', $_POST['language']);
    file_put_contents('data/password_md5', $password);
    if (isset($_POST['mmode'])) {
        //Yes
        file_put_contents('data/mmode', 'true');
    } else {
        file_put_contents('data/mmode', 'false');
    }
    echo '<div class="alert alert-success" role="alert">Changes Have been Saved</div>';
}
?>

<h1><?php echo $this->lang->line('settingstag'); ?></h1>
<style>
    #password {
    padding: 10px;
    border: 1px solid #000;
    margin: 0 0 10px;
}

div.pass-container {
    height: 30px;
}

div.pass-bar {
    height: 11px;
    margin-top: 2px;
}
div.pass-hint {
    font-family: arial;
    font-size: 11px;
}

</style>
<form method="post" action="#">
<?php echo $this->lang->line('password'); ?>:<input id="password" class="form-control" name="password" type="password" />
Name:<input id="password" class="form-control" name="name" value="<?php echo file_get_contents('data/name'); ?>" type="text" />
<?php echo $this->lang->line('key'); ?>: <input id="password" class="form-control" name="key" type="text" value="<?php echo file_get_contents('data/admin_url'); ?>" />
   <?php echo $this->lang->line('language'); ?>: <select style="color:black;" class="selectpicker" name="language" value="<?php echo file_get_contents('data/language'); ?>">
  <option>english</option>
  <option>chinese</option>
  <option>spanish</option>
</select>

    <div class="checkbox">
      <label><input name="mmode" type="checkbox" <?php if (file_get_contents('data/mmode')) {
    echo 'checked';
} ?>><?php echo $this->lang->line('maintainance'); ?></label>
    </div>
  <button type="submit" name="ok" class="btn btn-primary"><?php echo $this->lang->line('submit'); ?></button>
</form>