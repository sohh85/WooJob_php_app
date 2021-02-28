<?php

// $_GETで渡された検索条件を$paramsに代入
function getJobData($params)
{
    //DB接続情報
    include_once('../pdo_connect.php');

    //入力された検索条件から WHERE(○○ = ○○) に入るSQl文を生成
    $searchCondition = [];

    // 企業・店の名前
    if (!empty($params['name'])) {
        $searchCondition[] = "name like '%{$params['name']}%'";
    }
    // 地域
    if (!empty($params['city'])) {
        $searchCondition[] = 'city_no = ' . (int)$params['city'];
    }
    // 時給
    if (!empty($params['wage'])) {
        $searchCondition[] = 'wage >= ' . (int)$params['wage'];
    }
    // 英語使用頻度
    if (!empty($params['language'])) {
        $searchCondition[] = 'language_no = ' . (int)$params['language'];
    }

    if ($searchCondition) {
        // implode関数で各文をAND(第一引数)で連結
        $searchConditionSql = implode(' AND ', $searchCondition);
        $sql = 'select * from job_data where ' . $searchConditionSql;
    } else {
        $sql = 'select * from job_data';
    }

    //SQL文を実行する
    $jobDataSet = $dbh->query($sql);

    $result = [];

    if (!empty($jobDataSet)) {
        while ($row = $jobDataSet->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }
        return $result;
    }
}
