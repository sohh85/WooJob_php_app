<?php

// $_GETで渡された検索条件を$paramsに代入
function getJobData($params)
{
    //DB接続情報
    include_once('../pdo_connect.php');

    //入力された検索条件から WHERE(○○ = ○○) ここに入るSQl文を生成
    $where = [];
    // 企業・店の名前
    if (!empty($params['name'])) {
        $where[] = "name like '%{$params['name']}%'";
    }
    // 地域
    if (!empty($params['city'])) {
        $where[] = 'city = ' . $params['city'];
    }
    // 時給
    if (!empty($params['wage'])) {
        $where[] = 'wage >= ' . (int)$params['wage'];
    }
    // 英語使用頻度
    if (!empty($params['language'])) {
        $where[] = 'language = ' . $params['language'];
    }

    if ($where) {
        // implode関数でSQL分をAND(第一引数)で連結
        $whereSql = implode(' AND ', $where);
        $sql = 'select * from job_data where ' . $whereSql;
    } else {
        $sql = 'select * from job_data';
    }

    //SQL文を実行する
    $jobDataSet = $dbh->query($sql);

    //扱いやすい形に変える
    $result = [];
    while ($row = $jobDataSet->fetch(PDO::FETCH_ASSOC)) {
        $result[] = $row;
    }
    return $result;
}
