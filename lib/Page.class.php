<?php
/**
 *分页类
 *tags
 *@author Joker-Long
 *编写日期：2016年8月13日上午9:32:57
 */
class Page
{

    /**
     * 显示分页
     * 
     * @param unknown $page            
     * @param unknown $totalPage            
     * @param unknown $where            
     * @param string $sep            
     * @return string
     */
    public static function show($page, $totalPage, $where = null, $sep = "&nbsp"){
        $where = ($where == null) ? null : "&" . $where;
        $url = $_SERVER['PHP_SELF']; // 获取当前网页地址
        $index = ($page == 1) ? "首页" : "<a href='{$url}?page=1{$where}'>首页</a>";
        $last = ($page == $totalPage) ? "尾页" : "<a href='{$url}?page=$totalPage{$where}'>尾页</a>";
        $prevPage = ($page >= 1) ? $page - 1 : 1;
        $nextPage = ($page >= $totalPage) ? $totalPage : $page + 1;
        $prev = ($page == 1) ? "上一页" : "<a href='{$url}?page={$prevPage}{$where}'>上一页</a>";
        $next = ($page == $totalPage) ? "下一页" : "<a href='{$url}?page={$nextPage}{$where}'>下一页</a>";
        $str = "总共{$totalPage}页/当前是第{$page}页";
        $p = null;
        for ($i = 1; $i <= $totalPage; $i ++) {
            // 当前页无连接，不可点击，只显示当前页
            $temp = 5;
            if ($i > $page - $temp && $i < $page + $temp) {
                if ($page == $i) {
                    $p .= "[{$i}]"; // 为什么这里不加@会警告？？是因为$q之前没有定义,用.连接会警告
                } else {
                    $p .= "<a href='{$url}?page={$i}{$where}'>[{$i}]</a>";
                }
            }
        }
        $pageStr = $str . $sep . $index . $sep . $prev . $sep . $p . $sep . $next . $sep . $last;
        return $pageStr;
    }
}