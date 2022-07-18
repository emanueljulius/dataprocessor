</section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2022 </strong> All rights
    reserved.
  </footer>

  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- DataTables -->
<script src="<?= base_url(); ?>bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?= base_url(); ?>bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?= base_url(); ?>bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url(); ?>dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url(); ?>dist/js/demo.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>plugins/datepicker/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script src="<?= base_url(); ?>sweetalert/sweetalert.min.js"></script>
<script src="<?= base_url(); ?>sweetalert/jquery.sweet-alert.custom.js"></script>
<script>
  $(document).ready(function () {
    $('.sidebar-menu').tree();
    $('.schedule_datetime').datetimepicker({
        language:  'en',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 1,
        forceParse: 0,
        minuteStep: 10,
        showMeridian: 0
    });
  });
   $(function () {
    $('#enticedtb').DataTable({
      "order": [[ 0, "desc" ]]
    });
    $('#acquiredtb').DataTable({
      paging: true,
      ordering: false,
      info: true,
    });
  });
  function showProcessingtickyMsg(){
    stickyMsg("Processing...");
  }
</script>
</body>
</html>