<?php include_once 'header.php'; ?>

<body>

    <table border="0" class="evenColor">
        <thead>
            <tr>
                <th>Date of withdrawal</th>
                <th>Cash withdrawal amount</th>
                <th>Name of receipt</th>
                <th>Treatment status</th>
            </tr>
        </thead>
        <tbody>
        <?php if (count($withdrawRequests) == 0) { ?>
            <tr>
                <td colspan="4">There is no record for the time being</td>
            </tr>
        <?php } ?>
        <?php foreach ($withdrawRequests as $withdrawRequest) { ?>
            <tr>
                <td><?php echo date('Y-m-d', strtotime($withdrawRequest->created_at)); ?></td>
                <td><?php echo $withdrawRequest->body_stake; ?> RMB</td>
                <td><?php echo $withdrawRequest->body_name; ?></td>
                <td><?php 
                    if($withdrawRequest->body_transfer_number == 'PENDING'){
                        echo 'Being processed';
                    } else if ($withdrawRequest->body_transfer_number == 'FAIL') {
                        echo 'Treatment failure';
                    } else {
                        echo 'Treatment success';
                    }
                ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

<?php include_once 'footer.php'; ?>
