<?php
/**
 * The score view file of custom module of ZenTaoPMS.
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Memory <lvtao@cnezsoft.com>
 * @package     custom
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include 'header.html.php';?>
<div id='mainContent' class='main-content'>
  <form class="load-indicator main-form form-ajax" method='post'>
    <div class='main-header'>
      <div class='heading'>
        <strong><?php echo $lang->custom->scoreTitle?></strong>
      </div>
    </div>
    <table class='table table-form'>
      <tr>
        <th class='w-300px'><?php echo $lang->custom->scoreTitle;?></th>
        <td class='w-120px text-center'>
          <?php $checkedKey = isset($config->global->scoreStatus) ? $config->global->scoreStatus : 0;?>
          <?php foreach($lang->custom->scoreStatus as $key => $value):?>
          <label class="radio-inline"><input type="radio" name="score" value="<?php echo $key?>"<?php echo $key == $checkedKey ? " checked='checked'" : ''?> id="score<?php echo $key;?>"><?php echo $value;?></label>
          <?php endforeach;?>
        </td>
        <td class='form-actions'>
          <?php echo html::submitButton();?>
          <?php common::printLink('score', 'reset', '', "<i class='icon-refresh'></i> " . $lang->custom->scoreReset, '', ' id="scoreRefresh" class="btn btn-wide iframe" data-width="480"', true, true);?>
        </td>
      </tr>
    </table>
  </form>
</div>
<script>
$(function()
{
    $('#mainMenu #scoreTab').addClass('btn-active-text');
})
</script>
<?php include '../../common/view/footer.html.php';?>
