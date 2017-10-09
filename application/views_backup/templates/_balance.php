<?php if (!empty($balances)): ?>
    <?php foreach ($balances as $type => $balance): ?>
        <div class="col-xs-6">
            <h2><?= ($type == 1) ? 'Cash' : 'Bank';  ?></h2>
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th>Currency</th>
                    <th>Balance in the beginning</th>
                    <th>Income for period</th>
                    <th>Expense for period</th>
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
        </div>
    <?php endforeach; ?>
<?php endif; ?>
