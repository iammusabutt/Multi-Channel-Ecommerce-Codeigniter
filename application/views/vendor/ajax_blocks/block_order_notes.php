<?php foreach($notes as $key):?>
	<div class="card-box <?php if($key->note_author == $this->session->userdata('username')){echo 'bg-gold';}?>" style="padding: 20px !important; ">
		<p><?php echo $key->note_content;?></p>
		<div class="d-flex justify-content-between" style="font-size:11px;">
			<?php if($key->note_author == $this->session->userdata('username')){ ?>
				<p><b>By:</b> You</p>
			<?php } else{ ?>
				<p><b>By:</b> <?php echo $key->note_author;?></p>
			<?php } ?>
		</div>
	</div>
<?php endforeach; ?>