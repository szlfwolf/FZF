<?php include_once 'header.php'; ?>

<body>

    <table border="0">
        <tbody>
        <?php if (count($records) == 0) { ?>
            <tr>
                <td>There is no record for the time being</td>
            </tr>
        <?php } ?>
        <?php foreach ($records as $record) { ?>
            <tr style="border-left: 4px solid <?php echo $record->body_direction? '#F43530': '#04BE02'; ?>">
                <td class="left" style="color: #000;"><?php echo $record->body_name; ?> <span style="color: <?php echo $record->body_direction? '#F43530': '#04BE02'; ?>;"><?php echo number_format(floatval($record->body_stake), 2); ?></span> RMB</td>
                <td class="right" style="color: #aaa;"><?php echo date('Y-m-d H:i:s', strtotime($record->created_at)); ?></td>
            </tr>
        <?php } ?>
        </tbody>
        <?php if($records->hasMorePages()) { ?>
        <tfoot>
            <tr>
                <td colspan="2">
                    <a href="<?php echo $records->nextPageUrl();?>" class="weui_btn weui_btn_primary" style="margin: 20px auto; font-size: 14px; width: 200px;">next</a>
                </td>
            </tr>
        </tfoot>
        <?php } ?>
    </table>

<?php include_once 'footer.php'; ?>
