<?php

function getUserData($params)
{
    //DBの接続情報
    include_once('../pdo_connect.php');

    //入力された検索条件からSQl文を生成
    $where = [];
    if (!empty($params['name'])) {
        $where[] = "name like '%{$params['name']}%'";
    }
    if (!empty($params['sex'])) {
        $where[] = 'sex = ' . $params['sex'];
    }
    // 修正中
    if (!empty($params['wage'])) {
        $where[] = 'wage >= ' . ((int)$params['wage'] + 9) . ' AND age >= ' . (int)$params['age'];
    }
    if ($where) {
        // implode関数でSQL分をAND(第一引数)で連結
        $whereSql = implode(' AND ', $where);
        $sql = 'select * from users where ' . $whereSql;
    } else {
        $sql = 'select * from users';
    }

    //SQL文を実行する
    $UserDataSet = $dbh->query($sql);

    //扱いやすい形に変える
    $result = [];
    while ($row = $UserDataSet->fetch(PDO::FETCH_ASSOC)) {
        $result[] = $row;
    }
    return $result;
}
