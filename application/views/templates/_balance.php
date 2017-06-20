<?php foreach ($balances as $type => $balance): ?>
    <br>
    <h2 class="text-center"><?= ($type == 1) ? 'Cash' : 'Bank';  ?></h2>
    <table class="table table-responsive">
        <thead>
            <tr>
                <th>Currency</th>
                <th>Balance in the beginning</th>
                <th>Sum plus for period</th>
                <th>Sum minus for period</th>
                <th>Saldo</th>
                <th>Balance in the end</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($balance as $key => $item): ?>
                <tr>
                    <td><?= $key ?></td>
                    <?php foreach ($item as $oneBalance): ?>
                        <td><?= $oneBalance ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
<?php endforeach; ?>
