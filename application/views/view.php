<div class="row">
	<div class="col-xs-12">
	   <div class="box">
	    <div class="box-header">
	      <p><h3 class="box-title">Data Source: </h3><span>  <?= $data_source['name']; ?></span></p>
	      <p><h3 class="box-title">Last updated: </h3><span>  <?= $data_source['last_update']; ?></span></p>
	    </div>
	    <div class="box-body">
	      <table id="acquiredtb" class="table table-bordered table-striped">
	        <thead>
		        <tr>
		          <?php
		          	foreach ($data_headers as $data_header) {
		          		?>
		          		<th><?= $data_header; ?></th>
		          	 <?php
		          	}
		          ?>
		        </tr>
	        </thead>
	        <tbody>
        	<?php
				foreach($data_source_values as $data_source_value){
				?>
				<tr>
		          <?php
		          	foreach ($data_source_value as $data_value) {
		          		?>
		          		<td><?= $data_value; ?></td>
		          	 <?php
		          	}
		          ?>
		        </tr>
				<?php
					}
				?>
	        </tbody>
	      </table>
	    </div>
	  </div>
	</div>
</div>