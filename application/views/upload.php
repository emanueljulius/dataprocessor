<div class="col-md-6">
  <!-- Default box -->
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">Upload Data Source</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form role="form" method="post" action="<?= base_url(); ?>index.php/do-upload" enctype='multipart/form-data'>
      <div class="box-body">
        <div class="form-group">
          <label for="exampleInputFile">CSV File</label>
          <input type='file' name='file' class="file" id="exampleInputFile">
        </div>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        <button type="submit" class="btn btn-primary btn-action">
        UPLOAD
      </button>
      </div>
    </form>
  </div>
  <!-- /.box -->
</div>