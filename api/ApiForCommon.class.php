<?php

/**
 *web端和app移动端的共同接口
 *@author Joker-Long
 *编写日期：2016年8月13日下午8:23:22
 */
class ApiForCommon
{

    /**
     * 获取活动列表
     */
    public static function getListSituation()
    {
        global $result; // 更新userLog日志表要用
                        // print_r($_POST);
        $pageSize = $_POST['pageSize'] ? $_POST['pageSize'] : 5;
        $uId = $_POST['uId'] ? $_POST['uId'] : '';
        $uIdBy = $uId ? "s.uId={$uId}" : '';
        $cId = $_POST['cId'] ? $_POST['cId'] : '';
        $cIdBy = $cId ? "s.cId={$cId}" : '';
        $sId = $_POST['sId'] ? $_POST['sId'] : '';
        $sIdBy = $sId ? "s.sId={$sId}" : '';
        $firstTime = $_POST['firstTime'] ? $_POST['firstTime'] : ''; // 获取更新的记录
        $firstTimeBy = $firstTime ? "s.sPubtime>$firstTime" : '';
        
        $lastTime = $_POST['lastTime'] ? $_POST['lastTime'] : ''; // 获取历史的记录
        $lastTimeBy = $lastTime ? "s.sPubtime<$lastTime" : '';
        $order = $_POST['order'] ? $_POST['order'] : 'sPubtime';
        if ($firstTimeBy) { // 获取最新消息则时间倒序分页传
            $type = $_POST['type'] ? $_POST['type'] : 'asc';
        } else { // 加载历史记录则时间正序分页传
            $type = $_POST['type'] ? $_POST['type'] : 'desc';
        }
        $type = $type ? $type : 'desc';
        // $orderBy=$firstTime?"order by s.{$order} {$type}":"order by s.{$order} {$type}";
        $orderBy = "order by s.{$order} {$type}";
        $keywords = $_POST['keywords'] ? $_POST['keywords'] : '';
        $keywordsBy = $keywords ? "(s.`sTitle` like '%{$keywords}%' OR s.`sPosition` like '%{$keywords}%' OR u.`username` like '%{$keywords}%')" : null;
        $where = '';
        if ($firstTimeBy || $lastTimeBy || $keywordsBy || $sIdBy || $cIdBy || $uIdBy) { // where不为空
            $where = "where ";
        }
        if ($firstTimeBy) {
            $where1[] = $firstTimeBy;
        }
        if ($lastTimeBy) {
            $where1[] = $lastTimeBy;
        }
        if ($keywordsBy) {
            $where1[] = $keywordsBy;
        }
        if ($sIdBy) {
            $where1[] = $sIdBy;
        }
        if ($cIdBy) {
            $where1[] = $cIdBy;
        }
        if ($uIdBy) {
            $where1[] = $uIdBy;
        }
        $i = 0;
        if ($where1) {
            foreach ($where1 as $whe) {
                if ($i == 0) {
                    $where .= $whe;
                    $i ++;
                } else {
                    $where .= " and {$whe}";
                }
            }
        }
        $site = OUR_SITE;
        $path = "{$site}/uploads/";
        $rows = Situation::getSituation($pageSize, $where, $orderBy);
        if ($rows === false) {
            $result['status'] = 3;
            $result['message'] = '获取数据失败';
        } elseif ($rows === null) {
            $result['status'] = 2;
            $result['message'] = '没有更多数据';
        } else {
            $i = 0;
            foreach ($rows as $row) {
                if ($row['isTransmit'] != 0) { // 表示为转发的活动，返回数据有改变
                    $row2 = $row;
                    /*$result1[$i]['ts_uId'] = urlencode($row2['id']);
                    $result1[$i]['ts_username'] = urlencode($row2['username']);
                    $result1[$i]['ts_sex'] = urlencode($row2['sex']);
                    $result1[$i]['ts_face'] = urlencode($path . $row2['face']);
                    $result1[$i]['id'] = urlencode($row2['id']); */
                    $result1[$i] = array();
                    Situation::AddSituationInfo($result1, $i, $row, $path);
                    $row = Situation::getOneSituation($row['isTransmit']);
                    if($row){
                        foreach ($row as $key => $value) {
                            $result1[$i][$key] = urlencode($value);
                        }
                        $result1[$i]['face'] = urlencode($path . $row['face']);
                        Situation::ChangeSituationInfo($result1, $i);
                        $result1[$i]['id'] = $row2['id'];
                        $result1[$i]['sPubtime'] = $row2['sPubtime'];//不这样排序出错
                        $result1[$i]['isDel'] = 0;
                        Situation::isOption($result1, $i, $row['id']);//引用去添加是否赞过等
                        $j = 0;
                        $images = Album::getSituationImageBysId($row['id']);
                        if ($images === false) {
                            // $result['status'] = 4;
                            // $result['message'] = '获取活动图片失败';
                            $result1[$i]['sImage'] = '';
                        } elseif ($images === null) {
                            // $result['status'] = 5;
                            // $result['message'] = '该活动没有上传图片';
                            $result1[$i]['sImage'] = '';
                        } else {
                            foreach ($images as $image) {
                        
                                $result1[$i]['sImage'][$j . 'p'] = urlencode($path . $image['albumPath']);
                                $j ++;
                            }
                        }
                    }else{//活动被删除
                        foreach ($row2 as $key => $value) {
                            $result1[$i][$key] = urlencode($value);
                        }
                        $result1[$i]['face'] = urlencode($path . $row['face']);
                        Situation::ChangeSituationInfo($result1, $i);
                        $result1[$i]['isDel'] = 1;
                        Situation::isOption($result1, $i, $row['id']);
                    }
                }else{//不是转发
                    foreach ($row as $key => $value) {
                        $result1[$i][$key] = urlencode($value);
                    }
                    $result1[$i]['face'] = urlencode($path . $row['face']);
                    Situation::isOption($result1, $i, $row['id']);//引用去添加是否赞过等
                    $j = 0;
                    $images = Album::getSituationImageBysId($row['id']);
                    if ($images === false) {
                        // $result['status'] = 4;
                        // $result['message'] = '获取活动图片失败';
                        $result1[$i]['sImage'] = '';
                    } elseif ($images === null) {
                        // $result['status'] = 5;
                        // $result['message'] = '该活动没有上传图片';
                        $result1[$i]['sImage'] = '';
                    } else {
                        foreach ($images as $image) {
                    
                            $result1[$i]['sImage'][$j . 'p'] = urlencode($path . $image['albumPath']);
                            $j ++;
                        }
                    }
                }
                $i ++;
            }
            $result['status'] = 1;
            $result['message'] = "成功获取{$i}条数据";
            $result['datas'] = $result1;
        }
        echo Response::json($result);
    }

    /**
     * 通过活动id获取活动详情
     */
    public static function getSituationById()
    {
        global $result; // 更新userLog日志表要用
        $sId = $_POST['sId'];
        $row = Situation::getOneSituation($sId);
        if ($row) {
            if($row['isTransmit'] != 0){//表示为转发的信息
                $row = Situation::getOneSituation($row['isTransmit']);
            }
            $site = OUR_SITE;
            $path = "{$site}/uploads/";
            $result['status'] = 1;
            $result['message'] = '获取活动数据成功';
            foreach ($row as $key => $value) {
                $result1[$key] = urlencode($value);
            }
            $result1['face'] = urlencode($path . $row['face']);
            if ($_SESSION['uId'] != '') { // 如果登录了,则显示是否赞过等
                $bool = User::checkPraise($_SESSION['uId'], $sId);
                if ($bool) {
                    $result1['isPraise'] = 1; // 赞了
                } else {
                    $result1['isPraise'] = 0; // 没有赞过
                }
                $bool = User::checkCollect($_SESSION['uId'], $sId);
                if ($bool) {
                    $result1['isCollect'] = 1; // 已经收藏
                } else {
                    $result1['isCollect'] = 0; // 还没有收藏
                }
                $bool = User::checkJoin($_SESSION['uId'], $sId);
                if ($bool) {
                    $result1['isJoin'] = 1; // 已经参加
                } else {
                    $result1['isJoin'] = 0; // 还没有参加
                }
            } else { // 否则，默认为没有赞过
                $result1['isPraise'] = 0; // 没有赞过
                $result1['isCollect'] = 0; // 还没有收藏
                $result1['isJoin'] = 0; // 还没有参加
            }
            // 获取该活动的图片
            $images = Album::getSituationImageBysId($sId);
            
            if ($images === false) {
                // $result['status'] = 5;
                // $result['message'] = '获取活动图片失败';
                $result1['sImage'] = '';
            } elseif ($images === null) {
                // $result['status'] = 4;
                // $result['message'] = '该活动没有上传图片';
                $result1['sImage'] = '';
            } else {
                $j = 0;
                foreach ($images as $image) {
                    $result1['sImage'][$j . 'p'] = urlencode($path . $image['albumPath']);
                    $j ++;
                }
            }
            $result['datas'] = $result1;
        } elseif ($row === null) {
            $result['status'] = 2;
            $result['message'] = '活动不存在';
        } else {
            $result['status'] = 3;
            $result['message'] = '获取活动数据失败';
        }
        echo Response::json($result);
    }

    /**
     * 评论活动
     * 要判断用户是否登录
     */
    public static function comment()
    {
        global $result; // 更新userLog日志表要用
        global $db_obj;
        $arr['uId1'] = $_SESSION['uId'] ? $_SESSION['uId'] : ''; // 如果用户已经登录，则从session中获取用户的id
        if ($arr['uId1'] !== '') {
            $arr['sId'] = $_POST['sId'];
            $sid = Situation::isTransmit($arr['sId']);//是否是转发的活动，是则把上传的活动id换成原来的活动的id
            if($sid){
                $arr['sId'] = $sid;
            }
            $arr['content'] = $_POST['content'];
            $arr['time'] = time();
            if ((filter_input(INPUT_POST, 'content', FILTER_CALLBACK, array(
                'options' => 'Comment::validate_str'
            )))) {
                $sqls[1] = "select id from hw_situation where id={$arr['sId']}"; // 要评论的活动是否存在
                $sqls[2] = "select id from hw_user where id={$arr['uId1']}"; // 要评论人是否存在
                $sqls[3] = Sql::sqlForInsert('hw_comment', $arr); // 插入评论表
                $sqls[4] = "update hw_situation set comment=comment+1 where id={$arr['sId']}"; // 更新活动表中的评论数
                                                                                               // 更新user表中的记录
                $field = 'lastCommentTime';
                $lastTime = time();
                $bool = User::checkIsFirst($arr['uId1'], $field);
                if ($bool) { // 是当天第一次参加
                    $sqls[5] = "update hw_user set {$field}={$lastTime},experience=experience+2 where id={$arr['uId1']}"; // 更新活动表中的评论数
                } else {
                    $sqls[5] = "update hw_user set {$field}={$lastTime} where id={$arr['uId1']}"; // 更新活动表中的评论数
                }
                $bool = $db_obj->transaction($sqls); // 执行事务
                if ($bool) {
                    $result['status'] = 1;
                    $result['message'] = '评论成功';
                } else {
                    $result['status'] = 2;
                    $result['message'] = '评论失败,可能是评论的活动已经被删除';
                }
            } else {
                $result['status'] = 3;
                $result['message'] = '请输入合法内容';
            }
        } else {
            $result['status'] = 4;
            $result['message'] = '请先登录';
        }
        echo Response::json($result);
    }

    /**
     * 回复评论
     */
    public static function reply()
    {
        global $result; // 更新userLog日志表要用
        global $db_obj;
        $arr['uId1'] = $_SESSION['uId'] ? $_SESSION['uId'] : ''; // 如果用户已经登录，则从session中获取用户的id
        if ($arr['uId1'] !== '') {
            $arr['sId'] = $_POST['sId'];
            $sid = Situation::isTransmit($arr['sId']);//是否是转发的活动，是则把上传的活动id换成原来的活动的id
            if($sid){
                $arr['sId'] = $sid;
            }
            $arr['cId'] = $_POST['cId'];
            // $arr['uId1'] = $_POST['uId1'];
            $arr['uId2'] = $_POST['uId2'];
            $arr['content'] = $_POST['content'];
            $arr['time'] = time();
            if ((filter_input(INPUT_POST, 'content', FILTER_CALLBACK, array(
                'options' => 'Comment::validate_str'
            )))) {
                $sqls[1] = "select id from hw_situation where id={$arr['sId']}"; // 要评论的活动是否存在
                $sqls[2] = "select id from hw_comment where id={$arr['cId']} and uId1={$arr['uId2']}"; // 被回复人的评论是否存在
                $sqls[3] = "select id from hw_user where id={$arr['uId1']}"; // 回复人是否存在
                $sqls[4] = "select id from hw_user where id={$arr['uId2']}"; // 被回复人是否存在
                $sqls[5] = Sql::sqlForInsert('hw_comment', $arr); // 插入评论表
                $sqls[6] = "update hw_situation set comment=comment+1 where id={$arr['sId']}"; // 更新活动表中的评论数
                $bool = $db_obj->transaction($sqls); // 执行事务
                if ($bool) {
                    $result['status'] = 1;
                    $result['message'] = '回复成功';
                } else {
                    $result['status'] = 2;
                    $result['message'] = '回复失败';
                }
            } else {
                $result['status'] = 3;
                $result['message'] = '请输入合法内容';
            }
        } else {
            $result['status'] = 4;
            $result['message'] = '请先登录';
        }
        echo Response::json($result);
    }

    /**
     * 获取活动的评论列表
     */
    public static function getListComment()
    {
        global $result; // 更新userLog日志表要用
        global $db_obj;
        $sId = $_POST['sId'];
        $sid = Situation::isTransmit($sId);//是否是转发的活动，是则把上传的活动id换成原来的活动的id
        if($sid){
            $sId = $sid;
        }
        $site = OUR_SITE;
        $path = "{$site}/uploads/";
        if (is_numeric($sId)) {
            $rows = Comment::getAllComment($sId);
            if ($rows) {
                // 将评论人.回复人和被回复人的信息也返回
                foreach ($rows as &$row) {
                    // 评论人或者回复人
                    $sql = "select id,username,face,sex from hw_user where id={$row['uId1']}";
                    $user1 = $db_obj->fetchOne($sql);
                    foreach ($user1 as $k => $v) {
                        $user1[$k] = urlencode($v);
                    }
                    $user1['face'] = urlencode($path . $user1['face']);
                    $row['user1'] = $user1;
                    if ($row['uId2']) {
                        // 若被回复人存在，则也将被回复人的信息返回
                        $sql = "select id,username,face,sex from hw_user where id={$row['uId2']}";
                        $user2 = $db_obj->fetchOne($sql);
                        foreach ($user2 as $k => $v) {
                            $user2[$k] = urlencode($v);
                        }
                        $user2['face'] = urlencode($path . $user2['face']);
                        $row['user2'] = $user2;
                    }
                    $row['content'] = urlencode($row['content']);
                    unset($row['uId1']); // 销毁uId1
                    unset($row['uId2']); // 销毁uId2
                }
                $result['datas'] = $rows;
                $result['status'] = 1;
                $result['message'] = '获取评论列表成功';
            } elseif ($rows === null) {
                $result['status'] = 2;
                $result['message'] = '该活动还没有评论';
            } elseif ($rows === false) {
                $result['status'] = 3;
                $result['message'] = '获取评论列表失败';
            }
        } else {
            $result['status'] = 4;
            $result['message'] = '参数错误';
        }
        echo Response::json($result);
    }

    /**
     * 用户发布活动
     */
    public static function pubSituation()
    {
        global $result; // 更新userLog日志表要用
        global $db_obj;
        $arr['uId'] = $_SESSION['uId'] ? $_SESSION['uId'] : '';
        if ($arr['uId'] !== '') { // 已经登录
            $arr['sId'] = $_POST['sId'] ? $_POST['sId'] : '';
            $arr['cId'] = $_POST['cId'] ? $_POST['cId'] : '';
            $arr['sTitle'] = $_POST['sTitle'] ? $_POST['sTitle'] : '无标题';
            $arr['sDesc'] = $_POST['sDesc'] ? $_POST['sDesc'] : '这个家伙很懒,没有给发布的活动进行描述';
            $arr['sPosition'] = $_POST['sPosition'] ? $_POST['sPosition'] : '待定';
            $arr['sGatherPosition'] = $_POST['sGatherPosition'] ? $_POST['sGatherPosition'] : '待定';
            $arr['sNumber'] = $_POST['sNumber'] ? $_POST['sNumber'] : 10;
            // $arr['sCurrentNumber'] = 0;
            $arr['sPubtime'] = $_POST['sPubtime'] ? $_POST['sPubtime'] : time();
            $arr['sSignupBtime'] = $_POST['sSignupBtime'] ? $_POST['sSignupBtime'] : time();
            $arr['sSignupEtime'] = $_POST['sSignupEtime'] ? $_POST['sSignupEtime'] : time();
            $arr['sBtime'] = $_POST['sBtime'] ? $_POST['sBtime'] : time();
            $arr['sEtime'] = $_POST['sEtime'] ? $_POST['sEtime'] : time();
            // 过滤输入的字符串
            $arr = Str::filterInput($arr);
            $sqls[1] = "select id from hw_campus where id={$arr['cId']} and sId={$arr['sId']}";
            $sqls[2] = "update hw_user set pubNumber=pubNumber+1 where id={$arr['uId']}";
            $bool = $db_obj->transaction($sqls); // post的校区是否属于post的学校 // 标题0到45个字符，15个汉字(utf-8)
            if ($bool && Str::validate_str($arr['sTitle'], 0, 45) && Str::validate_str($arr['sPosition'], 0, 45) && Str::validate_str($arr['sGatherPosition'], 0, 45) && Str::validate_str($arr['sDesc'], 0, 255 * 3)) {
                $bool2 = User::pubSituation($arr);
                if ($bool2) {
                    $result['status'] = 1;
                    $result['message'] = '发布成功';
                } else {
                    $result['status'] = 2;
                    $result['message'] = '发布失败';
                }
            } else {
                $result['status'] = 3;
                $result['message'] = '参数错误';
            }
        } else {
            $result['status'] = 4;
            $result['message'] = '请先登录';
        }
        echo Response::json($result);
    }

    /**
     * 用户转发活动
     */
    public static function transmitSituation()
    {
        global $result; // 更新userLog日志表要用
        global $db_obj;
        $arr['uId'] = $_SESSION['uId'] ? $_SESSION['uId'] : '';
        if ($arr['uId'] !== '') { // 已经登录
            $sId = $_POST['sId'];
            $sid = Situation::isTransmit($arr['sId']);//是否是转发的活动，是则把上传的活动id换成原来的活动的id
            if($sid){
                $sId = $sid;
            }
            $sql = "select * from hw_situation where id={$arr['sId']}";
            $row = $db_obj->fetchOne($sql);
            $arr['transmitComment'] = $_POST['transmitComment'] ? $_POST['transmitComment'] : ''; // 转发评语
                                                                                                  // 标题0到45个字符，15个汉字(utf-8)
            if ($row && Str::validate_str($arr['transmitComment'], 0, 255 * 3)) {
                $arr['sId'] = $row['sId'];
                $arr['cId'] = $row['cId'];
                $arr['sTitle'] = $row['sTitle'];
                $arr['sDesc'] = '';
                $arr['sPosition'] = $row['sPosition'];
                $arr['sGatherPosition'] = $row['sGatherPosition'];
                $arr['sNumber'] = '';
                $arr['sCurrentNumber'] = '';
                $arr['sPubtime'] = time();
                $arr['sSignupBtime'] = '';
                $arr['sSignupEtime'] = '';
                $arr['sBtime'] = '';
                $arr['sEtime'] = '';
                $arr['isTransmit'] = $sId; // 所转发活动的原始id
                // 过滤输入的字符串
                $arr = Str::filterInput($arr);
                $bool = User::transmitSituation($arr);
                if ($bool) {
                    $result['status'] = 1;
                    $result['message'] = '转发成功';
                } else {
                    $result['status'] = 2;
                    $result['message'] = '转发失败';
                }
            } else {
                $result['status'] = 3;
                $result['message'] = '你要转发的活动不存在';
            }
        } else {
            $result['status'] = 4;
            $result['message'] = '请先登录';
        }
        echo Response::json($result);
    }

    /**
     * 用户删除活动
     *
     * @return string
     */
    public static function delSituation()
    {
        global $result; // 更新userLog日志表要用
        global $db_obj;
        $arr['uId'] = $_SESSION['uId'] ? $_SESSION['uId'] : '';
        if ($arr['uId'] !== '') {
            $arr['sId'] = $_POST['sId'] ? $_POST['sId'] : '';
            if (is_numeric($arr['sId'])) {
                $sql = "select id from hw_situation where id={$arr['sId']} and uId={$arr['uId']}"; // 该用户是否发布了该活动
                if ($db_obj->fetchOne($sql)) {
                    $bool = User::delSituation($arr);
                    if ($bool) {
                        $result['status'] = 1;
                        $result['message'] = '删除成功';
                    } else {
                        $result['status'] = 2;
                        $result['message'] = '删除失败';
                    }
                } else {
                    $result['status'] = 3;
                    $result['message'] = '要删除的活动不存在';
                }
            } else {
                $result['status'] = 4;
                $result['message'] = '参数错误';
            }
        } else {
            $result['status'] = 5;
            $result['message'] = '请先登录';
        }
        echo Response::json($result);
    }

    /**
     * 检查用户是否参加过该活动,用于活动列表默认显示是否参加过
     */
    public static function checkJoin()
    {
        global $result; // 更新userLog日志表要用
        global $db_obj;
        $uId = $_SESSION['uId'] ? $_SESSION['uId'] : ''; // 如果用户已经登录，则从session中获取用户的id
        if ($uId !== '') {
            $sId = $_POST['sId'] ? $_POST['sId'] : '';
            if ($sId != '') {
                $bool = User::checkJoin($uId, $sId);
                if ($bool) {
                    $result['status'] = 1;
                    $result['message'] = '您已经参加此活动';
                } else {
                    $result['status'] = 2;
                    $result['message'] = '您还没有此活动';
                }
            } else {
                $result['status'] = 3;
                $result['message'] = '活动id错误';
            }
        } else {
            $result['status'] = 4;
            $result['message'] = '还没有登录,默认显示还没有参加';
        }
        echo Response::json($result);
    }

    /**
     * 参加活动
     */
    public static function join()
    {
        global $result; // 更新userLog日志表要用
        global $db_obj;
        $uId = $_SESSION['uId'] ? $_SESSION['uId'] : ''; // 如果用户已经登录，则从session中获取用户的id
        if ($uId !== '') {
            $arr['sId'] = $_POST['sId'] ? $_POST['sId'] : NULL;
            $sid = Situation::isTransmit($arr['sId']);//是否是转发的活动，是则把上传的活动id换成原来的活动的id
            if($sid){
                $arr['sId'] = $sid;
            }
            $arr['uId'] = $_SESSION['uId']; // 当前用户
            $arr['time'] = time();
            $row = Situation::getOneSituation($arr['sId']); // 检查该活动是否存在
                                                            // 再检查改用户是否已经参加了此活动，已经参加则提示已经参加
            $check = User::checkUserJoin($arr['sId'], $arr['uId']);
            if (! $check) {
                if ($row) {
                    if($row['sCurrentNumber'] < $row['sNumber']){//看看是否参加的人数已经达到了上限
                        $bool = User::join($arr);
                        if ($bool) {
                            $result['status'] = 1;
                            $result['message'] = '参加活动成功';
                        } else {
                            $result['status'] = 2;
                            $result['message'] = '参加活动失败';
                        }
                    }else{
                        $result['status'] = 6;
                        $result['message'] = '人数达到上限，可以联系楼主增加名额';
                    }
                } else {
                    $result['status'] = 3;
                    $result['message'] = '该活动不存在';
                }
            } else {
                $result['status'] = 4;
                $result['message'] = '您已经参加了该活动';
            }
        } else {
            $result['status'] = 5;
            $result['message'] = '请先登录';
        }
        echo Response::json($result);
    }

    /**
     * 取消参加活动
     */
    public static function cancelJoin()
    {
        global $result; // 更新userLog日志表要用
        global $db_obj;
        $uId = $_SESSION['uId'] ? $_SESSION['uId'] : ''; // 如果用户已经登录，则从session中获取用户的id
        if ($uId !== '') {
            $arr['sId'] = $_POST['sId'] ? $_POST['sId'] : NULL;
            $sid = Situation::isTransmit($arr['sId']);//是否是转发的活动，是则把上传的活动id换成原来的活动的id
            if($sid){
                $arr['sId'] = $sid;
            }
            $arr['uId'] = $_SESSION['uId']; // 当前用户
            $row = Situation::getOneSituation($arr['sId']); // 检查该活动是否存在
                                                            // 再检查改用户是否已经参加了此活动，已经参加则提示已经参加
            $check = User::checkUserJoin($arr['sId'], $arr['uId']);
            if ($check) {
                if ($row) {
                    $bool = User::cancelJoin($arr);
                    if ($bool) {
                        $result['status'] = 1;
                        $result['message'] = '取消参加活动成功';
                    } else {
                        $result['status'] = 2;
                        $result['message'] = '取消参加活动失败';
                    }
                } else {
                    $result['status'] = 3;
                    $result['message'] = '该活动不存在';
                }
            } else {
                $result['status'] = 4;
                $result['message'] = '您还未参加了该活动，不能执行取消参加操作';
            }
        } else {
            $result['status'] = 5;
            $result['message'] = '请先登录';
        }
        echo Response::json($result);
    }

    /**
     * 获取活动参加的活动列表
     */
    public static function getUserJoin()
    {
        global $result; // 更新userLog日志表要用
        $uId = $_SESSION['uId'] ? $_SESSION['uId'] : '';
        if ($_POST['uId']) {
            $uId = $_POST['uId'] ? $_POST['uId'] : '';
        }
        $rows = User::getUserJoinSituationByUid($uId);
        if ($rows) {
            foreach ($rows as $row) {
                $joinTime[] = $row['time'];
                $sIds[] = $row['sId'];
            }
            $arang = join(',', array_values($sIds));
            $arangBy = "s.id in({$arang})";
            $pageSize = $_POST['pageSize'] ? $_POST['pageSize'] : 5;
            $cId = $_POST['cId'] ? $_POST['cId'] : '';
            $cIdBy = $cId ? "s.cId={$cId}" : '';
            $sId = $_POST['sId'] ? $_POST['sId'] : '';
            $sIdBy = $sId ? "s.sId={$sId}" : '';
            $firstTime = $_POST['firstTime'] ? $_POST['firstTime'] : ''; // 获取更新的记录
            $firstTimeBy = $firstTime ? "s.sPubtime>$firstTime" : '';
            
            $lastTime = $_POST['lastTime'] ? $_POST['lastTime'] : ''; // 获取历史的记录
            $lastTimeBy = $lastTime ? "s.sPubtime<$lastTime" : '';
            $order = $_POST['order'] ? $_POST['order'] : 'sPubtime';
            if ($firstTimeBy) { // 获取最新消息则时间倒序分页传
                $type = $_POST['type'] ? $_POST['type'] : 'asc';
            } else { // 加载历史记录则时间正序分页传
                $type = $_POST['type'] ? $_POST['type'] : 'desc';
            }
            $type = $type ? $type : 'desc';
            // $orderBy=$firstTime?"order by s.{$order} {$type}":"order by s.{$order} {$type}";
            $orderBy = "order by s.{$order} {$type}";
            $keywords = $_POST['keywords'] ? $_POST['keywords'] : '';
            $keywordsBy = $keywords ? "(s.`sTitle` like '%{$keywords}%' OR s.`sPosition` like '%{$keywords}%' OR u.`username` like '%{$keywords}%')" : null;
            $where = '';
            if ($firstTimeBy || $lastTimeBy || $keywordsBy || $sIdBy || $cIdBy || $arangBy) { // where不为空
                $where = "where ";
            }
            if ($firstTimeBy) {
                $where1[] = $firstTimeBy;
            }
            if ($lastTimeBy) {
                $where1[] = $lastTimeBy;
            }
            if ($keywordsBy) {
                $where1[] = $keywordsBy;
            }
            if ($sIdBy) {
                $where1[] = $sIdBy;
            }
            if ($cIdBy) {
                $where1[] = $cIdBy;
            }
            if ($arangBy) {
                $where1[] = $arangBy;
            }
            $i = 0;
            if ($where1) {
                foreach ($where1 as $whe) {
                    if ($i == 0) {
                        $where .= $whe;
                        $i ++;
                    } else {
                        $where .= " and {$whe}";
                    }
                }
            }
            $site = OUR_SITE;
            $path = "{$site}/uploads/";
            $rows = Situation::getSituation($pageSize, $where, $orderBy);
            if ($rows === false) {
                $result['status'] = 3;
                $result['message'] = '获取数据失败';
            } elseif ($rows === null) {
                $result['status'] = 2;
                $result['message'] = '没有更多数据';
            } else {
                $i = 0;
                foreach ($rows as $row) {
                    foreach ($row as $key => $value) {
                        $result1[$i][$key] = urlencode($value);
                    }
                    $result1[$i]['joinTime'] = urlencode($joinTime[$i]);
                    $result1[$i]['face'] = urlencode($path . $row['face']);
                    $sId = $row['id'];
                    Situation::isOption($result1, $i, $sId);//引用去添加是否赞过等
                    $j = 0;
                    $images = Album::getSituationImageBysId($sId);
                    if ($images === false) {
                        // $result['status'] = 4;
                        // $result['message'] = '获取活动图片失败';
                        $result1[$i]['sImage'] = '';
                    } elseif ($images === null) {
                        // $result['status'] = 5;
                        // $result['message'] = '该活动没有上传图片';
                        $result1[$i]['sImage'] = '';
                    } else {
                        foreach ($images as $image) {
                            
                            $result1[$i]['sImage'][$j . 'p'] = urlencode($path . $image['albumPath']);
                            $j ++;
                        }
                    }
                    $i ++;
                }
                $result['datas'] = $result1;
                $result['status'] = 1;
                $result['message'] = "成功获取{$i}条数据";
            }
        } else {
            $result['status'] = 4;
            $result['message'] = '该用户还没有参加活动';
        }
        echo Response::json($result);
    }

    /**
     * 点赞
     */
    public static function praise()
    {
        global $result; // 更新userLog日志表要用
        global $db_obj;
        $arr['uId'] = $_SESSION['uId'] ? $_SESSION['uId'] : ''; // 如果用户已经登录，则从session中获取用户的id
        if ($arr['uId'] !== '') {
            $arr['sId'] = $_POST['sId'];
            $sid = Situation::isTransmit($arr['sId']);//是否是转发的活动，是则把上传的活动id换成原来的活动的id
            if($sid){
                $arr['sId'] = $sid;
            }
            $arr['time'] = time();
            $sql = "select id from hw_situation where id={$arr['sId']}"; // 要赞的活动是否存在
            if ($db_obj->fetchOne($sql)) {
                $sql = "select id from hw_praise where sId={$arr['sId']} and uId={$arr['uId']}"; // 是否已经赞了该活动
                if (! $db_obj->fetchOne($sql)) {
                    $bool = User::praise($arr);
                    if ($bool) {
                        $result['status'] = 1;
                        $result['message'] = '点赞成功';
                    } else {
                        $result['status'] = 2;
                        $result['message'] = '点赞失败';
                    }
                } else {
                    $result['status'] = 3;
                    $result['message'] = '您已经赞过此活动';
                }
            } else {
                $result['status'] = 4;
                $result['message'] = '要赞的活动不存在';
            }
        } else {
            $result['status'] = 5;
            $result['message'] = '请先登录';
        }
        echo Response::json($result);
    }

    /**
     * 取消赞
     */
    public static function cancelPraise()
    {
        global $result; // 更新userLog日志表要用
        global $db_obj;
        $arr['uId'] = $_SESSION['uId'] ? $_SESSION['uId'] : ''; // 如果用户已经登录，则从session中获取用户的id
        if ($arr['uId'] !== '') {
            $arr['sId'] = $_POST['sId'];
            $sid = Situation::isTransmit($arr['sId']);//是否是转发的活动，是则把上传的活动id换成原来的活动的id
            if($sid){
                $arr['sId'] = $sid;
            }
            $sql = "select id from hw_situation where id={$arr['sId']}"; // 要评论的活动是否存在
            if ($db_obj->fetchOne($sql)) {
                $sql = "select id from hw_praise where sId={$arr['sId']} and uId={$arr['uId']}"; // 是否已经赞了该活动
                if ($db_obj->fetchOne($sql)) {
                    $bool = User::cancelPraise($arr);
                    if ($bool) {
                        $result['status'] = 1;
                        $result['message'] = '取消赞成功';
                    } else {
                        $result['status'] = 2;
                        $result['message'] = '取消赞失败';
                    }
                } else {
                    $result['status'] = 3;
                    $result['message'] = '您还没有赞过此活动，不能取消';
                }
            } else {
                $result['status'] = 4;
                $result['message'] = '要取消赞的活动不存在';
            }
        } else {
            $result['status'] = 5;
            $result['message'] = '请先登录';
        }
        echo Response::json($result);
    }

    /**
     * 检查用户是否赞过该活动,用于活动列表默认显示是否赞过
     */
    public static function checkPraise()
    {
        global $result; // 更新userLog日志表要用
        global $db_obj;
        $uId = $_SESSION['uId'] ? $_SESSION['uId'] : ''; // 如果用户已经登录，则从session中获取用户的id
        if ($uId !== '') {
            $sId = $_POST['sId'] ? $_POST['sId'] : '';
            if ($sId != '') {
                $bool = User::checkPraise($uId, $sId);
                if ($bool) {
                    $result['status'] = 1;
                    $result['message'] = '您赞过此活动';
                } else {
                    $result['status'] = 2;
                    $result['message'] = '您没有赞过此活动';
                }
            } else {
                $result['status'] = 3;
                $result['message'] = '活动id错误';
            }
        } else {
            $result['status'] = 4;
            $result['message'] = '还没有登录,默认显示没有赞过';
        }
        echo Response::json($result);
    }

    /**
     * 获取用户赞过的活动列表
     */
    public static function getUserPraise()
    {
        global $result; // 更新userLog日志表要用
        $uId = $_SESSION['uId'] ? $_SESSION['uId'] : '';
        if ($_POST['uId']) {
            $uId = $_POST['uId'] ? $_POST['uId'] : '';
        }
        if ($uId != '') {
            $rows = User::getUserPraiseByUid($uId);
            if ($rows) {
                foreach ($rows as $row) {
                    $praiseTime[] = $row['time'];
                    $sIds[] = $row['sId'];
                }
                $arang = join(',', array_values($sIds));
                $arangBy = "s.id in({$arang})";
                $pageSize = $_POST['pageSize'] ? $_POST['pageSize'] : 5;
                $cId = $_POST['cId'] ? $_POST['cId'] : '';
                $cIdBy = $cId ? "s.cId={$cId}" : '';
                $sId = $_POST['sId'] ? $_POST['sId'] : '';
                $sIdBy = $sId ? "s.sId={$sId}" : '';
                $firstTime = $_POST['firstTime'] ? $_POST['firstTime'] : ''; // 获取更新的记录
                $firstTimeBy = $firstTime ? "s.sPubtime>$firstTime" : '';
                
                $lastTime = $_POST['lastTime'] ? $_POST['lastTime'] : ''; // 获取历史的记录
                $lastTimeBy = $lastTime ? "s.sPubtime<$lastTime" : '';
                $order = $_POST['order'] ? $_POST['order'] : 'sPubtime';
                if ($firstTimeBy) { // 获取最新消息则时间倒序分页传
                    $type = $_POST['type'] ? $_POST['type'] : 'asc';
                } else { // 加载历史记录则时间正序分页传
                    $type = $_POST['type'] ? $_POST['type'] : 'desc';
                }
                $type = $type ? $type : 'desc';
                // $orderBy=$firstTime?"order by s.{$order} {$type}":"order by s.{$order} {$type}";
                $orderBy = "order by s.{$order} {$type}";
                $keywords = $_POST['keywords'] ? $_POST['keywords'] : '';
                $keywordsBy = $keywords ? "(s.`sTitle` like '%{$keywords}%' OR s.`sPosition` like '%{$keywords}%' OR u.`username` like '%{$keywords}%')" : null;
                $where = '';
                if ($firstTimeBy || $lastTimeBy || $keywordsBy || $sIdBy || $cIdBy || $arangBy) { // where不为空
                    $where = "where ";
                }
                if ($firstTimeBy) {
                    $where1[] = $firstTimeBy;
                }
                if ($lastTimeBy) {
                    $where1[] = $lastTimeBy;
                }
                if ($keywordsBy) {
                    $where1[] = $keywordsBy;
                }
                if ($sIdBy) {
                    $where1[] = $sIdBy;
                }
                if ($cIdBy) {
                    $where1[] = $cIdBy;
                }
                if ($arangBy) {
                    $where1[] = $arangBy;
                }
                $i = 0;
                if ($where1) {
                    foreach ($where1 as $whe) {
                        if ($i == 0) {
                            $where .= $whe;
                            $i ++;
                        } else {
                            $where .= " and {$whe}";
                        }
                    }
                }
                $site = OUR_SITE;
                $path = "{$site}/uploads/";
                $rows = Situation::getSituation($pageSize, $where, $orderBy);
                if ($rows === false) {
                    $result['status'] = 3;
                    $result['message'] = '获取数据失败';
                } elseif ($rows === null) {
                    $result['status'] = 2;
                    $result['message'] = '没有更多数据';
                } else {
                    $i = 0;
                    foreach ($rows as $row) {
                        foreach ($row as $key => $value) {
                            $result1[$i][$key] = urlencode($value);
                        }
                        $result1[$i]['praiseTime'] = urlencode($praiseTime[$i]);
                        $result1[$i]['face'] = urlencode($path . $row['face']);
                        $sId = $row['id'];
                        Situation::isOption($result1, $i, $sId);//引用去添加是否赞过等
                        $j = 0;
                        $images = Album::getSituationImageBysId($sId);
                        if ($images === false) {
                            // $result['status'] = 4;
                            // $result['message'] = '获取活动图片失败';
                            $result1[$i]['sImage'] = '';
                        } elseif ($images === null) {
                            // $result['status'] = 5;
                            // $result['message'] = '该活动没有上传图片';
                            $result1[$i]['sImage'] = '';
                        } else {
                            foreach ($images as $image) {
                                
                                $result1[$i]['sImage'][$j . 'p'] = urlencode($path . $image['albumPath']);
                                $j ++;
                            }
                        }
                        $i ++;
                    }
                    $result['datas'] = $result1;
                    $result['status'] = 1;
                    $result['message'] = "成功获取{$i}条数据";
                }
            } else {
                $result['status'] = 6;
                $result['message'] = '该用户还没有赞过活动';
            }
        } else {
            $result['status'] = 7;
            $result['message'] = '请先登录';
        }
        
        echo Response::json($result);
    }

    /**
     * 收藏活动
     */
    public static function collect()
    {
        global $result; // 更新userLog日志表要用
        global $db_obj;
        $arr['uId'] = $_SESSION['uId'] ? $_SESSION['uId'] : ''; // 如果用户已经登录，则从session中获取用户的id
        if ($arr['uId'] !== '') {
            $arr['sId'] = $_POST['sId'] ? $_POST['sId'] : '';
            $sid = Situation::isTransmit($arr['sId']);//是否是转发的活动，是则把上传的活动id换成原来的活动的id
            if($sid){
                $arr['sId'] = $sid;
            }
            $sql = "select id from hw_situation where id={$arr['sId']}"; // 要评论的活动是否存在
            if ($db_obj->fetchOne($sql)) {
                $sql = "select id from hw_collection where sId={$arr['sId']} and uId={$arr['uId']}"; // 是否已经收藏了该活动
                if (! $db_obj->fetchOne($sql)) {
                    $arr['time'] = time();
                    $bool = User::collect($arr);
                    if ($bool) {
                        $result['status'] = 1;
                        $result['message'] = '收藏成功';
                    } else {
                        $result['status'] = 2;
                        $result['message'] = '收藏失败';
                    }
                } else {
                    $result['status'] = 3;
                    $result['message'] = '您已经收藏过此活动';
                }
            } else {
                $result['status'] = 4;
                $result['message'] = '要收藏的活动不存在';
            }
        } else {
            $result['status'] = 5;
            $result['message'] = '请先登录';
        }
        echo Response::json($result);
    }

    /**
     * 取消收藏
     */
    public static function cancelCollect()
    {
        global $result; // 更新userLog日志表要用
        global $db_obj;
        $arr['uId'] = $_SESSION['uId'] ? $_SESSION['uId'] : ''; // 如果用户已经登录，则从session中获取用户的id
        if ($arr['uId'] !== '') {
            $arr['sId'] = $_POST['sId'] ? $_POST['sId'] : '';
            $sid = Situation::isTransmit($arr['sId']);//是否是转发的活动，是则把上传的活动id换成原来的活动的id
            if($sid){
                $arr['sId'] = $sid;
            }
            $sql = "select id from hw_situation where id={$arr['sId']}"; // 要评论的活动是否存在
            if ($db_obj->fetchOne($sql)) {
                $sql = "select id from hw_collection where sId={$arr['sId']} and uId={$arr['uId']}"; // 是否已经收藏了该活动
                if ($db_obj->fetchOne($sql)) {
                    $bool = User::cancelCollect($arr);
                    if ($bool) {
                        $result['status'] = 1;
                        $result['message'] = '取消收藏成功';
                    } else {
                        $result['status'] = 2;
                        $result['message'] = '取消收藏失败';
                    }
                } else {
                    $result['status'] = 3;
                    $result['message'] = '您还没收藏过此活动，不能删除';
                }
            } else {
                $result['status'] = 4;
                $result['message'] = '要取消收藏的活动不存在';
            }
        } else {
            $result['status'] = 5;
            $result['message'] = '请先登录';
        }
        echo Response::json($result);
    }

    /**
     * 检查用户是否收藏过该活动,用于活动列表默认显示是否收藏过
     */
    public static function checkCollect()
    {
        global $result; // 更新userLog日志表要用
        global $db_obj;
        $uId = $_SESSION['uId'] ? $_SESSION['uId'] : ''; // 如果用户已经登录，则从session中获取用户的id
        if ($uId !== '') {
            $sId = $_POST['sId'] ? $_POST['sId'] : '';
            if ($sId != '') {
                $bool = User::checkCollect($uId, $sId);
                if ($bool) {
                    $result['status'] = 1;
                    $result['message'] = '您收藏过此活动';
                } else {
                    $result['status'] = 2;
                    $result['message'] = '您没有收藏过此活动';
                }
            } else {
                $result['status'] = 3;
                $result['message'] = '活动id错误';
            }
        } else {
            $result['status'] = 4;
            $result['message'] = '还没有登录,默认显示没有收藏过';
        }
        echo Response::json($result);
    }

    /**
     * 获取活动参加的活动列表
     */
    public static function getUserCollection()
    {
        global $result; // 更新userLog日志表要用
        $uId = $_SESSION['uId'] ? $_SESSION['uId'] : '';
        $rows = User::getUserCollectionByUid($uId);
        if ($rows) {
            foreach ($rows as $row) {
                $collectTime[] = $row['time'];
                $sIds[] = $row['sId'];
            }
            $arang = join(',', array_values($sIds));
            $arangBy = "s.id in({$arang})";
            $pageSize = $_POST['pageSize'] ? $_POST['pageSize'] : 5;
            $cId = $_POST['cId'] ? $_POST['cId'] : '';
            $cIdBy = $cId ? "s.cId={$cId}" : '';
            $sId = $_POST['sId'] ? $_POST['sId'] : '';
            $sIdBy = $sId ? "s.sId={$sId}" : '';
            $firstTime = $_POST['firstTime'] ? $_POST['firstTime'] : ''; // 获取更新的记录
            $firstTimeBy = $firstTime ? "s.sPubtime>$firstTime" : '';
            
            $lastTime = $_POST['lastTime'] ? $_POST['lastTime'] : ''; // 获取历史的记录
            $lastTimeBy = $lastTime ? "s.sPubtime<$lastTime" : '';
            $order = $_POST['order'] ? $_POST['order'] : 'sPubtime';
            if ($firstTimeBy) { // 获取最新消息则时间倒序分页传
                $type = $_POST['type'] ? $_POST['type'] : 'asc';
            } else { // 加载历史记录则时间正序分页传
                $type = $_POST['type'] ? $_POST['type'] : 'desc';
            }
            $type = $type ? $type : 'desc';
            // $orderBy=$firstTime?"order by s.{$order} {$type}":"order by s.{$order} {$type}";
            $orderBy = "order by s.{$order} {$type}";
            $keywords = $_POST['keywords'] ? $_POST['keywords'] : '';
            $keywordsBy = $keywords ? "(s.`sTitle` like '%{$keywords}%' OR s.`sPosition` like '%{$keywords}%' OR u.`username` like '%{$keywords}%')" : null;
            $where = '';
            if ($firstTimeBy || $lastTimeBy || $keywordsBy || $sIdBy || $cIdBy || $arangBy) { // where不为空
                $where = "where ";
            }
            if ($firstTimeBy) {
                $where1[] = $firstTimeBy;
            }
            if ($lastTimeBy) {
                $where1[] = $lastTimeBy;
            }
            if ($keywordsBy) {
                $where1[] = $keywordsBy;
            }
            if ($sIdBy) {
                $where1[] = $sIdBy;
            }
            if ($cIdBy) {
                $where1[] = $cIdBy;
            }
            if ($arangBy) {
                $where1[] = $arangBy;
            }
            $i = 0;
            if ($where1) {
                foreach ($where1 as $whe) {
                    if ($i == 0) {
                        $where .= $whe;
                        $i ++;
                    } else {
                        $where .= " and {$whe}";
                    }
                }
            }
            $site = OUR_SITE;
            $path = "{$site}/uploads/";
            $rows = Situation::getSituation($pageSize, $where, $orderBy);
            if ($rows === false) {
                $result['status'] = 3;
                $result['message'] = '获取数据失败';
            } elseif ($rows === null) {
                $result['status'] = 2;
                $result['message'] = '没有更多数据';
            } else {
                $i = 0;
                foreach ($rows as $row) {
                    foreach ($row as $key => $value) {
                        $result1[$i][$key] = urlencode($value);
                    }
                    $result1[$i]['collectTime'] = urlencode($collectTime[$i]);
                    $result1[$i]['face'] = urlencode($path . $row['face']);
                    $sId = $row['id'];
                    Situation::isOption($result1, $i, $sId);//引用去添加是否赞过等
                    $j = 0;
                    $images = Album::getSituationImageBysId($sId);
                    if ($images === false) {
                        // $result['status'] = 4;
                        // $result['message'] = '获取活动图片失败';
                        $result1[$i]['sImage'] = '';
                    } elseif ($images === null) {
                        // $result['status'] = 5;
                        // $result['message'] = '该活动没有上传图片';
                        $result1[$i]['sImage'] = '';
                    } else {
                        foreach ($images as $image) {
                            
                            $result1[$i]['sImage'][$j . 'p'] = urlencode($path . $image['albumPath']);
                            $j ++;
                        }
                    }
                    $i ++;
                }
                $result['datas'] = $result1;
                $result['status'] = 1;
                $result['message'] = "成功获取{$i}条数据";
            }
        } else {
            $result['status'] = 6;
            $result['message'] = '该用户还没有收藏活动';
        }
        echo Response::json($result);
    }

    /**
     * 加关注
     */
    public static function attention()
    {
        global $result; // 更新userLog日志表要用
        global $db_obj;
        $arr['follower_id'] = $_SESSION['uId'] ? $_SESSION['uId'] : ''; // 如果用户已经登录，则从session中获取用户的id
        if ($arr['follower_id'] !== '') {
            
            $arr['user_id'] = $_POST['user_id'] ? $_POST['user_id'] : '';
            $sql = "select id from hw_user where id={$arr['user_id']}"; // 要关注的用户是否存在
            if ($db_obj->fetchOne($sql)) {
                $arr['time'] = time();
                $sql = "select id from hw_follow where follower_id={$arr['follower_id']} and user_id={$arr['user_id']}"; // 是否已经关注了该用户
                if (! $db_obj->fetchOne($sql)) {
                    $bool = User::Attention($arr);
                    if ($bool) {
                        $result['status'] = 1;
                        $result['message'] = '关注成功';
                    } else {
                        $result['status'] = 2;
                        $result['message'] = '关注失败';
                    }
                } else {
                    $result['status'] = 3;
                    $result['message'] = '您已经关注了该用户';
                }
            } else {
                $result['status'] = 4;
                $result['message'] = '您要关注的用户不存在';
            }
        } else {
            $result['status'] = 5;
            $result['message'] = '请先登录';
        }
        echo Response::json($result);
    }

    /**
     * 取消关注
     */
    public static function cancelAttention()
    {
        global $result; // 更新userLog日志表要用
        global $db_obj;
        $arr['follower_id'] = $_SESSION['uId'] ? $_SESSION['uId'] : ''; // 如果用户已经登录，则从session中获取用户的id
        if ($arr['follower_id'] !== '') {
            $arr['user_id'] = $_POST['user_id'] ? $_POST['user_id'] : '';
            $sql = "select id from hw_user where id={$arr['user_id']}"; // 要关注的用户是否存在
            if ($db_obj->fetchOne($sql)) {
                $sql = "select id from hw_follow where follower_id={$arr['follower_id']} and user_id={$arr['user_id']}"; // 是否已经关注了该用户
                if ($db_obj->fetchOne($sql)) {
                    $bool = User::cancelAttention($arr);
                    if ($bool) {
                        $result['status'] = 1;
                        $result['message'] = '取消关注成功';
                    } else {
                        $result['status'] = 2;
                        $result['message'] = '取消关注失败';
                    }
                } else {
                    $result['status'] = 3;
                    $result['message'] = '您还没有关注该用户';
                }
            } else {
                $result['status'] = 4;
                $result['message'] = '您要取消关注的用户不存在';
            }
        } else {
            $result['status'] = 5;
            $result['message'] = '请先登录';
        }
        echo Response::json($result);
    }

    /**
     * 获取粉丝列表//不能获取全部数量，客户端要传最后的时间或者id过来，获取下一页，之后该
     */
    public static function getUserFollower()
    {
        global $result; // 更新userLog日志表要用
        global $db_obj;
        $user_id = $_SESSION['uId'] ? $_SESSION['uId'] : ''; // 如果用户已经登录，则从session中获取用户的id
        if ($_POST['uId']) {
            $user_id = $_POST['uId'] ? $_POST['uId'] : '';
        }
        if ($user_id !== '') {
            $rows = User::getUserFollower($user_id);
            if ($rows === false) {
                $result['status'] = 3;
                $result['message'] = '获取粉丝列表失败';
            } elseif ($rows === null) {
                $result['status'] = 2;
                $result['message'] = '您还没有粉丝，快去拉粉=-=';
            } else {
                
                foreach ($rows as $row) {
                    $follower_id[] = $row['follower_id'];
                }
                $follower_id = join(',', array_values($follower_id)); // 获取粉丝id集合 如：1,2,3是一个字符串
                $rows = User::getUserInfoByUids($follower_id);
                if ($rows) {
                    $result['status'] = 1;
                    $result['message'] = '获取粉丝列表成功';
                    $i = 0;
                    foreach ($rows as $row) {
                        foreach ($row as $k => $v) {
                            $result1[$i][$k] = urlencode($v);
                        }
                        $site = OUR_SITE;
                        $path = "{$site}/uploads/";
                        $result1[$i]['face'] = urlencode($path . $result1[$i]['face']);
                        $i ++;
                    }
                    $result['datas'] = $result1;
                } else {
                    $result['status'] = 3;
                    $result['message'] = '获取粉丝列表失败';
                }
            }
        } else {
            $result['status'] = 4;
            $result['message'] = '请先登录';
        }
        echo Response::json($result);
    }

    /**
     * 获取用户关注列表
     */
    public static function getUserAttention()
    {
        global $result; // 更新userLog日志表要用
        global $db_obj;
        $follower_id = $_SESSION['uId'] ? $_SESSION['uId'] : ''; // 如果用户已经登录，则从session中获取用户的id
        if ($_POST['uId']) {
            $follower_id = $_POST['uId'] ? $_POST['uId'] : '';
        }
        if ($follower_id !== '') {
            $rows = User::getUserAttention($follower_id);
            if ($rows === false) {
                $result['status'] = 3;
                $result['message'] = '获取用户关注列表失败';
            } elseif ($rows === null) {
                $result['status'] = 2;
                $result['message'] = '您还没有关注的用户，快去加关注吧=-=';
            } else {
                
                foreach ($rows as $row) {
                    $user_id[] = $row['user_id'];
                }
                $user_id = join(',', array_values($user_id)); // 获取粉丝id集合 如：1,2,3是一个字符串
                $rows = User::getUserInfoByUids($user_id);
                if ($rows) {
                    $result['status'] = 1;
                    $result['message'] = '获取用户关注列表成功';
                    $i = 0;
                    foreach ($rows as $row) {
                        foreach ($row as $k => $v) {
                            $result1[$i][$k] = urlencode($v);
                        }
                        $site = OUR_SITE;
                        $path = "{$site}/uploads/";
                        $result1[$i]['face'] = urlencode($path . $result1[$i]['face']);
                        $i ++;
                    }
                    $result['datas'] = $result1;
                } elseif ($rows === null) {
                    $result['status'] = 2;
                    $result['message'] = '您还没有关注的用户，快去加关注吧=-=';
                } elseif ($rows === false) {
                    $result['status'] = 3;
                    $result['message'] = '获取粉丝列表失败';
                }
            }
        } else {
            $result['status'] = 4;
            $result['message'] = '请先登录';
        }
        echo Response::json($result);
    }

    /**
     * 获取活动用户组
     */
    public static function getSituationGroup()
    {
        global $result; // 更新userLog日志表要用
        $sId = $_POST['sId'] ? $_POST['sId'] : '';
        if ($sId) {
            $rows = User::getSituationGroupBySid($sId);
            if ($rows === false) {
                $result['status'] = 3;
                $result['message'] = '获取活动用户组失败';
            } elseif ($rows === null) {
                $result['status'] = 2;
                $result['message'] = '该活动还没用户组';
            } else {
                foreach ($rows as $row) {
                    $uId[] = $row['uId'];
                }
                $uId = join(',', array_values($uId)); // 获取粉丝id集合 如：1,2,3是一个字符串
                $rows = User::getUserInfoByUids($uId);
                if ($rows) {
                    $result['status'] = 1;
                    $result['message'] = '获取活动用户组成功';
                    $i = 0;
                    foreach ($rows as $row) {
                        foreach ($row as $k => $v) {
                            $result1[$i][$k] = urlencode($v);
                        }
                        $site = OUR_SITE;
                        $path = "{$site}/uploads/";
                        $result1[$i]['face'] = urlencode($path . $result1[$i]['face']);
                        $i ++;
                    }
                    $result['datas'] = $result1;
                } else {
                    $result['status'] = 3;
                    $result['message'] = '获取活动用户组失败';
                }
            }
        } else {
            $result['status'] = 4;
            $result['message'] = '活动id错误';
        }
        echo Response::json($result);
    }

    /**
     * 获取单个活动的所有图片
     */
    public static function getSituationImage()
    {
        global $result; // 更新userLog日志表要用
        $sId = $_POST['sId'] ? $_POST['sId'] : null;
        $rows = Album::getSituationImageBysId($sId);
        $site = OUR_SITE;
        $path = "{$site}/uploads/";
        $i = 0;
        if ($rows === false) {
            $result['status'] = 3;
            $result['message'] = '获取活动图片失败';
        } elseif ($rows === null) {
            $result['status'] = 2;
            $result['message'] = '该活动没有上传图片';
        } else {
            foreach ($rows as $row) {
                foreach ($row as $key => $value) {
                    $result1[$i][$key] = urlencode($value);
                }
                $result1[$i]['albumPath'] = urlencode($path . $value);
                $i ++;
            }
            $result['status'] = 1;
            $result['message'] = '获取活动图片成功';
            $result['datas'] = $result1;
        }
        echo Response::json($result);
    }

    /**
     * 得到省份列表
     *
     * @return string
     */
    public static function getListProvince()
    {
        global $result; // 更新userLog日志表要用
        $rows = Province::getAllProvince();
        $i = 0;
        if ($rows) {
            foreach ($rows as $row) {
                
                foreach ($row as $key => $value) {
                    $result1[$i][$key] = urlencode($value);
                }
                $i ++;
            }
            $result['status'] = 1;
            $result['message'] = '获取省份列表成功';
            $result['datas'] = $result1;
        } else {
            $result['status'] = 2;
            $result['message'] = '获取省份列表失败';
        }
        echo Response::json($result);
    }

    /**
     * 得到校区列表
     *
     * @return string
     */
    public static function getListSchool()
    {
        global $result; // 更新userLog日志表要用
        $rows = School::getAllSchool();
        $i = 0;
        if ($rows) {
            foreach ($rows as $row) {
                foreach ($row as $key => $value) {
                    $result1[$i][$key] = urlencode($value);
                }
                $i ++;
            }
            $result['status'] = 1;
            $result['message'] = '获取学校列表成功';
            $result['datas'] = $result1;
        } else {
            $result['status'] = 2;
            $result['message'] = '获取学校列表失败';
        }
        echo Response::json($result);
    }

    /**
     * 通过省份id得到该省份的高校列表
     *
     * @return string
     */
    public static function getSchoolByPid()
    {
        global $result; // 更新userLog日志表要用
        $pId = $_POST['pId'] ? $_POST['pId'] : null;
        $rows = School::getSchoolByProvinceId($pId);
        $i = 0;
        if ($rows === false) {
            $result['status'] = 3;
            $result['message'] = '获取省份的学校列表失败';
        } elseif ($rows === null) {
            $result['status'] = 2;
            $result['message'] = '数据库还未添加该省份的学校';
        } else {
            foreach ($rows as $row) {
                
                foreach ($row as $key => $value) {
                    $result1[$i][$key] = urlencode($value);
                }
                $i ++;
            }
            $result['status'] = 1;
            $result['message'] = '获取该省份学校列表成功';
            $result['datas'] = $result1;
        }
        echo Response::json($result);
    }

    /**
     * 得到校区列表
     *
     * @return string
     */
    public static function getCampusBySid()
    {
        global $result; // 更新userLog日志表要用
        $sId = $_POST['sId'] ? $_POST['sId'] : null;
        $rows = Campus::getCampusSchoolId($sId);
        if ($rows === false) {
            $result['status'] = 3;
            $result['message'] = '获取学校的校区列表失败';
        } elseif ($rows === null) {
            $result['status'] = 2;
            $result['message'] = '数据库中该学校还没有添加校区';
        } else {
            $i = 0;
            foreach ($rows as $row) {
                
                foreach ($row as $key => $value) {
                    $result1[$i][$key] = urlencode($value);
                }
                $i ++;
            }
            $result['status'] = 1;
            $result['message'] = '获取校区列表成功';
            $result['datas'] = $result1;
        }
        echo Response::json($result);
    }

    /**
     * 获取校区的场所列表
     */
    public static function getPlaceByCid()
    {
        global $result; // 更新userLog日志表要用
        $cId = $_POST['cId'] ? $_POST['cId'] : null;
        $rows = Place::getPlaceByCampusId($cId);
        if ($rows === false) {
            $result['status'] = 3;
            $result['message'] = '获取校区的场所列表失败';
        } elseif ($rows === null) {
            $result['status'] = 2;
            $result['message'] = '数据库中该校区还没有添加场所';
        } else {
            $i = 0;
            foreach ($rows as $row) {
                
                foreach ($row as $key => $value) {
                    $result1[$i][$key] = urlencode($value);
                }
                $i ++;
            }
            $result['status'] = 1;
            $result['message'] = '获取该校区场所列表成功';
            $result['datas'] = $result1;
        }
        echo Response::json($result);
    }

    /**
     * 获取校区提供的的快捷活动标题列表
     */
    public static function getActiveByCid()
    {
        global $result; // 更新userLog日志表要用
        $cId = $_POST['cId'];
        $rows = Active::getAcitveByCampusId($cId);
        if ($rows === false) {
            $result['status'] = 3;
            $result['message'] = '获取校区快捷活动标题列表失败';
        } elseif ($rows === null) {
            $result['status'] = 2;
            $result['message'] = '该校区还没有提供快捷活动列表';
        } else {
            $i = 0;
            foreach ($rows as $row) {
                
                foreach ($row as $key => $value) {
                    $result1[$i][$key] = urlencode($value);
                }
                $i ++;
            }
            $result['status'] = 1;
            $result['message'] = '获取校区快捷活动标题列表成功';
            $result['datas'] = $result1;
        }
        echo Response::json($result);
    }

    /**
     * 获取场所提供的的快捷活动标题列表
     */
    public static function getActiveByPid()
    {
        global $result; // 更新userLog日志表要用
        $pId = $_POST['pId'];
        $rows = Active::getAcitveByPlaceId($pId);
        if ($rows === false) {
            $result['status'] = 3;
            $result['message'] = '获取场所快捷活动标题列表失败';
        } elseif ($rows === null) {
            $result['status'] = 2;
            $result['message'] = '该场所还没有提供快捷活动列表';
        } else {
            $i = 0;
            foreach ($rows as $row) {
                
                foreach ($row as $key => $value) {
                    $result1[$i][$key] = urlencode($value);
                }
                $i ++;
            }
            $result['status'] = 1;
            $result['message'] = '获取场所快捷活动标题列表成功';
            $result['datas'] = $result1;
        }
        echo Response::json($result);
    }

    /**
     * 注册时的邮箱激活码验证
     *
     * @return boolean
     */
    public static function emailVerifyForRegister()
    {
        global $result; // 更新userLog日志表要用
        global $db_obj;
        $emailCode = stripslashes(trim($_GET['emailCode'])) ? stripslashes(trim($_GET['emailCode'])) : '';
        $email = stripslashes(trim($_GET['email'])) ? stripslashes(trim($_GET['email'])) : '';
        $nowtime = time();
        
        // print_r($_SESSION)
        if ($nowtime > $_SESSION['emailCode_exptime'] || $_SESSION['emailCode'] == '') {
            // 激活码过期
            $result['status'] = 5;
            $result['message'] = '激活码过期';
        } elseif ($emailCode != $_SESSION['emailCode'] || $_SESSION['emailCode'] == '') {
            
            $result['status'] = 4; // 激活码错误
            $result['message'] = '激活码失效';
        } else {
            $sql = "select id from hw_user where activeFlag='0' and `email`='$email'";
            $row = $db_obj->fetchOne($sql);
            if ($row) {
                $sql = "update hw_user set activeFlag=0 where id=" . $row['id'];
                $table = 'hw_user';
                $arr['activeFlag'] = 1;
                $where = "id={$row['id']}";
                $link = $db_obj->update($table, $arr, $where);
                if (! $link) {
                    $result['status'] = 2;
                    $result['message'] = '更新用户激活状态失败,请重新激活';
                } else {
                    $result['status'] = 1;
                    $result['message'] = '更新用户激活状态成功,请登陆';
                    $_SESSION = array();
                }
            } else {
                $result['status'] = 3;
                $result['message'] = '该邮箱错误';
            }
        }
        echo Response::json($result);
    }

    /**
     * 更改密码邮箱激活码验证
     *
     * @return boolean
     */
    public static function emailVerifyForReset()
    {
<<<<<<< HEAD
<<<<<<< HEAD
        global $result; //  1更新userLog日志表要用
=======
        global $result; //  200更新userLog日志表要用
>>>>>>> origin/master
=======
        global $result; //  更新userLog日志表要用
>>>>>>> origin/master
        $emailCode = stripslashes(trim($_GET['emailCode'])) ? stripslashes(trim($_GET['emailCode'])) : '';
        $email = $_SESSION['email'];
        $nowtime = time();
        
        // print_r($_SESSION)
        if ($nowtime > $_SESSION['emailCode_exptime'] || $_SESSION['emailCode'] == '') {
            // 激活码过期
            $result['status'] = 3;
            $result['message'] = '激活码过期';
        } elseif ($emailCode != $_SESSION['emailCode'] || $_SESSION['emailCode'] == '') {
            
            $result['status'] = 2; // 激活码错误
            $result['message'] = '激活码失效';
        } else {
            $result['status'] = 1; // 激活码错误
            $result['message'] = '成功跳转到修改密码页面';
            $sessionId = session_id();
            // $result['datas'] = $arr;
            // echo Response::json($result);
            // $result = Response::json($result);
        }
        $sessionId = $sessionId ? $sessionId : '';
        $url = "../resetPassword.html?status={$result['status']}&message={$result['message']}&email={$email}&emailCode={$emailCode}&sessionId={$sessionId}";
        echo "<script>window.location='{$url}'</script>";
    }

    /**
     * 用户登陆
     *
     * @return number
     */
    public static function userLogin()
    {
        global $result; // 更新userLog日志表要用
        $arr['password'] = $_POST['password'] ? md5($_POST['password']) : '';
        $loginId = $_POST['loginId'] ? $_POST['loginId'] : '';
        $autoFlag = $_POST['autoFlag'] ? $_POST['autoFlag'] : null;
        // 判断是否已经登录过，通过session
        if ($arr['password'] == '' || $loginId == '') {
            $result['status'] = 10;
            $result['message'] = '请将登录信息填写完';
        } elseif (Str::isPhone($loginId)) { // 邮箱登录
            $arr['phone'] = $loginId;
            $checkPhone = Phone::checkPhoneExist($arr['phone']);
            if ($checkPhone == 1) { // 存在该用户
                                    // 用户手机登陆
                $row = User::phoneLogin($arr['phone']);
                // 1判断是否已经登录，更新已经登录的用户表，若已经登录则不用重新登录，2判断是否是当天的第一次登录(更新用户表中的经验值)，更新用户表中的最后登陆时间
                $row1 = User::checkLoginedByuId($row['id']);
                if ($_SESSION['uId'] != $row['id']) { // 如果session过期，则删除登录表中的记录
                    $sql = "select id from hw_userlogined where uId={$row['id']}";
                    global $db_obj;
                    $row1 = $db_obj->fetchOne($sql);
                    if ($row1) {
                        $where = "id={$row1['id']}";
                        $table = 'hw_userlogined';
                        $db_obj->delete($table, $where);
                    }
                }
                $row1 = User::checkLoginedByuId($row['id']);
                if (! $row1) { // 还没有登录
                    if ($row) {
                        if ($row['activeFlag'] == 1) { // 手机注册的时候已经激活
                            if ($arr['password'] == $row['password']) {
                                $flag = User::checkIsFirstLogin($row['lastLoginTime']); // 检查是否是当天的第一次登录
                                $bool = User::updateUserLogined($row['id'], $flag);
                                if ($bool) { // 登录成功
                                    if ($autoFlag) { // 设置自动登录
                                        setcookie("uId", $row['id'], time() + 7 * 24 * 60 * 60);
                                        // setcookie("loginId", $row['phone'], time() + 7 * 24 * 60 * 60);
                                        setcookie("username", $row['username'], time() + 7 * 24 * 60 * 60);
                                    }
                                    // $_SESSION['loginId'] = $row['phone'];
                                    $_SESSION['username'] = $row['username'];
                                    $_SESSION['uId'] = $row['id']; // 用户id
                                    
                                    $result['status'] = 1;
                                    $result['sessionId'] = session_id();
                                    $_SESSION['sessionId'] = session_id();
                                    
                                    $result['message'] = '登录成功';
                                    // $result['datas'] = $row;看看是否登录成功的要返回个人信息
                                } else { // 登录失败
                                    $result['status'] = 2;
                                    $result['message'] = '登录失败';
                                }
                            } else {
                                $result['status'] = 3;
                                $result['message'] = '密码错误';
                            }
                        }
                    }
                } else {
                    $result['status'] = 7;
                    $result['message'] = '已经登录过';
                }
            } elseif ($checkPhone == 2) {
                $result['status'] = 8;
                $result['message'] = '该用户不存在';
            }
        } elseif (Str::isEmail($loginId)) { // 邮箱登录
            $arr['email'] = $loginId;
            $checkEmail = Email::checkEmailExist($arr['email']);
            if ($checkEmail == 1) { // 存在该用户
                $row = User::emailLogin($arr['email']);
                // 1判断是否已经登录，更新已经登录的用户表，若已经登录则不用重新登录，2判断是否是当天的第一次登录(更新用户表中的经验值)，更新用户表中的最后登陆时间
                $row1 = User::checkLoginedByuId($row['id']);
                if ($_SESSION['uId'] != $row['id']) { // 如果session过期，则删除登录表中的记录
                    $sql = "select id from hw_userlogined where uId={$row['id']}";
                    global $db_obj;
                    $row1 = $db_obj->fetchOne($sql);
                    if ($row1) {
                        $where = "id={$row1['id']}";
                        $table = 'hw_userlogined';
                        $db_obj->delete($table, $where);
                    }
                }
                $row1 = User::checkLoginedByuId($row['id']);
                if (! $row1) { // 还没有登录
                    if ($row['activeFlag'] == 1) { // 已经激活，否则请重新发送激活码
                        if ($arr['password'] == $row['password']) {
                            $flag = User::checkIsFirstLogin($row['lastLoginTime']); // 检查是否是当天的第一次登录
                            $bool = User::updateUserLogined($row['id'], $flag);
                            if ($bool) {
                                if ($autoFlag) { // 设置自动登录
                                    setcookie("uId", $row['id'], time() + 7 * 24 * 60 * 60);
                                    // setcookie("loginId", $row['email'], time() + 7 * 24 * 60 * 60);
                                    setcookie("username", $row['username'], time() + 7 * 24 * 60 * 60);
                                }
                                // $_SESSION['loginId'] = $row['email']; // 登录的邮箱或者手机号
                                $_SESSION['username'] = $row['username'];
                                $_SESSION['uId'] = $row['id']; // 用户id
                                $result['status'] = 1;
                                $result['sessionId'] = session_id();
                                $_SESSION['sessionId'] = session_id();
                                $result['message'] = '登录成功';
                                // print_r($_SESSION);
                            } else {
                                $result['status'] = 2;
                                $result['message'] = '登录失败';
                            }
                        } else {
                            $result['status'] = 3;
                            $result['message'] = '密码错误';
                        }
                    } else { // 重新激活
                        $nowtime = time();
                        if ($nowtime > $_SESSION['emailCode_exptime']) {
                            $_SESSION['emailCode'] = md5($arr['username'] . $arr['password'] . time()); // 创建用于激活识别码
                            $_SESSION['emailCode_exptime'] = time() + 60 * 60 * 24; // 过期时间为24小时后
                            $bool = Email::sendEmai($row['username'], $row['email'], $_SESSION['emailCode']);
                            if ($bool) {
                                $result['status'] = 4;
                                $result['message'] = '邮箱还未激活，重新发送激活邮箱成功，请前往邮箱激活';
                            } else {
                                $result['status'] = 5;
                                $result['message'] = '重新发送激活邮箱失败';
                            }
                        }
                        $result['status'] = 6;
                        $result['message'] = '您的邮箱还没激活，已经发送激活邮箱，请前去您的邮箱激活';
                    }
                } else {
                    $result['status'] = 7;
                    $result['message'] = '已经登录过';
                }
            } elseif ($checkEmail == 2) {
                $result['status'] = 8;
                $result['message'] = '该用户不存在';
            }
        } else {
            $result['status'] = 9;
            $result['message'] = '格式错误，请输入正确的手机或邮箱';
        }
        echo Response::json($result);
    }

    /**
     * 用户退出登录
     */
    public static function userloginOut()
    {
        global $result; // 更新userLog日志表要用
                        // print_r($_SESSION);
        if ($_SESSION['sessionId'] == session_id()) { // 退出当前sessionId
            
            $bool = User::loginOut();
            if ($bool) {
                $result['status'] = 1;
                $result['message'] = '成功退出登录';
            } else {
                $result['status'] = 2;
                $result['message'] = '退出登录失败';
            }
        } else {
            $result['status'] = 3;
            $result['message'] = '还未登录';
        }
        session_destroy(); // 这里关闭session会导致后台也一起跟着退出
        echo Response::json($result);
    }

    /**
     * 获取用的的基本信息，可读不可写
     */
    public static function getUserInfo()
    {
        global $result; // 更新userLog日志表要用
                        // 别的用户也要浏览该用户的个人信息,因此不用登陆的才能看，可读但是不可写
        $uId = $_SESSION['uId'] ? $_SESSION['uId'] : ''; // 如果用户已经登录，则从session中获取用户的id
        if ($_POST['uId']) {
            $uId = $_POST['uId'] ? $_POST['uId'] : '';
        }
        if ($uId) {
            $row = User::getUserInfoByUid($uId);
            if ($row) {
                unset($row['password']);
                $result['status'] = 1;
                $result['message'] = '获取用户信息成功';
                foreach ($row as $k => $v) {
                    $result1[$k] = urlencode($v);
                }
                $site = OUR_SITE;
                $path = "{$site}/uploads/";
                $result1['face'] = urlencode($path . $result1['face']);
                if ($_SESSION['uId'] != $uId) { // 如果的要获取的不是已经登录的用户信息，则销毁邮箱和手机号
                    unset($result1['email']); // 或者赋空值,结合前台考虑
                    unset($result1['phone']);
                }
                $exps = User::getAboutExperience($result1['experience']); // 转换经验值为等级，得到一个数组
                $result1['level'] = $exps['level'];
                $result1['remainXP'] = $exps['remain'];
                $result1['lackXP'] = $exps['lack'];
                $result1['levelXP'] = $exps['remain'] + $exps['lack'];
                if ($_SESSION['uId'] != '') { // 如果登录了,则显示是否关注过
                    $bool = User::checkAttention($_SESSION['uId'], $uId);
                    if ($bool) {
                        $result1['isAttention'] = 1; // 关注过
                    } else {
                        $result1['isAttention'] = 0; // 没有关注过
                    }
                } else { // 否则，默认为没有赞过
                    $result1['isAttention'] = 0; // 没有关注过
                }
                $result['datas'] = $result1;
            } else {
                $result['status'] = 2;
                $result['message'] = '获取用户信息失败';
            }
        } else {
            $result['status'] = 3;
            $result['message'] = '用户id错误';
        }
        echo Response::json($result);
    }

    /**
     * 修改用户个人基本信息
     */
    public static function modifyUserInfo()
    {
        global $result; // 更新userLog日志表要用
        global $db_obj;
        $uId = $_SESSION['uId'] ? $_SESSION['uId'] : ''; // 如果用户已经登录，则从session中获取用户的id
        if ($uId != '') {
            $arr['username'] = $_POST['username'];
            $checkUsername = User::checkUsernameExist($arr['username']);
            if ($checkUsername === 3) { // 该用户名已经存在，并且不是当前用户名
                $result['status'] = 7;
                $result['message'] = '该用户名不合法';
            } elseif ($checkUsername === 1 && $arr['username'] != $_SESSION['username']) { // 该用户名不合法 3-20位中文，英文，下划线，数字
                $result['status'] = 6;
                $result['message'] = '该用户名已经存在';
            } else { // 该用户名可以使用
                $arr['pId'] = $_POST['pId'];
                $arr['sId'] = $_POST['sId'];
                $arr['cId'] = $_POST['cId'];
                $sql = "select id from hw_campus where id={$arr['cId']} and sId={$arr['sId']}";
                $row = $db_obj->fetchOne($sql);
                $sql = "select id from hw_school where id={$arr['sId']} and sId={$arr['pId']}";
                $row1 = $db_obj->fetchOne($sql);
                if ($row && $row1) {
                    $arr['sex'] = $_POST['sex'];
                    $bool = User::checkUserSex($arr['sex']);
                    if ($bool) {
                        $arr['about'] = $_POST['about'] ? $_POST['about'] : '暂无介绍';
                        if (Str::validate_str($arr['about'], 0, 255)) {
                            $uploadFiles = Upload::uploadFile("../uploads");
                            if ($uploadFiles && is_array($uploadFiles)) { // 上传多张只取第一张
                                $arr['face'] = $uploadFiles[0]['name'];
                                $where = "id={$uId}";
                                $bool = User::modifyUserInfo($arr, $where);
                                if ($bool) {
                                    $result['status'] = 1;
                                    $result['message'] = '修改用户信息成功';
                                    $_SESSION['username'] = $arr['username']; // 更改session中的username
                                } else {
                                    $result['status'] = 2;
                                    $result['message'] = '修改用户信息失败';
                                    // 删除上传的图片
                                    Image::deletePicture($uploadFiles);
                                }
                            } elseif ($uploadFiles === false) {
                                $result['status'] = 3;
                                $result['message'] = '上传文件错误';
                            }
                        } else {
                            $result['status'] = 9;
                            $result['message'] = '简介内容不合法';
                        }
                    } else {
                        $result['status'] = 4;
                        $result['message'] = '性别不合法';
                    }
                } else {
                    $result['status'] = 5;
                    $result['message'] = '该校区,学校,省份不匹配';
                }
            }
        } else {
            $result['status'] = 8;
            $result['message'] = '还未登录，请先登录';
        }
        echo Response::json($result);
    }

    /**
     * 修改密码
     */
    public static function modifyPassword()
    {
        global $result; // 更新userLog日志表要用
        global $db_obj;
        $uId = $_SESSION['uId'] ? $_SESSION['uId'] : ''; // 如果用户已经登录，则从session中获取用户的id
        if ($uId != '') {
            $password = $_POST['password'] ? $_POST['password'] : '';
            if (Str::isPassword($password)) {
                $bool = User::modifyPassword($uId, $password);
                if ($bool) {
                    $result['status'] = 1;
                    $result['message'] = '修改密码成功';
                } else {
                    $result['status'] = 2;
                    $result['message'] = '修改密码失败,请检查是否与旧密码相同';
                }
            } else {
                $result['status'] = 3;
                $result['message'] = '密码不合法，请输入6—20位由字母、数字组成的密码';
            }
        } else {
            $result['status'] = 4;
            $result['message'] = '还未登录，请先登录';
        }
        echo Response::json($result);
    }

    /**
     * 绑定手机号
     */
    public static function bindPhone()
    {
        global $result; // 更新userLog日志表要用
        global $db_obj;
        $uId = $_SESSION['uId'] ? $_SESSION['uId'] : ''; // 如果用户已经登录，则从session中获取用户的id
        if ($uId != '') {
            $phone = $_POST['phone'] ? $_POST['phone'] : ''; // 为NULL的时候才是数据库的NULL,为''空字符串不是
            $messageCode = $_POST['messageCode'] ? $_POST['messageCode'] : '';
            $checkPhone = Phone::checkPhoneExist($phone);
            
            if ($phone == '' || $messageCode == '') {
                $result['message'] = '请将信息填写完成';
                $result['status'] = 8; // 填写不全
            } elseif ($checkPhone == 2) { // 返回2表示手机号码格式正确且还未被注册
                $nowtime = time();
                if ($_SESSION['messageCode'] == '') {
                    $result['status'] = 5; // 您还没有获取验证码，请获取手机验证码
                    $result['message'] = '您还没有获取验证码，请获取手机验证码';
                } elseif ($nowtime > $_SESSION['messageCode_exptime']) { // 验证码过期，请重新发送
                    $result['status'] = 4;
                    $result['message'] = '手机验证码过期,请重新获取手机验证码'; // 手机验证码过期
                } elseif ($messageCode != $_SESSION['messageCode']) {
                    $result['status'] = 3; // 手机验证码错误
                    $result['message'] = '手机验证码错误';
                } else {
                    $bool = User::bindPhone($uId, $phone);
                    if ($bool) {
                        $result['status'] = 1;
                        $result['message'] = '绑定手机号码成功';
                        // $_SESSION['loginId'] = $phone;
                    } else {
                        $result['status'] = 2;
                        $result['message'] = '绑定手机号码失败';
                    }
                }
            } elseif ($checkPhone == 3) {
                $result['status'] = 7;
                $result['message'] = '手机号码格式错误';
            } elseif ($checkPhone == 1) {
                $result['status'] = 6;
                $result['message'] = '该手机号码已经被注册';
            }
        } else {
            $result['status'] = 9;
            $result['message'] = '还未登录，请先登录';
        }
        echo Response::json($result);
    }

    /**
     * 绑定邮箱
     */
    public static function bindEmail()
    {
        global $result; // 更新userLog日志表要用
        global $db_obj;
        $uId = $_SESSION['uId'] ? $_SESSION['uId'] : ''; // 如果用户已经登录，则从session中获取用户的id
        if ($uId != '') {
            $username = $_SESSION['username'];
            $email = $_POST['email'] ? $_POST['email'] : '';
            $checkEmail = Email::checkEmailExist($email);
            if ($checkEmail == 2) { // 返回2表示邮箱格式正确且还未被注册
                $_SESSION['emailCode'] = md5($uId . $email . time()); // 创建用于激活识别码
                $_SESSION['emailCode_exptime'] = time() + 60 * 60 * 24; // 过期时间为24小时后
                $site = OUR_SITE;
                $body = "亲爱的" . $username . "：<br/>您正在汇玩空间网执行绑定邮箱的操作。<br/>请点击链接激活您的绑定邮箱。<br/><a href='{$site}/api/webServer.php?action=emailVerifyForBindEmail&email=" . $email . "&emailCode=" . $_SESSION['emailCode'] . "&sessionId=" . session_id() . "' target='_blank'>{$site}/api/webServer.php?action=emailVerifyForBindEmail&email=" . $email . "&emailCode=" . $_SESSION['emailCode'] . "&sessionId=" . session_id() . "</a><br/>如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接24小时内有效。<br/>如果此次激活请求非你本人所发，请忽略本邮件。<br/><p style='text-align:right'>--------  <a href='http://www.huiwanspace.com'>www.huiwanspace.com<br/>汇玩空间网</p>";
                $bool = Email::sendEmai($username, $email, $body);
                if ($bool) {
                    $result['status'] = 1;
                    $result['message'] = '注册成功,邮件已经发送,请前往邮箱进行激活';
                } else {
                    $result['status'] = 2;
                    $result['message'] = '发送邮件失败,请检查邮箱是否有误';
                    $_SESSION = array();
                }
            } elseif ($checkEmail == 3) {
                $result['status'] = 3;
                $result['message'] = '邮箱格式错误';
            } elseif ($checkEmail == 1) {
                $result['status'] = 4;
                $result['message'] = '该邮箱已经被注册';
            }
        } else {
            $result['status'] = 5;
            $result['message'] = '还未登录，请先登录';
        }
        echo Response::json($result);
    }

    /**
     * 验证绑定邮箱，执行更新用户表中的邮箱字段
     */
    public static function emailVerifyForBindEmail()
    {
        global $result; // 更新userLog日志表要用
        global $db_obj;
        $emailCode = stripslashes(trim($_GET['emailCode'])) ? stripslashes(trim($_GET['emailCode'])) : '';
        $email = stripslashes(trim($_GET['email'])) ? stripslashes(trim($_GET['email'])) : '';
        $uId = $_SESSION['uId'];
        $nowtime = time();
        // print_r($_SESSION)
        if ($nowtime > $_SESSION['emailCode_exptime'] || $_SESSION['emailCode'] == '') {
            // 激活码过期
            $result['status'] = 4;
            $result['message'] = '激活码过期';
        } elseif ($emailCode != $_SESSION['emailCode'] || $_SESSION['emailCode'] == '') {
            
            $result['status'] = 3; // 激活码错误
            $result['message'] = '激活码失效';
        } else {
            $bool = User::bindEmail($uId, $email);
            if ($bool) {
                $result['status'] = 1;
                $result['message'] = '激活成功';
            } else {
                $result['status'] = 2;
                $result['message'] = '激活失败';
            }
        }
        echo Response::json($result);
    }

    /**
     * 用户通过手机号码注册
     *
     * @return string
     */
    public static function userRegisterByPhone()
    {
        global $result; // 更新userLog日志表要用
        $arr['regTime'] = time();
        $arr['password'] = $_POST['password'] ? $_POST['password'] : '';
        $arr['phone'] = $_POST['phone'] ? $_POST['phone'] : ''; // 为NULL的时候才是数据库的NULL,为''空字符串不是
        $messageCode = $_POST['messageCode'] ? $_POST['messageCode'] : '';
        $nowtime = time();
        
        if ($arr['password'] == '' || $arr['phone'] == '' || $messageCode == '') {
            $result['message'] = '请将信息填写完成';
            $result['status'] = 9; // 填写不全
        } elseif ($_SESSION['messageCode'] == '') {
            $result['status'] = 8; // 您还没有获取验证码，请获取手机验证码
            $result['message'] = '您还没有获取验证码，请获取手机验证码';
        } elseif ($nowtime > $_SESSION['messageCode_exptime']) { // 验证码过期，请重新发送
            $result['status'] = 7;
            $result['message'] = '手机验证码过期,请重新获取手机验证码'; // 手机验证码过期
        } elseif ($messageCode != $_SESSION['messageCode']) {
            $result['status'] = 6; // 手机验证码错误
            $result['message'] = '手机验证码错误';
        } else {
            $checkPhone = Phone::checkPhoneExist($arr['phone']);
            
            if ($checkPhone == 2) { // 返回2表示手机号码格式正确且还未被注册
                if (Str::isPassword($arr['password'])) {
                    $table = 'eLife_user';
                    $arr['password'] = md5(trim($arr['password'])); // 存储的时候密码加密 // var_dump($arr);
                    $arr['activeFlag'] = 1;
                    $arr['username'] = '玩伴' . $arr['phone']; // 分配一个用户名，完善信息再修改//玩伴加上qq号
                    $link = User::register($arr);
                    if ($link) {
                        $result['status'] = 1; // 手机注册成功
                        $result['message'] = '手机注册成功,请登陆';
                        // 注册成功则清空$_SESSION['messageCode']和$_SESSION['messageCode_exptime']，防止二次注册
                        $_SESSION['messageCode'] == '';
                        $_SESSION['messageCode_exptime'] == '';
                    } else {
                        $result['status'] = 2; // 手机注册失败
                        $result['message'] = '手机注册失败，请重新注册';
                        $_SESSION['messageCode'] == '';
                        $_SESSION['messageCode_exptime'] == '';
                    }
                } else {
                    $result['status'] = 3;
                    $result['message'] = '密码格式错误';
                }
            } elseif ($checkPhone == 3) {
                $result['status'] = 4;
                $result['message'] = '手机号码格式错误';
            } elseif ($checkPhone == 1) {
                $result['status'] = 5;
                $result['message'] = '该手机号码已经被注册';
            }
        }
        echo Response::json($result);
    }

    /**
     * 得到手机短信验证码用于注册
     *
     * @return $result
     */
    public static function getMessageForReg()
    {
        global $result; // 更新userLog日志表要用
                        // $phone = '18004421903';
        $phone = $_POST['phone'] ? $_POST['phone'] : '';
        $checkPhone = Phone::checkPhoneExist($phone);
        if ($phone == '') {
            $result['status'] = 5; // 手机号不合法
            $result['message'] = '请填写手机号';
        } elseif ($checkPhone == 3) {
            $result['status'] = 4; // 手机号不合法
            $result['message'] = '手机号不合法';
        } elseif ($checkPhone == 2) { // 手机号合法且未被注册
            $messageCode = Str::buildRandomString(); // 四位数字验证码
            $messageCode_exptime = '1440'; // 验证码有效时间,单位为分钟
            $datas = array(
                $messageCode,
                $messageCode_exptime
            );
            $tempId = '1';
            $bool = Phone::sendMessage($phone, $datas, $tempId);
            if ($bool) {
                $result['status'] = 1; // 发送成功，请查看短信
                $result['message'] = '发送成功，请查看短信';
                $result['sessionId'] = session_id(); // 用户注册验证码的验证注册时候提交上来的sessionId
                $_SESSION['messageCode'] = $messageCode; // 为验证做准备
                $_SESSION['messageCode_exptime'] = time() + $messageCode_exptime * 60; // 五分钟后验证码过期
            } else {
                $result['status'] = 2; // 发送验证码失败，请重新发送
                $result['message'] = '发送验证码失败，请检查手机号，重新发送';
                $_SESSION = array();
            }
        } else {
            $result['status'] = 3; // 手机号码已经被注册
            $result['message'] = '手机号码已经被注册';
        }
        echo Response::json($result);
    }

    /**
     * 得到手机短信验证码用于更改密码
     *
     * @return $result
     */
    public static function getMessageForReset()
    {
        global $result; // 更新userLog日志表要用
                        // $phone = '18004421903';
        $phone = $_POST['phone'] ? $_POST['phone'] : '';
        $checkPhone = Phone::checkPhoneExist($phone);
        if ($phone == '') {
            $result['status'] = 5; // 手机号不合法
            $result['message'] = '请填写手机号';
        } elseif ($checkPhone == 3) {
            $result['status'] = 4; // 手机号不合法
            $result['message'] = '手机号不合法';
        } elseif ($checkPhone == 1) { // 手机号码已经被注册
            $messageCode = Str::buildRandomString(); // 四位数字验证码
            $messageCode_exptime = '1440'; // 验证码有效时间,单位为分钟
            $datas = array(
                $messageCode,
                $messageCode_exptime
            );
            $tempId = '1';
            $bool = Phone::sendMessage($phone, $datas, $tempId);
            if ($bool) {
                $result['status'] = 1; // 发送成功，请查看短信
                $result['message'] = '发送成功，请查看短信';
                $result['sessionId'] = session_id(); // 用户注册验证码的验证注册时候提交上来的sessionId
                $_SESSION['messageCode'] = $messageCode; // 为验证做准备
                $_SESSION['messageCode_exptime'] = time() + $messageCode_exptime * 60; // 五分钟后验证码过期
            } else {
                $result['status'] = 2; // 发送验证码失败，请重新发送
                $result['message'] = '发送验证码失败，请检查手机号，重新发送';
                $_SESSION = array();
            }
        } else {
            $result['status'] = 3; // 手机号码未被注册
            $result['message'] = '手机号码未被注册';
        }
        echo Response::json($result);
    }

    /**
     * 邮箱账号改密码
     */
    public static function resetPasswordForEmail()
    {
        global $result; // 更新userLog日志表要用
        $email = $_POST['email'] ? $_POST['email'] : '';
        $password = $_POST['password'] ? $_POST['password'] : '';
        if ($password == '' || $email == '') {
            $result['status'] = 5;
            $result['message'] = '请将信息填写完';
        } elseif ($_SESSION['email'] == '' || $_SESSION['emailCode'] != $_POST['emailCode']) {
            $result['status'] = 4;
            $result['message'] = '未获取此邮箱激活链接';
        } else {
            if (Str::isPassword($password)) {
                $bool = User::resetPasswordByEmail($email, $password);
                if ($bool) {
                    $result['status'] = 1;
                    $result['message'] = '更改密码成功';
                    $_SESSION = array();
                } else {
                    $result['status'] = 2;
                    $result['message'] = '更改密码失败';
                }
            } else {
                $result['status'] = 3;
                $result['message'] = '密码格式错误';
            }
        }
        echo Response::json($result);
    }

    /**
     * 手机账号改密码
     */
    public static function resetPasswordForPhone()
    {
        global $result; // 更新userLog日志表要用
        $phone = $_POST['phone'] ? $_POST['phone'] : '';
        $password = $_POST['password'] ? $_POST['password'] : '';
        $messageCode = $_POST['messageCode'] ? $_POST['messageCode'] : '';
        if ($password == '' || $phone == '' || $messageCode == '') {
            $result['status'] = 6; // 填写不全
            $result['message'] = '请将信息填写完成';
        } elseif ($_SESSION['messageCode'] == '') {
            $result['status'] = 5; // 您还没有获取验证码，请获取手机验证码
            $result['message'] = '您还没有获取验证码，请获取手机验证码';
        } elseif ($messageCode != $_SESSION['messageCode']) { // 验证码错误，请重新输入
            $result['status'] = 4; // 手机验证码错误
            $result['message'] = '手机验证码错误';
        } elseif (Str::isPassword($password)) {
            $bool = User::resetPasswordByPhone($phone, $password);
            if ($bool) {
                $result['status'] = 1;
                $result['message'] = '更改密码成功';
                $_SESSION = array();
            } else {
                $result['status'] = 2;
                $result['message'] = '更改密码失败';
            }
        } else {
            $result['status'] = 3;
            $result['message'] = '密码格式错误';
        }
        echo Response::json($result);
    }
}
