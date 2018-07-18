<?php
/**
 * Created by PhpStorm.
 * User: lnwdark
 * Date: 13/1/2018 AD
 * Time: 02:36
 */


$data = [
    ['number' => 11, 'name' => 'AA', 'kg' => 6],
    ['number' => 12, 'name' => 'AAC', 'kg' => 6],
    ['number' => 12, 'name' => 'AAB', 'kg' => 6],
    ['number' => 13, 'name' => 'AD', 'kg' => 7],
    ['number' => 14, 'name' => 'BBD', 'kg' => 2],
    ['number' => 14, 'name' => 'DB', 'kg' => 2],
    ['number' => 14, 'name' => 'DB', 'kg' => 2],
];



$workingID = null;
$workingNumber = null;
foreach($data as $k=>$v) {
    if (!$workingNumber || $workingNumber != $v['number']) {
        $workingID = $k;
        $workingNumber = $v['number'];
        $data[$k]['rowspan'] = 1;
    } else {
        $data[$workingID]['rowspan']++;
        $data[$k]['rowspan'] = 0;
    }
}

var_dump($data);
exit();
?>

<table border="1">
    <thead>
        <tr>
            <th>Number</th>
            <th>Detail</th>
            <th>KG</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($data as $k=>$v): ?>
            <tr>
                <?php if ($v['rowspan'] > 0): ?>
                    <td rowspan="<?=$v['rowspan']?>"><?=$v['number']?></td>
                <?php endif; ?>
                    <td><?=$v['name']?></td>
                <?php if ($v['rowspan'] > 0): ?>
                    <td rowspan="<?=$v['rowspan']?>"><?=$v['kg']?></td>
                <?php endif; ?>
            </tr>

        <?php endforeach; ?>
    </tbody>
</table>
