<?php include_once 'header.php'; ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row" style="margin-top: 20px;">
                    <form method="get" action="/administrator/mem_orders_count">
                        <div class="input-group custom-search-form">
                             <div class="form-group">
                                <label class="control-label col-md-1"  style="margin-top:10px">会员</label>
                                <div class="col-md-3">
                                    <select id="id_member" name="id_member" type="text" class="form-control select2" placeholder="会员...">
                                        <?php foreach ($members as $item) { ?>
                                        <option value="<?php echo $item->id; ?>"><?php echo $item->agent_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input id="StartTimeInput" class="form-control" name="starttime" type="text" readonly="readonly" style="border-radius: 5px;" />
                                </div>
                                <div class="col-md-3">
                                    <input id="EndTimeInput" class="form-control" name="endtime" type="text" readonly="readonly" style="border-radius: 5px;" />
                                </div>
                            </div>

                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>

                                <button id="ExportBtn" type="button" class="btn btn-default">
                                    <i class="fa ">导出</i>
                                </button>
                            </span>
                        </div>
                    </form>
                    <form id="ExportForm"  method="post" style="display: none;" action="/administrator/mem_orders_count_ex">
                        <input id="ExportID" name="id_member" type="hidden" />
                        <input id="ExportStartTime" name="starttime" type="hidden" />
                        <input id="ExportEndTime" name="endtime" type="hidden" />
                    </form>
                </div>
                <div class="row" style="margin-top: 20px;">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>用户编号</th>
                                    <th>订单编号</th>
                                    <th>买入金额</th>
                                    <th>盈亏</th>
                                    <th>手续费</th>
                                    <th>余额</th>
                                    <th>时间</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php if (count($datas) == 0) { ?>
                                    <tr>
                                        <td colspan="11">暫時還沒有任何記錄</td>
                                    </tr>
                                <?php } ?>

                                <?php foreach ($datas as $item) { ?>
                                <tr>
                                    <td><?php echo $item->id; ?></td>
                                    <td><a href="/administrator/orders?id_user=<?php echo $item->id_user; ?>"><?php echo $item->id_user; ?></a> (<a href="/administrator/users?id_user=<?php echo $item->id_user; ?>"><?php echo $item->user->body_phone; ?></a>)</td>
                                    <td><a href="/administrator/orders?id_object=<?php echo $item->id_object; ?>"><?php echo $item->object->body_name; ?></a></td>
                                    <td><?php echo $item->body_stake; ?></td>
                                    <td>
                                     <?php if ($item->body_is_win == 1){ ?>
                                    <span style="color: red;"><?php echo $item->body_bonus; ?></span>
                                     <?php } ?>

                                     <?php if ($item->body_is_draw == 1){ ?>
                                    <span >0</span>
                                     <?php } ?>
                                       <?php if ($item->body_is_win == 0&& $item->body_is_draw !=1){ ?>
                                    <span style="color: green;">-<?php echo $item->body_stake; ?></span>
                                     <?php } ?>


                                  
                                     </td>
                                    <td><?php echo $item->body_fee; ?></td>
                                     <td><?php echo $item->body_balance; ?></td>
                                    <td><?php echo $item->created_at; ?></td>
                                   
                                   
                                    
                                </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="13" style="text-align: center;">
                                        <ul class="pagination">
                                            <li class="paginate_button previous"><a href="<?php echo $datas->previousPageUrl(); ?>">上一页</a></li>
                                            <li class="paginate_button active"><a href="#"><?php echo $datas->currentPage(); ?> / <?php echo $datas->lastPage(); ?>, 共 <?php echo $datas->total(); ?> 条记录</a></li>
                                            <li class="paginate_button next"><a href="<?php echo $datas->nextPageUrl(); ?>">下一页</a></li>
                                        </ul>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
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
               
                $("#StartTimeInput").datetimepicker({
                    format : 'yyyy-mm-dd hh:ii:ss',
                    language : 'zh-CN',
                    minuteStep : 1,
                    autoclose : true,
                    forceParse : false,
                    endDate : new Date()
                }).on('changeDate', function(ev) {
                    var date = ev.date.getFullYear() + '-' + (ev.date.getMonth() + 1) + '-' + ev.date.getDate();
                    $('#EndTimeInput').datetimepicker('setStartDate', date);
                    $('#ExportStartTime').val(date);
                });
                $("#EndTimeInput").datetimepicker({
                    format : 'yyyy-mm-dd hh:ii:ss',
                    language : 'zh-CN',
                    minuteStep : 1,
                    autoclose : true,
                    forceParse : false,
                    endDate : new Date()
                }).on('changeDate', function(ev) {
                    var date = ev.date.getFullYear() + '-' + (ev.date.getMonth() + 1) + '-' + ev.date.getDate();
                    $('#ExportEndTime').val(date);
                });

                $('#id_member').on('change', function() {
                    $('#ExportID').val($(this).val());
                });

                $('#ExportBtn').on('click', function() {
                    $('#ExportForm').submit();
                });
            });
        </script>
<?php include_once 'footer.php'; ?>