<?php
$lang->custom->common     = '自定義';
$lang->custom->index      = '首頁';
$lang->custom->set        = '自定義配置';
$lang->custom->restore    = '恢復預設';
$lang->custom->key        = '鍵';
$lang->custom->value      = '值';
$lang->custom->flow       = '流程';
$lang->custom->working    = '工作方式';
$lang->custom->select     = '請選擇流程：';
$lang->custom->branch     = '多分支';
$lang->custom->owner      = '所有者';
$lang->custom->module     = '模組';
$lang->custom->section    = '附加部分';
$lang->custom->lang       = '所屬語言';
$lang->custom->setPublic  = '設為公共';
$lang->custom->required   = '必填項';
$lang->custom->score      = '積分';
$lang->custom->timezone   = '時區';
$lang->custom->scoreReset = '重置積分';
$lang->custom->scoreTitle = '積分功能';

$lang->custom->object['story']    = $lang->storyCommon;
$lang->custom->object['task']     = '任務';
$lang->custom->object['bug']      = 'Bug';
$lang->custom->object['testcase'] = '用例';
$lang->custom->object['testtask'] = '版本';
$lang->custom->object['todo']     = '待辦';
$lang->custom->object['user']     = '用戶';
$lang->custom->object['block']    = '區塊';

$lang->custom->story = new stdClass();
$lang->custom->story->fields['priList']          = '優先順序';
$lang->custom->story->fields['sourceList']       = '來源';
$lang->custom->story->fields['reasonList']       = '關閉原因';
$lang->custom->story->fields['stageList']        = '階段';
$lang->custom->story->fields['statusList']       = '狀態';
$lang->custom->story->fields['reviewResultList'] = '評審結果';
$lang->custom->story->fields['review']           = '評審流程';

$lang->custom->task = new stdClass();
$lang->custom->task->fields['priList']    = '優先順序';
$lang->custom->task->fields['typeList']   = '類型';
$lang->custom->task->fields['reasonList'] = '關閉原因';
$lang->custom->task->fields['statusList'] = '狀態';
$lang->custom->task->fields['hours']      = '工時';

$lang->custom->bug = new stdClass();
$lang->custom->bug->fields['priList']        = '優先順序';
$lang->custom->bug->fields['severityList']   = '嚴重程度';
$lang->custom->bug->fields['osList']         = '操作系統';
$lang->custom->bug->fields['browserList']    = '瀏覽器';
$lang->custom->bug->fields['typeList']       = '類型';
$lang->custom->bug->fields['resolutionList'] = '解決方案';
$lang->custom->bug->fields['statusList']     = '狀態';
$lang->custom->bug->fields['longlife']       = '久未處理天數';

$lang->custom->testcase = new stdClass();
$lang->custom->testcase->fields['priList']    = '優先順序';
$lang->custom->testcase->fields['typeList']   = '類型';
$lang->custom->testcase->fields['stageList']  = '階段';
$lang->custom->testcase->fields['resultList'] = '執行結果';
$lang->custom->testcase->fields['statusList'] = '狀態';
$lang->custom->testcase->fields['review']     = '評審流程';

$lang->custom->testtask = new stdClass();
$lang->custom->testtask->fields['priList']    = '優先順序';
$lang->custom->testtask->fields['statusList'] = '狀態';

$lang->custom->todo = new stdClass();
$lang->custom->todo->fields['priList']    = '優先順序';
$lang->custom->todo->fields['typeList']   = '類型';
$lang->custom->todo->fields['statusList'] = '狀態';

$lang->custom->user = new stdClass();
$lang->custom->user->fields['roleList']     = '職位';
$lang->custom->user->fields['statusList']   = '狀態';
$lang->custom->user->fields['contactField'] = '可用聯繫方式';
$lang->custom->user->fields['deleted']      = '列出已刪除用戶';

$lang->custom->system = array('flow', 'working', 'required', 'score');

$lang->custom->block->fields['closed'] = '關閉的區塊';

$lang->custom->currentLang = '適用當前語言';
$lang->custom->allLang     = '適用所有語言';

$lang->custom->confirmRestore = '是否要恢復預設配置？';

$lang->custom->notice = new stdclass();
$lang->custom->notice->userFieldNotice             = '控制以上欄位在用戶相關頁面是否顯示，留空則全部顯示';
$lang->custom->notice->canNotAdd                   = '該項參與運算，不提供自定義添加功能';
$lang->custom->notice->forceReview                 = "指定人提交的%s必須評審。";
$lang->custom->notice->forceNotReview              = "指定人提交的%s不需要評審。";
$lang->custom->notice->longlife                    = 'Bug列表頁面的久未處理標籤中，列出設置天數之前未處理的Bug。';
$lang->custom->notice->invalidNumberKey            = '鍵值應為不大於255的數字';
$lang->custom->notice->invalidStringKey            = '鍵值應當為小寫英文字母、數字或下劃線的組合';
$lang->custom->notice->cannotSetTimezone           = 'date_default_timezone_set方法不存在或禁用，不能設置時區。';
$lang->custom->notice->noClosedBlock               = '沒有永久關閉的區塊';
$lang->custom->notice->required                    = '頁面提交時，選中的欄位必填';
$lang->custom->notice->conceptResult               = '我們已經根據您的選擇為您設置了<b> %s-%s </b>模式，使用<b>%s</b> + <b> %s</b>。';
$lang->custom->notice->conceptPath                 = '您可以在：後台 -> 自定義 -> 流程頁面修改。';

$lang->custom->notice->indexPage['product']        = "從8.2版本起增加了產品主頁視圖，是否預設進入產品主頁？";
$lang->custom->notice->indexPage['project']        = "從8.2版本起增加了項目主頁視圖，是否預設進入項目主頁？";
$lang->custom->notice->indexPage['qa']             = "從8.2版本起增加了測試主頁視圖，是否預設進入測試主頁？";

$lang->custom->notice->invalidStrlen['ten']        = '鍵的長度必須小於10個字元！';
$lang->custom->notice->invalidStrlen['twenty']     = '鍵的長度必須小於20個字元！';
$lang->custom->notice->invalidStrlen['thirty']     = '鍵的長度必須小於30個字元！';
$lang->custom->notice->invalidStrlen['twoHundred'] = '鍵的長度必須小於225個字元！';

$lang->custom->storyReview    = '評審流程';
$lang->custom->forceReview    = '強制評審';
$lang->custom->forceNotReview = '不需要評審';
$lang->custom->reviewList[1]  = '開啟';
$lang->custom->reviewList[0]  = '關閉';

$lang->custom->deletedList[1] = '列出';
$lang->custom->deletedList[0] = '不列出';

$lang->custom->workingHours   = '每天可用工時';
$lang->custom->weekend        = '休息日';
$lang->custom->weekendList[2] = '雙休';
$lang->custom->weekendList[1] = '單休';

$lang->custom->productProject = new stdclass();
$lang->custom->productProject->relation['0_0'] = '產品 - 項目';
$lang->custom->productProject->relation['0_1'] = '產品 - 迭代';
$lang->custom->productProject->relation['1_1'] = '項目 - 迭代';
$lang->custom->productProject->relation['0_2'] = '產品 - 衝刺';
$lang->custom->productProject->relation['1_2'] = '項目 - 衝刺';

$lang->custom->productProject->notice = '請根據實際情況選擇適合自己團隊的概念。';

$lang->custom->workingList['full']      = '完整研發管理工具';
$lang->custom->workingList['onlyTest']  = '測試管理工具';
$lang->custom->workingList['onlyStory'] = "{$lang->storyCommon}管理工具";
$lang->custom->workingList['onlyTask']  = '任務管理工具';

$lang->custom->menuTip  = '點擊顯示或隱藏導航條目，拖拽來更改顯示順序。';
$lang->custom->saveFail = '保存失敗！';
$lang->custom->page     = '頁面';

$lang->custom->scoreStatus[1] = '開啟';
$lang->custom->scoreStatus[0] = '關閉';

$lang->custom->moduleName['product']     = $lang->productCommon;
$lang->custom->moduleName['productplan'] = '計劃';
$lang->custom->moduleName['project']     = $lang->projectCommon;

$lang->custom->conceptQuestions['overview']         = "1. 下述哪種組合方式更適合您公司的管理現狀？";
$lang->custom->conceptQuestions['story']            = "2. 您公司是在使用需求概念還是用戶故事概念？";
$lang->custom->conceptQuestions['requirementpoint'] = "3. 您公司是在使用工時還是功能點來做規模估算？";
$lang->custom->conceptQuestions['storypoint']       = "3. 您公司是在使用工時還是故事點來做規模估算？";

$lang->custom->conceptOptions = new stdclass;

$lang->custom->conceptOptions->story = array();
$lang->custom->conceptOptions->story['0'] = '需求';
$lang->custom->conceptOptions->story['1'] = '故事';

$lang->custom->conceptOptions->hourPoint = array();
$lang->custom->conceptOptions->hourPoint['0'] = '工時';
$lang->custom->conceptOptions->hourPoint['1'] = '故事點';
$lang->custom->conceptOptions->hourPoint['2'] = '功能點';
