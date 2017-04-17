<?php include_once 'header.php'; ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row" style="margin-top: 40px;">
                    <div class="col-lg-12">
                        <form method="post" role="form">
                        
                            <div class="form-group input-group">
                                <span class="input-group-addon">休市起始时间</span>
                                <input  type="text" id="starttime" name="starttime" class="form-control" value="<?php echo $starttime; ?>">
                            </div>
                            <div class="form-group input-group">
                            
                                <span class="input-group-addon">休市结束时间</span>
                               
                                <input type="text" id="endtime" name="endtime" class="form-control" value="<?php echo $endtime; ?>" >
                            </div>
                            <div style="text-align: center">
                                <button type="submit" class="btn btn-primary">确认</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <link href="/public/resources/views/administrator/bower_components/bootstrap/dist/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css">

        <script src="/public/resources/views/administrator/bower_components/bootstrap/dist/js/bootstrap-datetimepicker.min.js"></script>
       <script type="text/javascript">
            $(document).ready(function() {
                $.fn.datetimepicker.dates['zh-CN'] = {
                    days : ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期日"],
                    daysShort : ["周日", "周一", "周二", "周三", "周四", "周五", "周六", "周日"],
                    daysMin : ["日", "一", "二", "三", "四", "五", "六", "日"],
                    months : ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
                    monthsShort : ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
                    today : "今天",
                    suffix : [],
                    meridiem : ["上午", "下午"]
                };
               
                $("#starttime").datetimepicker({
                    format : 'yyyy-mm-dd',
                    language : 'zh-CN',
                    minuteStep : 1,
                    autoclose : true,
                    forceParse : false
                }).on('changeDate', function(ev) {
                    var date = ev.date.getFullYear() + '-' + (ev.date.getMonth() + 1) + '-' + ev.date.getDate();
                    
                    $('#starttime').val(date);
                });
                $("#endtime").datetimepicker({
                    format : 'yyyy-mm-dd',
                    language : 'zh-CN',
                    minuteStep : 1,
                    autoclose : true,
                    forceParse : false
                }).on('changeDate', function(ev) {
                    var date = ev.date.getFullYear() + '-' + (ev.date.getMonth() + 1) + '-' + ev.date.getDate();
                    $('#endtime').val(date);
                });

                
            });
        </script>
<?php include_once 'footer.php'; ?>