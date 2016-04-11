<?php
/**
 * The control file of block of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class block extends control
{
    /**
     * construct. 
     * 
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->selfCall = strpos($this->server->http_referer, common::getSysURL()) === 0 || $this->session->blockModule;
        if($this->methodName != 'admin' and !$this->selfCall and !$this->loadModel('sso')->checkKey()) die('');
    }

    /**
     * Block admin. 
     * 
     * @param  int    $index 
     * @param  string $module 
     * @access public
     * @return void
     */
    public function admin($index = 0, $module = 'my')
    {
        $this->session->set('blockModule', $module);

        $title = $index == 0 ? $this->lang->block->createBlock : $this->lang->block->editBlock;

        if(!$index) $index = $this->block->getLastKey($module) + 1;

        if($module == 'my')
        {
            $modules = $this->lang->block->moduleList;
            foreach($modules as $moduleKey => $moduleName)
            {
                if($moduleKey == 'todo') continue;
                if(in_array($moduleKey, $this->app->user->rights['acls'])) unset($modules[$moduleKey]);
                if(!common::hasPriv($moduleKey, 'index')) unset($modules[$moduleKey]);
            }

            $modules['dynamic'] = $this->lang->block->dynamic;
            $modules['html']    = 'HTML';
            $modules = array('' => '') + $modules;

            $hiddenBlocks = $this->block->getHiddenBlocks();
            foreach($hiddenBlocks as $block) $modules['hiddenBlock' . $block->id] = $block->title;
            $this->view->modules    = $modules;
        }
        elseif(isset($this->lang->block->moduleList[$module]))
        {
            $this->get->set('mode', 'getblocklist');
            $this->view->blocks = $this->fetch('block', 'main', "module=$module&index=$index");
        }

        $this->view->block      = $this->block->getBlock($index);
        $this->view->index      = $index;
        $this->view->title      = $title;
        $this->display();
    }

    /**                        
     * Set params when type is rss or html. 
     * 
     * @param  int    $index   
     * @param  string $type    
     * @access public          
     * @return void            
     */
    public function set($index, $type, $source = '')
    {
        if($_POST)             
        {
            $source = isset($this->lang->block->moduleList[$source]) ? $source : '';
            $this->block->save($index, $source, $type, $this->session->blockModule);
            if(dao::isError())  die(js::error(dao::geterror())); 
            die(js::reload('parent'));
        }

        $block = $this->block->getBlock($index);
        if($block) $type = $block->block;

        if(isset($this->lang->block->moduleList[$source]))
        {
            $func   = 'get' . ucfirst($type) . 'Params';
            $params = $this->block->$func($source);
            $this->view->params = json_decode($params, true);
        }

        $this->view->source  = $source;
        $this->view->type    = $type;
        $this->view->index   = $index;
        $this->view->block   = ($block) ? $block : array();
        $this->display();      
    }

    /**
     * Delete block 
     * 
     * @param  int    $index 
     * @param  string $sys 
     * @param  string $type 
     * @access public
     * @return void
     */
    public function delete($index, $module = 'my', $type = 'delete')
    {   
        if($type == 'hidden')
        {   
            $this->dao->update(TABLE_BLOCK)->set('hidden')->eq(1)->where('`order`')->eq($index)->andWhere('account')->eq($this->app->user->account)->andWhere('module')->eq($module)->exec();
        }
        else
        {   
            $this->dao->delete()->from(TABLE_BLOCK)->where('`order`')->eq($index)->andWhere('account')->eq($this->app->user->account)->andWhere('module')->eq($module)->exec();
        }
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success'));
    }

    /**
     * Sort block.
     * 
     * @param  string    $oldOrder 
     * @param  string    $newOrder 
     * @param  string    $module 
     * @access public
     * @return void
     */
    public function sort($oldOrder, $newOrder, $module = 'my')
    {
        $oldOrder  = explode(',', $oldOrder);
        $newOrder  = explode(',', $newOrder);
        $orderList = $this->block->getBlockList($module);

        foreach($oldOrder as $key => $oldIndex)
        {
            if(!isset($orderList[$oldIndex])) continue;
            $order = $orderList[$oldIndex];
            $order->order = $newOrder[$key];
            $this->dao->replace(TABLE_BLOCK)->data($order)->exec();
        }

        if(dao::isError()) $this->send(array('result' => 'fail'));
        $this->send(array('result' => 'success'));
    }

    /**
     * Display dashboard for app.
     * 
     * @param  string    $module 
     * @access public
     * @return void
     */
    public function dashboard($module)
    {
        $blocks = $this->block->getBlockList($module);
        $inited = empty($this->config->$module->common->blockInited) ? '' : $this->config->$module->common->blockInited;

        /* Init block when vist index first. */
        if(empty($blocks) and !($inited and $inited->app == $module and $inited->value))
        {
            if($this->block->initBlock($module)) die(js::reload());
        }

        foreach($blocks as $block)
        {
            $params  = json_decode($block->params);
            $blockID = $block->block;

            $block->blockLink = $this->createLink('block', 'printBlock', "index=$block->order&module=$block->module");
            $block->moreLink  = '';
            if(isset($this->lang->block->modules[$module]->moreLinkList->{$blockID}))
            {
                list($moduleName, $method, $vars) = explode('|', sprintf($this->lang->block->modules[$module]->moreLinkList->{$blockID}, $params->type));
                $block->moreLink = $this->createLink($moduleName, $method, $vars);
            }
        }

        $this->view->blocks = $blocks;
        $this->view->module = $module;
        $this->display();
    }

    /**
     * latest dynamic.
     * 
     * @access public
     * @return void
     */
    public function dynamic()
    {
        $this->view->actions = $this->loadModel('action')->getDynamic('all', 'today');
        $this->view->users   = $this->loadModel('user')->getPairs();
        $this->display();
    }

    /**
     * Print block. 
     * 
     * @param  int    $index 
     * @access public
     * @return void
     */
    public function printBlock($index, $module = 'my')
    {
        $block = $this->block->getBlock($index, $module);

        if(empty($block)) return false;

        $html = '';
        if($block->block == 'html')
        {
            $html = "<div class='article-content'>" . htmlspecialchars_decode($block->params->html) .'</div>';
        }
        elseif($block->source != '')
        {
            $this->get->set('mode', 'getblockdata');
            $this->get->set('module', $block->module);
            $this->get->set('source', $block->source);
            $this->get->set('blockid', $block->block);
            $this->get->set('param',base64_encode(json_encode($block->params)));
            $html = $this->fetch('block', 'main', "module={$block->source}&index=$index");
        }
        elseif($block->block == 'dynamic')
        {
            $html = $this->fetch('block', 'dynamic');
        }
        
        die($html);
    }

    /**
     * Main function.
     * 
     * @access public
     * @return void
     */
    public function main($module = '', $index = 0)
    {
        if(!$this->selfCall)
        {
            $lang = $this->get->lang;
            $this->app->setClientLang($lang);
            $this->app->loadLang('common');
            $this->app->loadLang('block');
        }

        $mode = strtolower($this->get->mode);
        if($mode == 'getblocklist')
        {   
            $blocks = $this->block->getAvailableBlocks($module);
            if($this->selfCall)
            {
                $blocks     = json_decode($blocks, true);
                $blockPairs = array('' => '') + $blocks;

                $block = $this->block->getBlock($index);

                echo "<th>{$this->lang->block->lblBlock}</th>";
                echo '<td>' . html::select('moduleBlock', $blockPairs, ($block and $block->source != '') ? $block->block : '', "class='form-control' onchange='getBlockParams(this.value, \"$module\")'") . '</td>';
                if(isset($block->source)) echo "<script>$(function(){getBlockParams($('#moduleBlock').val(), '{$block->source}')})</script>";
            }
        }   
        elseif($mode == 'getblockform')
        {   
            $code = strtolower($this->get->blockid);
            $func = 'get' . ucfirst($code) . 'Params';
            echo $this->block->$func($module);
        }   
        elseif($mode == 'getblockdata')
        {
            $code = strtolower($this->get->blockid);

            $params = $this->get->param;
            $params = json_decode(base64_decode($params));
            if(!$this->selfCall)
            {
                $this->app->user = $this->dao->select('*')->from(TABLE_USER)->where('ranzhi')->eq($params->account)->fetch();
                if(empty($this->app->user)) 
                {
                    $this->app->user = new stdclass();
                    $this->app->user->account = 'guest';
                }

                $sso = base64_decode($this->get->sso);
                $this->view->sso  = $sso;
                $this->view->sign = strpos($sso, '&') === false ? '?' : '&';
            }

            $this->viewType   = (isset($params->viewType) and $params->viewType == 'json') ? 'json' : 'html';
            $this->params     = $params;
            $this->view->code = $this->get->blockid;

            $func = 'print' . ucfirst($code) . 'Block';
            $this->$func($module);

            $this->view->moreLink = '';
            if(isset($this->lang->block->modules[$module]->moreLinkList->{$code}))
            {
                list($moduleName, $method, $vars) = explode('|', sprintf($this->lang->block->modules[$module]->moreLinkList->{$code}, $params->type));
                $this->view->moreLink = $this->createLink($moduleName, $method, $vars);
            }

            if($this->viewType == 'json')
            {
                unset($this->view->app);
                unset($this->view->config);
                unset($this->view->lang);
                unset($this->view->header);
                unset($this->view->position);
                unset($this->view->moduleTree);

                $output['status'] = is_object($this->view) ? 'success' : 'fail';
                $output['data']   = json_encode($this->view);
                $output['md5']    = md5(json_encode($this->view));
                die(json_encode($output));
            }

            $this->display();
        }
    }

    /**
     * Print List block.
     * 
     * @access public
     * @return void
     */
    public function printListBlock($module = 'product')
    {
        $func = 'print' . ucfirst($module) . 'Block';
        $this->view->module = $module;
        $this->$func();

    }

    /**
     * Print todo block.
     * 
     * @access public
     * @return void
     */
    public function printTodoBlock()
    {
        $this->view->todos    = $this->loadModel('todo')->getList('all', $this->app->user->account, 'wait, doing', $this->viewType == 'json' ? 0 : $this->params->num);
    }

    /**
     * Print task block.
     * 
     * @access public
     * @return void
     */
    public function printTaskBlock()
    {
        $this->view->tasks    = $this->loadModel('task')->getUserTasks($this->app->user->account, $this->params->type, $this->viewType == 'json' ? 0 : $this->params->num, null, $this->params->orderBy);
    }

    /**
     * Print bug block.
     * 
     * @access public
     * @return void
     */
    public function printBugBlock()
    {
        $this->view->bugs     = $this->loadModel('bug')->getUserBugs($this->app->user->account, $this->params->type, $this->params->orderBy, $this->viewType == 'json' ? 0 : $this->params->num);
    }

    /**
     * Print case block.
     * 
     * @access public
     * @return void
     */
    public function printCaseBlock()
    {
        $this->app->loadLang('testcase');
        $this->app->loadLang('testtask');

        $cases = array();
        if($this->params->type == 'assigntome')
        {
            $cases = $this->dao->select('t1.assignedTo AS assignedTo, t2.*')->from(TABLE_TESTRUN)->alias('t1')
                ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
                ->leftJoin(TABLE_TESTTASK)->alias('t3')->on('t1.task = t3.id')
                ->Where('t1.assignedTo')->eq($this->app->user->account)
                ->andWhere('t1.status')->ne('done')
                ->andWhere('t3.status')->ne('done')
                ->andWhere('t3.deleted')->eq(0)
                ->andWhere('t2.deleted')->eq(0)
                ->orderBy($this->params->orderBy)
                ->beginIF($this->viewType != 'json')->limit($this->params->num)->fi()
                ->fetchAll();
        }
        elseif($this->params->type == 'openedbyme')
        {
            $cases = $this->dao->findByOpenedBy($this->app->user->account)->from(TABLE_CASE)
                ->andWhere('deleted')->eq(0)
                ->orderBy($this->params->orderBy)
                ->beginIF($this->viewType != 'json')->limit($this->params->num)->fi()
                ->fetchAll();
        }
        $this->view->cases    = $cases;
    }

    /**
     * Print testtask block.
     * 
     * @access public
     * @return void
     */
    public function printTesttaskBlock()
    {
        $this->app->loadLang('testtask');
        $this->view->testtasks = $this->dao->select('t1.*,t2.name as productName,t3.name as buildName,t4.name as projectName')->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_BUILD)->alias('t3')->on('t1.build=t3.id')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on('t1.build=t4.id')
            ->where('t1.deleted')->eq('0')
            ->beginIF($this->params->type != 'all')->andWhere('t1.status')->eq($this->params->type)->fi()
            ->orderBy('t1.id desc')
            ->beginIF($this->viewType != 'json')->limit($this->params->num)->fi()
            ->fetchAll();
    }

    /**
     * Print story block.
     * 
     * @access public
     * @return void
     */
    public function printStoryBlock()
    {
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init(0, $this->params->num, 1);
        $this->view->stories  = $this->loadModel('story')->getUserStories($this->app->user->account, $this->params->type, $this->params->orderBy, $this->viewType != 'json' ? $pager : '');
    }

    /**
     * Print plan block.
     * 
     * @access public
     * @return void
     */
    public function printPlanBlock()
    {
        $this->app->loadLang('productplan');
        $this->view->plans = $this->dao->select('t1.*,t2.name as productName')->from(TABLE_PRODUCTPLAN)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq('0')
            ->orderBy('t1.begin desc')
            ->beginIF($this->viewType != 'json')->limit($this->params->num)->fi()
            ->fetchAll();
    }

    /**
     * Print releases block.
     * 
     * @access public
     * @return void
     */
    public function printReleaseBlock()
    {
        $this->app->loadLang('release');
        $this->view->releases = $this->dao->select('t1.*,t2.name as productName,t3.name as buildName')->from(TABLE_RELEASE)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_BUILD)->alias('t3')->on('t1.build=t3.id')
            ->where('t1.deleted')->eq('0')
            ->orderBy('t1.id desc')
            ->beginIF($this->viewType != 'json')->limit($this->params->num)->fi()
            ->fetchAll();
    }

    /**
     * Print Build block.
     * 
     * @access public
     * @return void
     */
    public function printBuildBlock()
    {
        $this->app->loadLang('build');
        $this->view->builds = $this->dao->select('t1.*,t2.productName')->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq('0')
            ->orderBy('t1.id desc')
            ->beginIF($this->viewType != 'json')->limit($this->params->num)->fi()
            ->fetchAll();
    }

    /**
     * Print product block.
     * 
     * @access public
     * @return void
     */
    public function printProductBlock()
    {
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init(0, $this->params->num, 1);
        $this->view->productStats = $this->loadModel('product')->getStats('order_desc', $this->viewType != 'json' ? $pager : '');
    }

    /**
     * Print project block.
     * 
     * @access public
     * @return void
     */
    public function printProjectBlock()
    {
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init(0, $this->params->num, 1);
        $this->view->projectStats = $this->loadModel('project')->getProjectStats($status = 'undone', $productID = 0, $branch = 0, $itemCounts = 30, $orderBy = 'order_desc', $this->viewType != 'json' ? $pager : '');
    }
}
